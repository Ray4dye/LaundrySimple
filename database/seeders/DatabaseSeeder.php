<?php

namespace Database\Seeders;

use App\Models\LaundryItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin Default
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Laundry',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Buat User Customer Contoh
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );

        // 3. Panggil Seeder Layanan Laundry
        $this->call([
            LaundryItemSeeder::class,
        ]);

        // 4. Buat Contoh Pesanan Masuk (Dummy Data untuk Dashboard dan Laporan)
        $cuciReguler = LaundryItem::where('name', 'Cuci Baju Reguler')->first();
        $setrikaReguler = LaundryItem::where('name', 'Setrika Baju Reguler')->first();
        $paket = LaundryItem::where('name', 'Paket Cuci dan Setrika')->first();

        if ($cuciReguler && $setrikaReguler && $paket) {
            // Pesanan 1 - Selesai
            $order1 = Order::create([
                'customer_name' => 'Siti Rahma',
                'queue_number' => '01',
                'total_price' => ($cuciReguler->price * 2) + ($setrikaReguler->price * 3),
                'status' => 'completed',
                'created_at' => now()->subDays(1),
            ]);

            OrderDetail::create([
                'order_id' => $order1->id,
                'laundry_item_id' => $cuciReguler->id,
                'quantity' => 2,
                'price' => $cuciReguler->price,
                'subtotal' => $cuciReguler->price * 2,
                'created_at' => now()->subDays(1),
            ]);

            OrderDetail::create([
                'order_id' => $order1->id,
                'laundry_item_id' => $setrikaReguler->id,
                'quantity' => 3,
                'price' => $setrikaReguler->price,
                'subtotal' => $setrikaReguler->price * 3,
                'created_at' => now()->subDays(1),
            ]);

            // Pesanan 2 - Pending
            $order2 = Order::create([
                'customer_name' => 'Ahmad Fauzi',
                'queue_number' => '02',
                'total_price' => $paket->price * 3,
                'status' => 'pending',
                'created_at' => now(),
            ]);

            OrderDetail::create([
                'order_id' => $order2->id,
                'laundry_item_id' => $paket->id,
                'quantity' => 3,
                'price' => $paket->price,
                'subtotal' => $paket->price * 3,
                'created_at' => now(),
            ]);
        }
    }
}
