<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Resident;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function show(Tenant $tenant)
    {
        // Verify user has access
        if (!auth()->user()->tenants->contains($tenant->id)) {
            abort(403, 'Unauthorized access to this tenant');
        }

        $tenant->load('users');

        // Get statistics
        $stats = [
            'total_rooms' => Room::where('tenant_id', $tenant->id)->count(),
            'active_residents' => Resident::where('tenant_id', $tenant->id)->where('status', 'active')->count(),
            'occupancy_rate' => $this->calculateOccupancyRate($tenant->id),
            'unpaid_bills' => Bill::where('tenant_id', $tenant->id)->whereIn('status', ['unpaid', 'overdue'])->count(),
        ];

        return view('tenant.show', compact('tenant', 'stats'));
    }

    public function edit(Tenant $tenant)
    {
        // Verify user is owner
        $isOwner = auth()->user()->tenants()
            ->wherePivot('tenant_id', $tenant->id)
            ->wherePivot('role', 'owner')
            ->exists();

        if (!$isOwner) {
            abort(403, 'Only owner can edit tenant');
        }

        return view('tenant.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        // Verify user is owner
        $isOwner = auth()->user()->tenants()
            ->wherePivot('tenant_id', $tenant->id)
            ->wherePivot('role', 'owner')
            ->exists();

        if (!$isOwner) {
            abort(403, 'Only owner can update tenant');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'is_active' => 'sometimes|boolean',
        ]);

        $tenant->update($validated);

        return redirect()->route('tenant.show', $tenant)
            ->with('success', 'Data kost berhasil diupdate!');
    }

    protected function calculateOccupancyRate($tenantId)
    {
        $totalRooms = Room::where('tenant_id', $tenantId)->count();
        $occupiedRooms = Room::where('tenant_id', $tenantId)->where('status', 'occupied')->count();

        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
    }
}
