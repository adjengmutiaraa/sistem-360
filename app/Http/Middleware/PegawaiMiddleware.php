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

        if ($request->user()->hasAnyRole(['Super Admin', 'Admin BKPSDM']) && ! $request->user()->hasRole('Pegawai')) {
            // Technically super admin shouldn't be here unless they also have a pegawai role, but for now redirect
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
