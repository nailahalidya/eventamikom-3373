<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $event;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        $this->event = Event::create([
            'category_id' => $this->category->id,
            'title' => 'Workshop AI Amikom',
            'description' => 'Belajar AI dasar',
            'date' => now()->addDays(5),
            'location' => 'Ruang Cinema',
            'price' => 100000,
            'early_bird_price' => 50000,
            'early_bird_until' => now()->addDays(2),
            'stock' => 50,
        ]);
    }

    /** @test */
    public function free_event_bypasses_midtrans()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $freeEvent = Event::create([
            'category_id' => $this->category->id,
            'title' => 'Seminar Gratis Kampus',
            'date' => now()->addDays(3),
            'location' => 'Aula Utama',
            'price' => 0,
            'stock' => 100,
        ]);

        $response = $this->post(route('checkout.store', $freeEvent->id), [
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@gmail.com',
            'customer_phone' => '08123456789',
        ]);

        $response->assertRedirect();
        
        $transaction = Transaction::where('customer_email', 'budi@gmail.com')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('settlement', strtolower($transaction->status));
        $this->assertEquals(0, $transaction->total_price);
        $this->assertNotNull($transaction->qr_token);

        // Stock decreased
        $this->assertEquals(99, $freeEvent->fresh()->stock);

        // Assert Mail Sent
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\TicketMail::class, function ($mail) {
            return $mail->hasTo('budi@gmail.com');
        });
    }

    /** @test */
    public function coupon_can_be_applied_and_gives_discount()
    {
        $coupon = Coupon::create([
            'code' => 'PROMO50',
            'type' => 'percent',
            'discount_amount' => 50,
            'is_active' => true,
        ]);

        // API Test
        $responseApi = $this->postJson(route('api.coupon.apply'), [
            'code' => 'PROMO50',
            'event_id' => $this->event->id,
        ]);

        $responseApi->assertStatus(200);
        $responseApi->assertJsonPath('success', true);
        $responseApi->assertJsonPath('discount', 25000); // 50% of 50000 Early Bird price

        // Simulate checkout with applied coupon and pending transaction.
        $transaction = Transaction::create([
            'event_id' => $this->event->id,
            'order_id' => 'TRX-COUPON-001',
            'customer_name' => 'Siti Nurhaliza',
            'customer_email' => 'siti@gmail.com',
            'customer_phone' => '08987654321',
            'total_price' => 30000,
            'status' => 'pending',
            'snap_token' => null,
            'qr_token' => 'QR-COUPON-001',
            'expires_at' => now()->addMinutes(15),
            'coupon_id' => $coupon->id,
            'coupon_code' => 'PROMO50',
        ]);

        $this->assertEquals('pending', strtolower($transaction->status));
        $this->assertEquals(0, $coupon->fresh()->used_count);

        putenv('MIDTRANS_SERVER_KEY=test');
        $signature = hash('sha512', $transaction->order_id . '200' . $transaction->total_price . 'test');

        $callbackResponse = $this->postJson(route('midtrans.callback'), [
            'order_id' => $transaction->order_id,
            'transaction_status' => 'settlement',
            'status_code' => '200',
            'gross_amount' => $transaction->total_price,
            'signature_key' => $signature,
        ]);

        $callbackResponse->assertStatus(200);
        $this->assertEquals(1, $coupon->fresh()->used_count);
        $this->assertEquals('settlement', strtolower($transaction->fresh()->status));
    }

    /** @test */
    public function checkin_scanner_validates_tickets_and_prevents_double_entry()
    {
        $trx = Transaction::create([
            'event_id' => $this->event->id,
            'order_id' => 'TRX-TEST-001',
            'customer_name' => 'Ahmad Dani',
            'customer_email' => 'ahmad@gmail.com',
            'customer_phone' => '081111111',
            'total_price' => 50000,
            'status' => 'settlement',
            'qr_token' => 'QR-TOKEN-123',
        ]);

        // 1st Scan — Success
        $res1 = $this->postJson(route('api.checkin'), ['code' => 'QR-TOKEN-123']);
        $res1->assertStatus(200);
        $res1->assertJsonPath('status', 'SUCCESS');

        $this->assertNotNull($trx->fresh()->checked_in_at);
        $this->assertEquals('used', strtolower($trx->fresh()->status));

        // 2nd Scan — Double Entry Prevention (409 Conflict)
        $res2 = $this->postJson(route('api.checkin'), ['code' => 'QR-TOKEN-123']);
        $res2->assertStatus(409);
        $res2->assertJsonPath('status', 'ALREADY_USED');
    }

    /** @test */
    public function dynamic_pricing_calculates_active_tier_correctly()
    {
        // Currently early_bird_until is in future, so price is 50000
        $this->assertEquals(50000, $this->event->current_price);
        $this->assertEquals('Early Bird', $this->event->active_tier_name);
    }

    /** @test */
    public function abandoned_cart_recovery_command_sends_wa_reminder()
    {
        $pendingTrx = Transaction::create([
            'event_id' => $this->event->id,
            'order_id' => 'TRX-ABANDONED-001',
            'customer_name' => 'Doni Suparno',
            'customer_email' => 'doni@gmail.com',
            'customer_phone' => '08123444555',
            'total_price' => 50000,
            'status' => 'pending',
            'expires_at' => now()->addMinutes(10),
            'wa_reminder_sent_at' => null,
        ]);
        $pendingTrx->created_at = now()->subMinutes(5);
        $pendingTrx->save();

        $this->artisan('cart:recover-reminders')
            ->assertExitCode(0);

        $this->assertNotNull($pendingTrx->fresh()->wa_reminder_sent_at);
    }
}
