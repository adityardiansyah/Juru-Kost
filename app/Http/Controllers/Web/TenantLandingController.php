<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantLandingController extends Controller
{
    public function show(Tenant $tenant)
    {
        // Load available rooms with their room types
        $availableRooms = $tenant->rooms()
            ->where('status', 'available')
            ->with('roomType')
            ->get();

        // Load active FAQs ordered
        $faqs = $tenant->faqs()->active()->ordered()->get();

        // Load active testimonials (limit to 6)
        $testimonials = $tenant->testimonials()->active()->latest()->limit(6)->get();

        return view('landing.tenant.show', compact('tenant', 'availableRooms', 'faqs', 'testimonials'));
    }
}
