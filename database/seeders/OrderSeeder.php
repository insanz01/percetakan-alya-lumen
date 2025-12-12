<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            echo "No customers or products found. Skipping order seeder.\n";
            return;
        }

        // Create sample orders for each customer
        foreach ($customers as $customer) {
            $address = ShippingAddress::where('user_id', $customer->id)->first();

            if (!$address) {
                continue;
            }

            // Order 1: Pending payment
            $this->createOrder($customer, $address, $products, [
                'status' => 'pending_payment',
                'payment_status' => 'pending',
                'created_at' => Carbon::now()->subDays(1),
            ]);

            // Order 2: In production
            $this->createOrder($customer, $address, $products, [
                'status' => 'in_production',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(3),
            ]);

            // Order 3: Delivered
            $this->createOrder($customer, $address, $products, [
                'status' => 'delivered',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(10),
            ]);
        }

        // Create additional sample orders
        $customer = $customers->first();
        $address = ShippingAddress::where('user_id', $customer->id)->first();

        if ($customer && $address) {
            // Order: File verification
            $this->createOrder($customer, $address, $products, [
                'status' => 'file_verification',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(2),
            ]);

            // Order: Shipped
            $this->createOrder($customer, $address, $products, [
                'status' => 'shipped',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subDays(5),
                'tracking_number' => 'JNE123456789',
            ]);

            // Order: Cancelled
            $this->createOrder($customer, $address, $products, [
                'status' => 'cancelled',
                'payment_status' => 'refunded',
                'created_at' => Carbon::now()->subDays(7),
                'notes' => 'Dibatalkan oleh pelanggan',
            ]);
        }

        echo "Orders seeded!\n";
    }

    /**
     * Create a sample order
     */
    private function createOrder(User $customer, ShippingAddress $address, $products, array $options = []): void
    {
        // Select random products
        $selectedProducts = $products->random(min(rand(1, 3), $products->count()));

        $orderNumber = 'PM' . date('Ymd') . strtoupper(Str::random(6));

        $subtotal = 0;
        $items = [];

        foreach ($selectedProducts as $product) {
            $quantity = [100, 250, 500][rand(0, 2)];

            // Get price from quantity tiers
            $tiers = is_string($product->quantity_tiers)
                ? json_decode($product->quantity_tiers, true)
                : $product->quantity_tiers;

            if (empty($tiers)) {
                $tiers = [['minQty' => 1, 'maxQty' => 99999, 'pricePerUnit' => $product->base_price]];
            }

            $unitPrice = $product->base_price;
            foreach ($tiers as $tier) {
                if ($quantity >= $tier['minQty'] && $quantity <= $tier['maxQty']) {
                    $unitPrice = $tier['pricePerUnit'];
                    break;
                }
            }

            $totalPrice = $unitPrice * $quantity;
            $subtotal += $totalPrice;

            // Get sizes, materials, print_sides from product
            $sizes = is_string($product->sizes) ? json_decode($product->sizes, true) : $product->sizes;
            $materials = is_string($product->materials) ? json_decode($product->materials, true) : $product->materials;
            $printSides = is_string($product->print_sides) ? json_decode($product->print_sides, true) : $product->print_sides;

            $items[] = [
                'product_id' => $product->id,
                'size_id' => $sizes[0]['id'] ?? null,
                'size_name' => $sizes[0]['name'] ?? 'Standard',
                'material_id' => $materials[0]['id'] ?? null,
                'material_name' => ($materials[0]['name'] ?? 'Standard') . ' ' . ($materials[0]['weight'] ?? ''),
                'print_side_id' => $printSides[0]['id'] ?? 'side-1',
                'print_side_name' => $printSides[0]['name'] ?? '1 Sisi',
                'finishing_ids' => [],
                'finishing_names' => [],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'uploaded_file_name' => 'design-file-' . Str::random(8) . '.pdf',
                'uploaded_file_url' => 'https://storage.printmaster.id/uploads/' . Str::random(12) . '.pdf',
                'uploaded_file_status' => 'approved',
                'status' => $options['status'] ?? 'pending_payment',
            ];
        }

        $shippingCost = [15000, 20000, 25000, 30000][rand(0, 3)];
        $discount = 0;
        $totalAmount = $subtotal + $shippingCost - $discount;

        $paymentMethods = ['bank_transfer', 'virtual_account', 'ewallet'];
        $shippingProviders = ['JNE REG', 'JNE YES', 'SiCepat REG', 'Anteraja'];

        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => $orderNumber,
            'shipping_address_id' => $address->id,
            'shipping_method' => $shippingProviders[rand(0, 3)],
            'shipping_provider' => explode(' ', $shippingProviders[rand(0, 3)])[0],
            'tracking_number' => $options['tracking_number'] ?? null,
            'payment_method' => $paymentMethods[rand(0, 2)],
            'payment_type' => $paymentMethods[rand(0, 2)],
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'status' => $options['status'] ?? 'pending_payment',
            'payment_status' => $options['payment_status'] ?? 'pending',
            'payment_deadline' => Carbon::now()->addDays(1),
            'paid_at' => ($options['payment_status'] ?? 'pending') === 'paid' ? Carbon::now() : null,
            'notes' => $options['notes'] ?? null,
            'created_at' => $options['created_at'] ?? Carbon::now(),
            'updated_at' => $options['created_at'] ?? Carbon::now(),
        ]);

        // Create order items
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'size_id' => $item['size_id'],
                'size_name' => $item['size_name'],
                'material_id' => $item['material_id'],
                'material_name' => $item['material_name'],
                'print_side_id' => $item['print_side_id'],
                'print_side_name' => $item['print_side_name'],
                'finishing_ids' => $item['finishing_ids'],
                'finishing_names' => $item['finishing_names'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['total_price'],
                'uploaded_file_name' => $item['uploaded_file_name'],
                'uploaded_file_url' => $item['uploaded_file_url'],
                'uploaded_file_status' => $item['uploaded_file_status'],
                'status' => $item['status'],
            ]);
        }
    }
}
