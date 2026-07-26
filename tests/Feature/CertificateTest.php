<?php

namespace Tests\Feature;

use App\Jobs\GenerateAndSendCertificate;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_issue_certificate_via_route(): void
    {
        Queue::fake();

        $user = User::create([
            'name' => 'Participant Test',
            'email' => 'participant@test.com',
            'password' => bcrypt('password'),
        ]);

        $category = Category::create(['name' => 'Seminar', 'slug' => 'seminar']);
        $event = Event::create([
            'title' => 'Webinar Laravel Best Practice',
            'category_id' => $category->id,
            'date' => now()->subDays(1),
            'location' => 'Online',
            'price' => 0,
            'stock' => 100,
        ]);

        $response = $this->actingAs($user)->post(route('certificates.issue', $event->id), [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect();
        Queue::assertPushed(GenerateAndSendCertificate::class);
    }

    public function test_artisan_command_dispatches_jobs_for_ended_events(): void
    {
        Queue::fake();

        $user = User::create([
            'name' => 'Participant Test',
            'email' => 'participant2@test.com',
            'password' => bcrypt('password'),
        ]);

        $category = Category::create(['name' => 'Workshop', 'slug' => 'workshop']);
        $event = Event::create([
            'title' => 'Workshop UI/UX Design',
            'category_id' => $category->id,
            'date' => now()->subHours(2),
            'location' => 'Online',
            'price' => 50000,
            'stock' => 50,
        ]);

        Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'ORDER-CERT-123',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'total_price' => 50000,
            'status' => 'settlement',
        ]);

        $this->artisan('certificates:generate')
            ->assertExitCode(0);

        Queue::assertPushed(GenerateAndSendCertificate::class);
    }
}
