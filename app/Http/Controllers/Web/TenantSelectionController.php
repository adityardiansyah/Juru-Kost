<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantSelectionController extends Controller
{
    public function index()
    {
        $tenants = auth()->user()->tenants()->with('users')->get();

        // If user only has one tenant, auto-select it
        if ($tenants->count() === 1) {
            session(['tenant_id' => $tenants->first()->id]);
            return redirect()->route('dashboard');
        }

        return view('tenant.select', compact('tenants'));
    }

    /**
     * Switch to selected tenant
     */
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);

        // Verify user has access to this tenant
        $tenant = auth()->user()->tenants()->find($validated['tenant_id']);

        if (!$tenant) {
            return back()->with('error', 'Anda tidak memiliki akses ke kost ini.');
        }

        // Set tenant in session
        session(['tenant_id' => $tenant->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil beralih ke ' . $tenant->name);
    }

    /**
     * Create new tenant
     */
    public function create()
    {
        return view('tenant.create');
    }

    /**
     * Store new tenant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants,slug|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Create tenant
        $tenant = Tenant::create($validated);

        // Attach current user as owner
        $tenant->users()->attach(auth()->id(), ['role' => 'owner']);

        // Auto-select the new tenant
        session(['tenant_id' => $tenant->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Kost "' . $tenant->name . '" berhasil dibuat!');
    }

    /**
     * Get user's role in current tenant
     */
    public function getRole()
    {
        $tenantId = session('tenant_id');

        if (!$tenantId) {
            return response()->json(['role' => null]);
        }

        $role = auth()->user()->tenants()
            ->where('tenants.id', $tenantId)
            ->first()
            ->pivot
            ->role ?? null;

        return response()->json(['role' => $role]);
    }
}
