<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrganizerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        $user = Auth::user();

        if ($user->role !== 'organizer') {
            abort(403);
        }

        if (!$user->organizer) {
            abort(403);
        }

        if ($user->organizer->status !== 'approved') {
            abort(403);
        }

        return $next($request);
    }
}