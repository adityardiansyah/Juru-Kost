<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('tenant_id')) {
            return redirect()->route('tenant.select')
                ->with('warning', 'Silakan pilih kost terlebih dahulu.');
        }

        return $next($request);
    }
}
