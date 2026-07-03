<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\TransactionController;
use Illuminate\Support\Facades\Artisan;
use App\Models\Transaction;
use App\Http\Controllers\MidtransWebhookController;

Route::get('/jalankan-symlink', function () {
    Artisan::call('storage:link');
    return 'Symlink berhasil dibuat! Silakan cek kembali foto kamu.';
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

Route::get('/ticket/{order_id}', function ($order_id) {
    $transaction = Transaction::with('event')
        ->where('order_id', $order_id)
        ->firstOrFail();

    return view('ticket', compact('transaction'));
})->name('ticket');

Route::get('/category/{id}', [HomeController::class, 'category'])
    ->name('category.filter');

Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.callback');
/*
|--------------------------------------------------------------------------
| Checkout Routes
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');

Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
/*
|--------------------------------------------------------------------------
| Admin Routes (Modifikasi Pertemuan 8)
|--------------------------------------------------------------------------
*/

// Rute fallback global Laravel jika ada sistem yang melempar ke '/login'
Route::get('/login', function () {  
    return redirect()->route('admin.login');  
})->name('login');  

// Grouping untuk URL berawalan /admin
Route::prefix('admin')->name('admin.')->group(function () {

    // Login & logout admin
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Route admin yang wajib login
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        Route::get('transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');
    });
});