<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/profil', function () {
//     return view('profil');
// });

// Route::get('/katalog', function () {
//     return view('katalog');
// });

// Route::get('/bantuan', function () {
//     return view('bantuan');
// });

// Route::get('/contact', function () {
//     return view('contact');
// });

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/event', function () {
    return view('admin.event');
});

Route::get('/transaksi', function () {
    return view('admin.transaksi');
});

Route::get('/detail-event', function () {
    return view('event-detail');
});

Route::get('/checkout', function () {
    return view('checkout');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog', function () {
    return view('katalog');
});
