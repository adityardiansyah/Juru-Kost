<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $requests = MaintenanceRequest::with('resident', 'room')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->priority, function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'room_id' => 'nullable|exists:rooms,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $maintenanceRequest = MaintenanceRequest::create([
            'tenant_id' => session('tenant_id'),
            'resident_id' => $validated['resident_id'],
            'room_id' => $validated['room_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        // Send WhatsApp notification
        \App\Jobs\SendMaintenanceNotification::dispatch(
            $maintenanceRequest->resident->phone,
            $maintenanceRequest->ticket_number,
            'pending'
        );

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request created successfully',
            'data' => $maintenanceRequest->load('resident', 'room'),
        ], 201);
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        return response()->json([
            'success' => true,
            'data' => $maintenanceRequest->load('resident', 'room'),
        ]);
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_cost' => 'nullable|numeric|min:0',
        ]);

        $maintenanceRequest->update([
            'status' => $validated['status'],
            'estimated_cost' => $validated['estimated_cost'] ?? $maintenanceRequest->estimated_cost,
        ]);

        if ($validated['status'] === 'completed') {
            $maintenanceRequest->markAsCompleted();
        }

        // Send WhatsApp notification
        \App\Jobs\SendMaintenanceNotification::dispatch(
            $maintenanceRequest->resident->phone,
            $maintenanceRequest->ticket_number,
            $validated['status']
        );

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $maintenanceRequest,
        ]);
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request deleted successfully',
        ]);
    }
}
