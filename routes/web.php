<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PartnerController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/detail-event', function () {
    return view('event-detail');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/ticket', function () {
    return view('ticket');
});
Route::get('/category/{id}', [HomeController::class, 'category'])
    ->name('category.filter');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    Route::resource('events', EventAdminController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('partners', PartnerController::class);
});
