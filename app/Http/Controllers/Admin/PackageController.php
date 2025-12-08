<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    /**
     * Display a listing of packages
     */
    public function index()
    {
        $packages = Package::orderBy('sort_order')->get();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'type' => 'required|in:lifetime,monthly,yearly',
            'is_active' => 'boolean',
            'max_tenants' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'bonus_features' => 'nullable|array',
            'bonus_features.*' => 'string',
            'sort_order' => 'nullable|integer',
        ]);

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Calculate discount percentage
        if (isset($validated['original_price']) && $validated['original_price'] > 0) {
            $validated['discount_percentage'] = round(
                (($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100
            );
        } else {
            $validated['discount_percentage'] = 0;
        }

        // Set is_active default
        $validated['is_active'] = $request->has('is_active');

        Package::create($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified package
     */
    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'type' => 'required|in:lifetime,monthly,yearly',
            'is_active' => 'boolean',
            'max_tenants' => 'nullable|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'bonus_features' => 'nullable|array',
            'bonus_features.*' => 'string',
            'sort_order' => 'nullable|integer',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $package->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Calculate discount percentage
        if (isset($validated['original_price']) && $validated['original_price'] > 0) {
            $validated['discount_percentage'] = round(
                (($validated['original_price'] - $validated['price']) / $validated['original_price']) * 100
            );
        } else {
            $validated['discount_percentage'] = 0;
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active');

        $package->update($validated);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package berhasil diupdate!');
    }

    /**
     * Remove the specified package
     */
    public function destroy(Package $package)
    {
        // Check if package has orders
        if ($package->orders()->count() > 0) {
            return redirect()->route('admin.packages.index')
                ->with('error', 'Package tidak dapat dihapus karena sudah memiliki order!');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'Package berhasil dihapus!');
    }

    /**
     * Toggle package active status
     */
    public function toggleStatus(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);

        $status = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.packages.index')
            ->with('success', "Package berhasil {$status}!");
    }
}
