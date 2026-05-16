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
        if (! Auth::check()) {
            return redirect()
                ->route('login')
                ->with('success', 'Silakan login sebagai admin terlebih dahulu.');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()
                ->route('home')
                ->withErrors(['access' => 'Akses admin hanya tersedia untuk pengguna dengan role admin.']);
        }

        return $next($request);
    }
}
