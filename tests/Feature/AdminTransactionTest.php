<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_transactions_with_filter_and_delete(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $category = Category::create(['name' => 'Concert', 'slug' => 'concert']);
        $event = Event::create([
            'title' => 'Amikom Fest 2026',
            'category_id' => $category->id,
            'date' => now()->addDays(5),
            'location' => 'Yogyakarta',
            'price' => 100000,
            'stock' => 50,
        ]);

        $trx1 = Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'TRX-FILTER-001',
            'customer_name' => 'Andi Wijaya',
            'customer_email' => 'andi@test.com',
            'customer_phone' => '081234567890',
            'total_price' => 105000,
            'status' => 'settlement',
        ]);

        $trx2 = Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'TRX-FILTER-002',
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@test.com',
            'customer_phone' => '081234567891',
            'total_price' => 105000,
            'status' => 'pending',
        ]);

        // 1. Admin accesses index — all transactions visible
        $response = $this->actingAs($admin)->get(route('admin.transactions.index'));
        $response->assertStatus(200);
        $response->assertSee($trx1->order_id);
        $response->assertSee($trx2->order_id);

        // 2. Admin filters by status=pending — only trx2 visible
        $responseFiltered = $this->actingAs($admin)->get(route('admin.transactions.index', ['status' => 'pending']));
        $responseFiltered->assertStatus(200);
        $responseFiltered->assertSee($trx2->order_id);

        // 3. Admin deletes trx2
        $responseDelete = $this->actingAs($admin)->delete(route('admin.transactions.destroy', $trx2->id));
        $responseDelete->assertRedirect();

        $this->assertDatabaseMissing('transactions', ['id' => $trx2->id]);
    }
}
