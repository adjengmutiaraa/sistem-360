<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PegawaiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->role !== 'pegawai') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak. Anda adalah Admin.');
        }

        return $next($request);
    }
}
