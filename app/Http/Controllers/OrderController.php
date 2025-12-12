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
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Search by order number
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
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
            ->where('order_number', $orderNumber)
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
            'user_id' => 'required|exists:users,id',
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
            'shipping_method' => 'required|string',
            'shipping_provider' => 'nullable|string',
            'payment_method' => 'required|string',
            'payment_type' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        $data = $request->only([
            'user_id',
            'shipping_address_id',
            'shipping_method',
            'shipping_provider',
            'payment_method',
            'payment_type',
            'subtotal',
            'shipping_cost',
            'discount',
            'notes'
        ]);

        $data['order_number'] = Order::generateOrderNumber();
        $data['total_amount'] = $data['subtotal'] + $data['shipping_cost'] - ($data['discount'] ?? 0);
        $data['status'] = 'pending_payment';
        $data['payment_status'] = 'pending';
        $data['payment_deadline'] = Carbon::now()->addHours(24);

        $order = Order::create($data);

        // Create order items
        foreach ($request->input('items') as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'size_id' => $item['size_id'] ?? null,
                'size_name' => $item['size_name'] ?? null,
                'material_id' => $item['material_id'] ?? null,
                'material_name' => $item['material_name'] ?? null,
                'print_side_id' => $item['print_side_id'] ?? null,
                'print_side_name' => $item['print_side_name'] ?? null,
                'finishing_ids' => $item['finishing_ids'] ?? null,
                'finishing_names' => $item['finishing_names'] ?? null,
                'custom_width' => $item['custom_width'] ?? null,
                'custom_height' => $item['custom_height'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'uploaded_file_name' => $item['uploaded_file_name'] ?? null,
                'uploaded_file_url' => $item['uploaded_file_url'] ?? null,
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

        if ($request->has('tracking_number')) {
            $order->tracking_number = $request->input('tracking_number');
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
            'payment_status' => 'required|in:pending,paid,expired,refunded',
        ]);

        $order->payment_status = $request->input('payment_status');

        if ($request->input('payment_status') === 'paid') {
            $order->paid_at = Carbon::now();
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
            ->where('user_id', $userId)
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
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'revenue_today' => Order::where('payment_status', 'paid')->whereDate('created_at', $today)->sum('total_amount'),
            'revenue_this_month' => Order::where('payment_status', 'paid')->where('created_at', '>=', $thisMonth)->sum('total_amount'),
        ];

        return $this->successResponse($stats);
    }
}
