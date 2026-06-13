<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login DAN memiliki role admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Jika bukan admin / belum login, paksa logout dan tendang ke halaman login
        Auth::logout();
        return redirect()->route('admin.login')->with('error', 'Akses ditolak! Anda harus login sebagai Admin Penyelenggara.');
    }
}