<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TenantTestimonial;
use Illuminate\Http\Request;

class TenantTestimonialController extends Controller
{
    public function index()
    {
        $currentTenant = auth()->user()->currentTenant;

        if (!$currentTenant) {
            return redirect()->route('tenant.select')->with('error', 'Silakan pilih tenant terlebih dahulu.');
        }

        $testimonials = $currentTenant->testimonials()->latest()->get();
        return view('tenant-settings.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('tenant-settings.testimonials.create');
    }

    public function store(Request $request)
    {
        $currentTenant = auth()->user()->currentTenant;

        if (!$currentTenant) {
            return redirect()->route('tenant.select')->with('error', 'Silakan pilih tenant terlebih dahulu.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['tenant_id'] = $currentTenant->id;

        TenantTestimonial::create($validated);

        return redirect()->route('tenant-testimonials.index')->with('success', 'Testimoni berhasil ditambahkan');
    }

    public function edit(TenantTestimonial $tenantTestimonial)
    {
        return view('tenant-settings.testimonials.edit', compact('tenantTestimonial'));
    }

    public function update(Request $request, TenantTestimonial $tenantTestimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $tenantTestimonial->update($validated);

        return redirect()->route('tenant-testimonials.index')->with('success', 'Testimoni berhasil diupdate');
    }

    public function destroy(TenantTestimonial $tenantTestimonial)
    {
        $tenantTestimonial->delete();
        return redirect()->route('tenant-testimonials.index')->with('success', 'Testimoni berhasil dihapus');
    }
}
