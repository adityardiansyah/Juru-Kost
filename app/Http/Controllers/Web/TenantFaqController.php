<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TenantFaq;
use Illuminate\Http\Request;

class TenantFaqController extends Controller
{
    public function index()
    {
        $currentTenant = auth()->user()->currentTenant;

        if (!$currentTenant) {
            return redirect()->route('tenant.select')->with('error', 'Silakan pilih tenant terlebih dahulu.');
        }

        $faqs = $currentTenant->faqs()->ordered()->get();
        return view('tenant-settings.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('tenant-settings.faqs.create');
    }

    public function store(Request $request)
    {
        $currentTenant = auth()->user()->currentTenant;

        if (!$currentTenant) {
            return redirect()->route('tenant.select')->with('error', 'Silakan pilih tenant terlebih dahulu.');
        }

        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $validated['tenant_id'] = $currentTenant->id;
        $validated['order'] = TenantFaq::where('tenant_id', $currentTenant->id)->max('order') + 1;

        TenantFaq::create($validated);

        return redirect()->route('tenant-faqs.index')->with('success', 'FAQ berhasil ditambahkan');
    }

    public function edit(TenantFaq $tenantFaq)
    {
        return view('tenant-settings.faqs.edit', compact('tenantFaq'));
    }

    public function update(Request $request, TenantFaq $tenantFaq)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $tenantFaq->update($validated);

        return redirect()->route('tenant-faqs.index')->with('success', 'FAQ berhasil diupdate');
    }

    public function destroy(TenantFaq $tenantFaq)
    {
        $tenantFaq->delete();
        return redirect()->route('tenant-faqs.index')->with('success', 'FAQ berhasil dihapus');
    }

    public function reorder(Request $request)
    {
        $orders = $request->input('orders', []);

        foreach ($orders as $id => $order) {
            TenantFaq::where('id', $id)->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}
