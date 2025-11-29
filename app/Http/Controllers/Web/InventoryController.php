<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $inventories = Inventory::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        })
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $inventories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $inventory = Inventory::create([
            'tenant_id' => session('tenant_id'),
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'min_stock' => $validated['min_stock'],
            'unit' => $validated['unit'],
            'unit_price' => $validated['unit_price'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inventory created successfully',
            'data' => $inventory,
        ], 201);
    }

    public function show(Inventory $inventory)
    {
        return response()->json([
            'success' => true,
            'data' => $inventory->load('logs.user'),
        ]);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'min_stock' => 'sometimes|required|integer|min:0',
            'unit' => 'sometimes|required|string|max:50',
            'unit_price' => 'sometimes|required|numeric|min:0',
        ]);

        $inventory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Inventory updated successfully',
            'data' => $inventory,
        ]);
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventory deleted successfully',
        ]);
    }

    public function stockIn(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $inventory->addStock(
            $validated['quantity'],
            $validated['notes'] ?? null,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully',
            'data' => $inventory->fresh(),
        ]);
    }

    public function stockOut(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validated['quantity'] > $inventory->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock',
            ], 422);
        }

        $inventory->reduceStock(
            $validated['quantity'],
            $validated['notes'] ?? null,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Stock reduced successfully',
            'data' => $inventory->fresh(),
        ]);
    }

    public function lowStockAlert()
    {
        $lowStock = Inventory::where('tenant_id', session('tenant_id'))
            ->whereColumn('quantity', '<=', 'min_stock')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $lowStock,
            'count' => $lowStock->count(),
        ]);
    }
}
