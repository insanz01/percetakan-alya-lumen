<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
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
            'pengguna_id' => 'required|exists:users,id',
            'alamat_pengiriman_id' => 'required|exists:shipping_addresses,id',
            'metode_pengiriman' => 'required|string',
            'kurir' => 'nullable|string',
            'metode_pembayaran' => 'required|string',
            'tipe_pembayaran' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'biaya_kirim' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:products,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.harga_total' => 'required|numeric|min:0',
        ]);

        $data = $request->only([
            'pengguna_id',
            'alamat_pengiriman_id',
            'metode_pengiriman',
            'kurir',
            'metode_pembayaran',
            'tipe_pembayaran',
            'subtotal',
            'biaya_kirim',
            'diskon',
            'catatan'
        ]);

        $data['nomor_pesanan'] = Order::generateOrderNumber();
        $data['total'] = $data['subtotal'] + $data['biaya_kirim'] - ($data['diskon'] ?? 0);
        $data['status'] = 'pending_payment';
        $data['status_bayar'] = 'pending';
        $data['batas_bayar'] = Carbon::now()->addHours(24);

        $order = Order::create($data);

        // Create order items
        foreach ($request->input('items') as $item) {
            OrderItem::create([
                'pesanan_id' => $order->id,
                'produk_id' => $item['produk_id'],
                'ukuran_id' => $item['ukuran_id'] ?? null,
                'nama_ukuran' => $item['nama_ukuran'] ?? null,
                'bahan_id' => $item['bahan_id'] ?? null,
                'nama_bahan' => $item['nama_bahan'] ?? null,
                'sisi_cetak_id' => $item['sisi_cetak_id'] ?? null,
                'nama_sisi_cetak' => $item['nama_sisi_cetak'] ?? null,
                'finishing_ids' => $item['finishing_ids'] ?? null,
                'nama_finishing' => $item['nama_finishing'] ?? null,
                'lebar_kustom' => $item['lebar_kustom'] ?? null,
                'tinggi_kustom' => $item['tinggi_kustom'] ?? null,
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'harga_total' => $item['harga_total'],
                'nama_file_diunggah' => $item['nama_file_diunggah'] ?? null,
                'url_file_diunggah' => $item['url_file_diunggah'] ?? null,
                'status' => 'pending_payment',
            ]);
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
     * Get user orders
     */
    public function userOrders(Request $request, $userId)
    {
        $orders = Order::with(['items.product'])
            ->where('pengguna_id', $userId)
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
