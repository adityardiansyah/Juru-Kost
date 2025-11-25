<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = auth()->user()->tenants;

        return response()->json([
            'success' => true,
            'data' => $tenants,
        ]);
    }

    public function destroy(Resident $resident)
    {
        $resident->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resident deleted successfully',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tenants,slug',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $tenant = Tenant::create($validated);

        // Attach current user as owner
        $tenant->users()->attach(auth()->id(), ['role' => 'owner']);

        return response()->json([
            'success' => true,
            'message' => 'Tenant created successfully',
            'data' => $tenant,
        ], 201);
    }

    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        return response()->json([
            'success' => true,
            'data' => $tenant->load('users'),
        ]);
    }

    public function update(Request $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'is_active' => 'sometimes|boolean',
        ]);

        $tenant->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tenant updated successfully',
            'data' => $tenant,
        ]);
    }

    public function switchTenant(Request $request, Tenant $tenant)
    {
        // Verify user has access
        if (!auth()->user()->tenants->contains($tenant->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to this tenant',
            ], 403);
        }

        session(['tenant_id' => $tenant->id]);

        return response()->json([
            'success' => true,
            'message' => 'Tenant switched successfully',
            'data' => $tenant,
        ]);
    }
}
