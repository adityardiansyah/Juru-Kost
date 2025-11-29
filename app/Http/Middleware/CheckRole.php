<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roleOrPermission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($user->is_superuser) {
            return $next($request);
        }

        // Check if it's a role check
        if ($user->hasRole($roleOrPermission)) {
            return $next($request);
        }

        // Check if it's a permission check
        if ($user->hasPermission($roleOrPermission)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
