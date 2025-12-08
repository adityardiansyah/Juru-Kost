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

        // Add role information to each tenant
        $tenants->each(function ($tenant) {
            $roleId = $tenant->pivot->role_id;
            $tenant->role = \App\Models\Role::find($roleId);
        });

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
    /**
     * Switch to selected tenant
     */
    public function switch(Request $request, $tenantId = null)
    {
        // If tenant ID is passed in route (GET/POST from dropdown), use it
        // Otherwise look for it in request body (POST from form)
        $id = $tenantId ?? $request->input('tenant_id');

        if (!$id) {
            return back()->with('error', 'Tenant tidak valid.');
        }

        // Verify user has access to this tenant
        $tenant = auth()->user()->tenants()->find($id);

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
        $ownerRole = \App\Models\Role::where('name', 'owner')->first();
        $tenant->users()->attach(auth()->id(), ['role_id' => $ownerRole->id]);

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

        $tenant = auth()->user()->tenants()
            ->where('tenants.id', $tenantId)
            ->first();

        if (!$tenant) {
            return response()->json(['role' => null]);
        }

        $role = \App\Models\Role::find($tenant->pivot->role_id);

        return response()->json(['role' => $role ? $role->name : null]);
    }
}
