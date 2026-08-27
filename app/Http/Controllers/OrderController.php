<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Promo;
use App\Models\ShippingAddress;
use App\Models\UploadedFile;
use App\Services\ShippingCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Get all orders (admin)
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by payment status
        if ($request->has('status_bayar')) {
            $query->where('status_bayar', $request->input('status_bayar'));
        }

        // Filter by user
        if ($request->has('pengguna_id')) {
            $query->where('pengguna_id', $request->input('pengguna_id'));
        }

        // Search by order number
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pesanan', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        $perPage = $request->input('per_page', 15);
        $orders = $query->paginate($perPage);

        return $this->paginatedResponse($orders);
    }

    /**
     * Get single order
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'shippingAddress'])->find($id);

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan', 404);
        }

        return $this->successResponse($order);
    }

    /**
     * Get order by order number
     */
    public function showByOrderNumber($orderNumber)
    {
        $order = Order::with(['user', 'items.product', 'shippingAddress'])
            ->where('nomor_pesanan', $orderNumber)
            ->first();

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan', 404);
        }

        return $this->successResponse($order);
    }

    /**
     * Create new order
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'alamat_pengiriman_id' => 'required|exists:shipping_addresses,id',
            'metode_pengiriman_id' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'tipe_pembayaran' => 'nullable|string',
            'kode_promo' => 'nullable|string',
            'bukti_transfer_file_id' => 'nullable|exists:uploaded_files,id',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.ukuran_id' => 'nullable|string',
            'items.*.bahan_id' => 'nullable|string',
            'items.*.sisi_cetak_id' => 'nullable|string',
            'items.*.finishing_ids' => 'nullable|array',
            'items.*.lebar_kustom' => 'nullable|integer|min:1',
            'items.*.tinggi_kustom' => 'nullable|integer|min:1',
        ]);

        // The address must belong to the authenticated user — never trust it
        // just because the id exists in the table.
        $address = ShippingAddress::where('pengguna_id', $request->auth->id)
            ->find($request->input('alamat_pengiriman_id'));

        if (!$address) {
            return $this->errorResponse('Alamat pengiriman tidak ditemukan', 422);
        }

        // Never trust client-supplied prices, shipping cost, promo discount,
        // or user id — recompute everything server-side from the actual
        // product/promo/shipping-rate records.
        $subtotal = 0;
        $totalWeightGrams = 0;
        $itemsData = [];

        foreach ($request->input('items') as $item) {
            $product = Product::find($item['produk_id']);
            if (!$product) {
                return $this->errorResponse('Produk tidak ditemukan: ' . $item['produk_id'], 422);
            }

            $jumlah = (int) $item['jumlah'];

            $tiers = $product->tier_jumlah ?? [];
            $tier = collect($tiers)->first(
                fn ($t) => $jumlah >= $t['minQty'] && $jumlah <= $t['maxQty']
            );
            $baseUnitPrice = $tier['pricePerUnit'] ?? ($tiers[0]['pricePerUnit'] ?? $product->harga_dasar);

            $sizes = $product->ukuran ?? [];
            $size = collect($sizes)->firstWhere('id', $item['ukuran_id'] ?? null);
            $sizeMultiplier = $size['priceMultiplier'] ?? 1;

            if (
                $size && str_contains(strtolower($size['name'] ?? ''), 'custom')
                && !empty($item['lebar_kustom']) && !empty($item['tinggi_kustom'])
            ) {
                $baseSize = $sizes[0] ?? null;
                $baseArea = ($baseSize['width'] ?? 148) * ($baseSize['height'] ?? 210);
                $customArea = $item['lebar_kustom'] * $item['tinggi_kustom'];
                $sizeMultiplier = max(1, $customArea / $baseArea);
            }

            $materials = $product->bahan ?? [];
            $material = collect($materials)->firstWhere('id', $item['bahan_id'] ?? null);
            $materialPrice = $material['pricePerUnit'] ?? 0;

            $printSides = $product->sisi_cetak ?? [];
            $printSide = collect($printSides)->firstWhere('id', $item['sisi_cetak_id'] ?? null);
            $printSideMultiplier = $printSide['priceMultiplier'] ?? 1;

            $finishingIds = $item['finishing_ids'] ?? [];
            $finishingTotal = 0;
            $finishingNames = [];
            foreach ($product->finishing ?? [] as $f) {
                if (in_array($f['id'], $finishingIds)) {
                    $finishingTotal += $f['price'];
                    $finishingNames[] = $f['name'];
                }
            }

            $unitPrice = ($baseUnitPrice * $sizeMultiplier * $printSideMultiplier) + $materialPrice + $finishingTotal;

            if ($product->promo && $product->persen_promo) {
                $unitPrice -= $unitPrice * ($product->persen_promo / 100);
            }

            $totalPrice = $unitPrice * $jumlah;
            $subtotal += $totalPrice;
            $totalWeightGrams += ($product->berat_per_pcs ?? 0) * $jumlah;

            $itemsData[] = [
                'produk_id' => $product->id,
                'ukuran_id' => $item['ukuran_id'] ?? null,
                'nama_ukuran' => $size['name'] ?? null,
                'bahan_id' => $item['bahan_id'] ?? null,
                'nama_bahan' => $material['name'] ?? null,
                'sisi_cetak_id' => $item['sisi_cetak_id'] ?? null,
                'nama_sisi_cetak' => $printSide['name'] ?? null,
                'finishing_ids' => $finishingIds,
                'nama_finishing' => $finishingNames,
                'lebar_kustom' => $item['lebar_kustom'] ?? null,
                'tinggi_kustom' => $item['tinggi_kustom'] ?? null,
                'jumlah' => $jumlah,
                'harga_satuan' => round($unitPrice, 2),
                'harga_total' => round($totalPrice, 2),
                'nama_file_diunggah' => $item['nama_file_diunggah'] ?? null,
                'tautan_file_diunggah' => $item['tautan_file_diunggah'] ?? null,
            ];
        }

        // Re-quote the shipping cost server-side from the id the client picked
        // (e.g. "jne_reg") — never trust a client-supplied amount.
        $weightKg = max(1, ceil($totalWeightGrams / 1000));
        [$providerCode, $serviceCode] = array_pad(
            explode('_', $request->input('metode_pengiriman_id'), 2),
            2,
            null
        );
        $shippingOption = ShippingCalculator::calculate($providerCode, $serviceCode, $weightKg, $address->provinsi);

        if (!$shippingOption) {
            return $this->errorResponse('Metode pengiriman tidak valid', 422);
        }

        $biayaKirim = $shippingOption['cost'];

        // Re-validate the promo code server-side and recompute its discount —
        // never trust a client-supplied discount amount.
        $diskon = 0;
        $promo = null;

        if ($request->filled('kode_promo')) {
            $promo = Promo::where('kode', strtoupper($request->input('kode_promo')))->first();

            if (!$promo || !$promo->isValid()) {
                return $this->errorResponse('Kode promo tidak valid atau sudah kadaluarsa', 422);
            }

            if ($subtotal < $promo->min_beli) {
                return $this->errorResponse(
                    'Minimal pembelian Rp ' . number_format($promo->min_beli, 0, ',', '.') . ' untuk kode promo ini',
                    422
                );
            }

            $diskon = $promo->calculateDiscount($subtotal);
        }

        $total = $subtotal + $biayaKirim - $diskon;

        $order = Order::create([
            'nomor_pesanan' => Order::generateOrderNumber(),
            'pengguna_id' => $request->auth->id,
            'alamat_pengiriman_id' => $address->id,
            'metode_pengiriman' => $shippingOption['service'],
            'kurir' => $shippingOption['provider'],
            'metode_pembayaran' => $request->input('metode_pembayaran'),
            'tipe_pembayaran' => $request->input('tipe_pembayaran'),
            'subtotal' => $subtotal,
            'biaya_kirim' => $biayaKirim,
            'diskon' => $diskon,
            'total' => $total,
            'status' => 'pending_payment',
            'status_bayar' => 'pending',
            'batas_bayar' => Carbon::now()->addHours(24),
            'catatan' => $request->input('catatan'),
        ]);

        if ($request->filled('bukti_transfer_file_id')) {
            UploadedFile::where('id', $request->input('bukti_transfer_file_id'))
                ->where('pengguna_id', $request->auth->id)
                ->update(['terkait_id' => $order->id, 'terkait_tipe' => 'order']);
        }

        foreach ($itemsData as $data) {
            OrderItem::create(array_merge($data, [
                'pesanan_id' => $order->id,
                'status' => 'pending_payment',
            ]));
        }

        if ($promo) {
            $promo->increment('jumlah_penggunaan');
        }

        return $this->successResponse(
            $order->load(['items.product', 'shippingAddress']),
            'Pesanan berhasil dibuat',
            201
        );
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan', 404);
        }

        $this->validate($request, [
            'status' => 'required|in:pending_payment,payment_verified,file_verification,file_rejected,in_production,finishing,shipped,delivered,cancelled',
        ]);

        $order->status = $request->input('status');

        // Update items status too
        $order->items()->update(['status' => $request->input('status')]);

        if ($request->has('nomor_resi')) {
            $order->nomor_resi = $request->input('nomor_resi');
        }

        $order->save();

        return $this->successResponse($order->load('items'), 'Status pesanan berhasil diupdate');
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->errorResponse('Pesanan tidak ditemukan', 404);
        }

        $this->validate($request, [
            'status_bayar' => 'required|in:pending,paid,expired,refunded',
        ]);

        $order->status_bayar = $request->input('status_bayar');

        if ($request->input('status_bayar') === 'paid') {
            $order->dibayar_pada = Carbon::now();
            $order->status = 'payment_verified';
        }

        $order->save();

        return $this->successResponse($order, 'Status pembayaran berhasil diupdate');
    }

    /**
     * Get orders for the currently authenticated user
     */
    public function userOrders(Request $request)
    {
        $orders = Order::with(['items.product'])
            ->where('pengguna_id', $request->auth->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($orders);
    }

    /**
     * Get order statistics (admin dashboard)
     */
    public function statistics()
    {
        $today = Carbon::now()->startOfDay();
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_orders' => Order::count(),
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_this_month' => Order::where('created_at', '>=', $thisMonth)->count(),
            'pending_orders' => Order::where('status', 'pending_payment')->count(),
            'processing_orders' => Order::whereIn('status', ['payment_verified', 'file_verification', 'in_production', 'finishing'])->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('status_bayar', 'paid')->sum('total'),
            'revenue_today' => Order::where('status_bayar', 'paid')->whereDate('created_at', $today)->sum('total'),
            'revenue_this_month' => Order::where('status_bayar', 'paid')->where('created_at', '>=', $thisMonth)->sum('total'),
        ];

        return $this->successResponse($stats);
    }

    /**
     * Get recent orders for dashboard
     */
    public function recentOrders(Request $request)
    {
        $limit = $request->input('limit', 10);

        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                $firstItem = $order->items->first();
                return [
                    'id' => $order->id,
                    'nomor_pesanan' => $order->nomor_pesanan,
                    'customer' => $order->user ? $order->user->nama : 'Guest',
                    'customer_email' => $order->user ? $order->user->email : null,
                    'product' => $firstItem ? $firstItem->product->nama : 'N/A',
                    'items_count' => $order->items->count(),
                    'quantity' => $order->items->sum('jumlah'),
                    'total_amount' => $order->total,
                    'status' => $order->status,
                    'payment_status' => $order->status_bayar,
                    'created_at' => $order->created_at,
                ];
            });

        return $this->successResponse($orders);
    }
}
