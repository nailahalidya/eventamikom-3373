<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_access_dashboard_with_growth_metrics(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $category = \App\Models\Category::create([
            'name' => 'Workshop',
            'slug' => 'workshop',
        ]);

        Event::create([
            'category_id' => $category->id,
            'title' => 'Workshop UI/UX',
            'date' => now()->addDays(5),
            'location' => 'Amikom',
            'price' => 25000,
            'stock' => 50,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.index');
        $response->assertViewHasAll([
            'totalUsers',
            'currentMonthUsers',
            'userGrowthPercentage',
            'months',
            'userGrowthData',
            'eventGrowthData',
            'totalEvents',
        ]);
        $response->assertSee('Pertumbuhan Pengguna');
        $response->assertSee('Pertumbuhan Event');
        $response->assertSee('userGrowthChart');
        $response->assertSee('eventGrowthChart');
    }
}
