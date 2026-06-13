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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

Route::get('/ticket', function () {
    return view('ticket');
})->name('ticket');

Route::get('/category/{id}', [HomeController::class, 'category'])
    ->name('category.filter');

/*
|--------------------------------------------------------------------------
| Checkout Routes
|--------------------------------------------------------------------------
*/

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])
    ->name('checkout.create');

Route::post('/checkout/{event}', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/payment/success/{order_id}', [CheckoutController::class, 'paymentSuccess'])
    ->name('payment.success');

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
    
    // 1. Rute Login & Logout (Bebas Akses tanpa Tembok Keamanan)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login'); 
    Route::post('login', [AuthController::class, 'login'])->name('login.post'); 
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');  
    
    // 2. Mengamankan Route Administrasi di balik tembok (Middleware auth & admin)
    Route::middleware(['auth', 'admin'])->group(function () {  
        
        // Mengubah rute dashboard awal agar diarahkan ke name('dashboard') sesuai modul
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');  
        
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);  
        
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');  
    });  
});