<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Models\Transaction;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CheckinController;

use App\Http\Controllers\Auth\GoogleController;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\OrganizerController;
use App\Http\Controllers\Admin\CouponController;

use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\TransactionController as OrganizerTransactionController;

use App\Http\Controllers\HelpCenterController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Utility
|--------------------------------------------------------------------------
*/

Route::get('/jalankan-symlink', function () {
    Artisan::call('storage:link');
    return 'Symlink berhasil dibuat!';
});

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

Route::get('/category/{id}', [HomeController::class, 'category'])
    ->name('category.filter');

Route::view('/cara-pesan', 'cara-pesan')
    ->name('cara-pesan');

Route::get('/help-center', [HelpCenterController::class, 'index'])
    ->name('help.center');

Route::get('/tickets', [TicketController::class, 'index'])
    ->name('tickets.index');


/*
|--------------------------------------------------------------------------
| Ticket
|--------------------------------------------------------------------------
*/

Route::get('/ticket/{order_id}', function ($order_id) {

    $transaction = Transaction::with('event')
        ->where('order_id', $order_id)
        ->firstOrFail();

    return view('ticket', compact('transaction'));
})->name('ticket');

/*
|--------------------------------------------------------------------------
| Certificate
|--------------------------------------------------------------------------
*/

Route::post('/events/{event}/certificates/issue', [CertificateController::class, 'issue'])
    ->middleware('auth')
    ->name('certificates.issue');

Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])
    ->middleware('auth')
    ->name('certificates.download');

/*
|--------------------------------------------------------------------------
| Review
|--------------------------------------------------------------------------
*/

Route::post('/events/{event}/review', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/review/success', [ReviewController::class, 'success'])->name('reviews.success');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])->name('reviews.report');

/*
|--------------------------------------------------------------------------
| Checkout
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| Midtrans
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| Google Login
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

/*
|--------------------------------------------------------------------------
| User Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Login
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->middleware('guest')
    ->name('login');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->middleware('guest')
    ->name('admin.login.post');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');

/*
|--------------------------------------------------------------------------
| Super Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('logout');

        Route::resource('events', EventAdminController::class);

        Route::resource('categories', CategoryController::class);

        Route::resource('partners', PartnerController::class);

        Route::resource('coupons', CouponController::class);

        Route::get('/transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');

        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
            ->name('transactions.destroy');

        Route::resource('organizers', OrganizerController::class)
            ->only(['index', 'show']);

        Route::patch('/organizers/{organizer}/approve', [OrganizerController::class, 'approve'])
            ->name('organizers.approve');

        Route::patch('/organizers/{organizer}/reject', [OrganizerController::class, 'reject'])
            ->name('organizers.reject');
    });

/*
|--------------------------------------------------------------------------
| Check-in Scanner & Public API Routes
|--------------------------------------------------------------------------
*/

Route::get('/checkin', [CheckinController::class, 'show'])->name('checkin.show');
Route::post('/api/checkin', [CheckinController::class, 'scan'])->name('api.checkin');
Route::post('/api/coupon/apply', [CheckoutController::class, 'applyCoupon'])->name('api.coupon.apply');

/*
|--------------------------------------------------------------------------
| Organizer
|--------------------------------------------------------------------------
*/

Route::prefix('organizer')
    ->name('organizer.')
    ->middleware(['auth', 'organizer'])
    ->group(function () {

        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('events', OrganizerEventController::class);

        Route::resource('transaction', OrganizerTransactionController::class)
            ->only(['index', 'show']);

        Route::resource('transaction', TransactionController::class);
    });
