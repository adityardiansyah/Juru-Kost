<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada tenant_id di session
        if ($request->session()->has('tenant_id')) {
            $tenantId = $request->session()->get('tenant_id');

            // Verify user has access to this tenant
            if (auth()->check()) {
                $hasAccess = auth()->user()
                    ->tenants()
                    ->where('tenants.id', $tenantId)
                    ->exists();

                if (!$hasAccess) {
                    $request->session()->forget('tenant_id');
                    return redirect()->route('tenant.select')
                        ->with('error', 'Anda tidak memiliki akses ke kost ini.');
                }
            }
        }

        return $next($request);
    }
}
