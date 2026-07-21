<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== 'admin') {
            return redirect()->route('pegawai.dashboard')->with('error', 'Akses ditolak. Anda bukan Admin.');
        }

        return $next($request);
    }
}
