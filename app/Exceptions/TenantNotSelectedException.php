<?php

namespace App\Exceptions;

use Exception;

class TenantNotSelectedException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a tenant first',
            ], 403);
        }

        return redirect()->route('tenant.select')
            ->with('warning', 'Silakan pilih kost terlebih dahulu.');
    }
}
