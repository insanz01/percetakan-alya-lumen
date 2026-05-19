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
        $customers = User::where('peran', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            echo "No customers or products found. Skipping order seeder.\n";
            return;
        }

        // Create sample orders for each customer
        foreach ($customers as $customer) {
            $address = ShippingAddress::where('pengguna_id', $customer->id)->first();

            if (!$address) {
                continue;
            }

            // Order 1: Pending payment
            $this->createOrder($customer, $address, $products, [
                'status' => 'pending_payment',
                'status_bayar' => 'pending',
                'created_at' => Carbon::now()->subDays(1),
            ]);

            // Order 2: In production
            $this->createOrder($customer, $address, $products, [
                'status' => 'in_production',
                'status_bayar' => 'paid',
                'created_at' => Carbon::now()->subDays(3),
            ]);

            // Order 3: Delivered
            $this->createOrder($customer, $address, $products, [
                'status' => 'delivered',
                'status_bayar' => 'paid',
                'created_at' => Carbon::now()->subDays(10),
            ]);
        }

        // Create additional sample orders
        $customer = $customers->first();
        $address = ShippingAddress::where('pengguna_id', $customer->id)->first();

        if ($customer && $address) {
            // Order: File verification
            $this->createOrder($customer, $address, $products, [
                'status' => 'file_verification',
                'status_bayar' => 'paid',
                'created_at' => Carbon::now()->subDays(2),
            ]);

            // Order: Shipped
            $this->createOrder($customer, $address, $products, [
                'status' => 'shipped',
                'status_bayar' => 'paid',
                'created_at' => Carbon::now()->subDays(5),
                'nomor_resi' => 'JNE123456789',
            ]);

            // Order: Cancelled
            $this->createOrder($customer, $address, $products, [
                'status' => 'cancelled',
                'status_bayar' => 'refunded',
                'created_at' => Carbon::now()->subDays(7),
                'catatan' => 'Dibatalkan oleh pelanggan',
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
            $tiers = is_string($product->tier_jumlah)
                ? json_decode($product->tier_jumlah, true)
                : $product->tier_jumlah;

            if (empty($tiers)) {
                $tiers = [['minQty' => 1, 'maxQty' => 99999, 'pricePerUnit' => $product->harga_dasar]];
            }

            $unitPrice = $product->harga_dasar;
            foreach ($tiers as $tier) {
                if ($quantity >= $tier['minQty'] && $quantity <= $tier['maxQty']) {
                    $unitPrice = $tier['pricePerUnit'];
                    break;
                }
            }

            $totalPrice = $unitPrice * $quantity;
            $subtotal += $totalPrice;

            // Get sizes, materials, print_sides from product
            $sizes = is_string($product->ukuran) ? json_decode($product->ukuran, true) : $product->ukuran;
            $materials = is_string($product->bahan) ? json_decode($product->bahan, true) : $product->bahan;
            $printSides = is_string($product->sisi_cetak) ? json_decode($product->sisi_cetak, true) : $product->sisi_cetak;

            $items[] = [
                'produk_id' => $product->id,
                'ukuran_id' => $sizes[0]['id'] ?? null,
                'nama_ukuran' => $sizes[0]['name'] ?? 'Standard',
                'bahan_id' => $materials[0]['id'] ?? null,
                'nama_bahan' => ($materials[0]['name'] ?? 'Standard') . ' ' . ($materials[0]['weight'] ?? ''),
                'sisi_cetak_id' => $printSides[0]['id'] ?? 'side-1',
                'nama_sisi_cetak' => $printSides[0]['name'] ?? '1 Sisi',
                'finishing_ids' => [],
                'nama_finishing' => [],
                'jumlah' => $quantity,
                'harga_satuan' => $unitPrice,
                'harga_total' => $totalPrice,
                'nama_file_diunggah' => 'design-file-' . Str::random(8) . '.pdf',
                'url_file_diunggah' => 'https://storage.printmaster.id/uploads/' . Str::random(12) . '.pdf',
                'status_file_diunggah' => 'approved',
                'status' => $options['status'] ?? 'pending_payment',
            ];
        }

        $shippingCost = [15000, 20000, 25000, 30000][rand(0, 3)];
        $discount = 0;
        $totalAmount = $subtotal + $shippingCost - $discount;

        $paymentMethods = ['bank_transfer', 'virtual_account', 'ewallet'];
        $shippingProviders = ['JNE REG', 'JNE YES', 'SiCepat REG', 'Anteraja'];

        $order = Order::create([
            'pengguna_id' => $customer->id,
            'nomor_pesanan' => $orderNumber,
            'alamat_pengiriman_id' => $address->id,
            'metode_pengiriman' => $shippingProviders[rand(0, 3)],
            'kurir' => explode(' ', $shippingProviders[rand(0, 3)])[0],
            'nomor_resi' => $options['nomor_resi'] ?? null,
            'metode_pembayaran' => $paymentMethods[rand(0, 2)],
            'tipe_pembayaran' => $paymentMethods[rand(0, 2)],
            'subtotal' => $subtotal,
            'biaya_kirim' => $shippingCost,
            'diskon' => $discount,
            'total' => $totalAmount,
            'status' => $options['status'] ?? 'pending_payment',
            'status_bayar' => $options['status_bayar'] ?? 'pending',
            'batas_bayar' => Carbon::now()->addDays(1),
            'dibayar_pada' => ($options['status_bayar'] ?? 'pending') === 'paid' ? Carbon::now() : null,
            'catatan' => $options['catatan'] ?? null,
            'created_at' => $options['created_at'] ?? Carbon::now(),
            'updated_at' => $options['created_at'] ?? Carbon::now(),
        ]);

        // Create order items
        foreach ($items as $item) {
            OrderItem::create([
                'pesanan_id' => $order->id,
                'produk_id' => $item['produk_id'],
                'ukuran_id' => $item['ukuran_id'],
                'nama_ukuran' => $item['nama_ukuran'],
                'bahan_id' => $item['bahan_id'],
                'nama_bahan' => $item['nama_bahan'],
                'sisi_cetak_id' => $item['sisi_cetak_id'],
                'nama_sisi_cetak' => $item['nama_sisi_cetak'],
                'finishing_ids' => $item['finishing_ids'],
                'nama_finishing' => $item['nama_finishing'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'harga_total' => $item['harga_total'],
                'nama_file_diunggah' => $item['nama_file_diunggah'],
                'url_file_diunggah' => $item['url_file_diunggah'],
                'status_file_diunggah' => $item['status_file_diunggah'],
                'status' => $item['status'],
            ]);
        }
    }
}
