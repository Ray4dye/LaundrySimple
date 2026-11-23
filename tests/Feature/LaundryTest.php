<?php

namespace Tests\Feature;

use App\Models\LaundryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaundryTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_laundry_catalog(): void
    {
        LaundryItem::create([
            'name' => 'Cuci Baju Reguler',
            'category' => 'Cuci',
            'price' => 25000,
            'description' => 'Layanan cuci baju reguler.',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Cuci Baju Reguler');
    }

    public function test_customer_can_checkout_order(): void
    {
        $item = LaundryItem::create([
            'name' => 'Setrika Baju Reguler',
            'category' => 'Setrika',
            'price' => 5000,
            'description' => 'Layanan setrika baju reguler.',
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => 'Andi Wijaya',
            'queue_number' => 7,
            'items' => [
                $item->id => 3,
            ],
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Andi Wijaya',
            'queue_number' => '07',
            'total_price' => 15000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_details', [
            'laundry_item_id' => $item->id,
            'quantity' => 3,
            'price' => 5000,
            'subtotal' => 15000,
        ]);
    }

    public function test_admin_can_access_dashboard_and_manage_laundry_items(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);

        $responseItems = $this->actingAs($admin)->get('/admin/laundry_items');
        $responseItems->assertStatus(200);

        $postItem = $this->actingAs($admin)->post('/admin/laundry_items', [
            'name' => 'Cuci Selimut',
            'category' => 'Cuci',
            'price' => 35000,
            'description' => 'Cuci selimut tebal',
        ]);
        $postItem->assertRedirect(route('laundry_items.index'));

        $this->assertDatabaseHas('laundry_items', [
            'name' => 'Cuci Selimut',
            'price' => 35000,
        ]);
    }

    public function test_regular_user_cannot_access_admin_dashboard_or_laundry_items(): void
    {
        $user = User::create([
            'name' => 'User Regular',
            'email' => 'user@test.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $responseDashboard = $this->actingAs($user)->get('/dashboard');
        $responseDashboard->assertStatus(403);

        $responseItems = $this->actingAs($user)->get('/admin/laundry_items');
        $responseItems->assertStatus(403);

        $responseUsers = $this->actingAs($user)->get('/admin/users');
        $responseUsers->assertStatus(403);
    }

    public function test_admin_can_view_order_reports(): void
    {
        $admin = User::create([
            'name' => 'Admin Report',
            'email' => 'adminreport@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/reports/layanans');
        $response->assertStatus(200);
        $response->assertSee('Rekap Layanan & Laporan Omzet');
    }
}
