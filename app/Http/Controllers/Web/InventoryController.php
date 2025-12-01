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
            ->when($request->low_stock, function ($query) {
                $query->whereColumn('quantity', '<=', 'min_stock');
            })
            ->orderBy('name')
            ->paginate(20);

        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventories.create');
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

        return redirect()->route('inventories.index')
            ->with('success', 'Inventori berhasil ditambahkan!');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load(['logs' => function ($query) {
            $query->with('user')->orderBy('created_at', 'desc');
        }]);

        return view('inventories.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_stock' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventories.show', $inventory)
            ->with('success', 'Inventori berhasil diupdate!');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventories.index')
            ->with('success', 'Inventori berhasil dihapus!');
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

        return redirect()->route('inventories.show', $inventory)
            ->with('success', 'Stok berhasil ditambahkan!');
    }

    public function stockOut(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validated['quantity'] > $inventory->quantity) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi!');
        }

        $inventory->reduceStock(
            $validated['quantity'],
            $validated['notes'] ?? null,
            auth()->id()
        );

        return redirect()->route('inventories.show', $inventory)
            ->with('success', 'Stok berhasil dikurangi!');
    }
}
