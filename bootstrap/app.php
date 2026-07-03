<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class, // Sesuaikan dengan nama class middleware admin Anda
        ]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Mendaftarkan alias middleware admin sesuai modul Pertemuan 8 (saya ganti jadi modul pertemuan 12)
        $middleware->validateCsrfTokens(except: [
        '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();