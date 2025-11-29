<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::with('room')
            ->when($request->room_id, function ($query, $roomId) {
                $query->where('room_id', $roomId);
            })
            ->latest()
            ->paginate(20);

        return view('assets.index', compact('assets'));
    }

    public function create(Request $request)
    {
        $rooms = Room::where('tenant_id', session('tenant_id'))->get();
        $selectedRoomId = $request->room_id;
        return view('assets.create', compact('rooms', 'selectedRoomId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'useful_life_years' => 'required|integer|min:1',
            'condition' => 'required|in:good,fair,poor,broken',
        ]);

        $validated['tenant_id'] = session('tenant_id');

        $asset = Asset::create($validated);

        if ($request->filled('room_id')) {
            return redirect()->route('rooms.show', $request->room_id)
                ->with('success', 'Aset berhasil ditambahkan ke kamar!');
        }

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil ditambahkan!');
    }

    public function show(Asset $asset)
    {
        $asset->load('room', 'maintenanceLogs');
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $rooms = Room::where('tenant_id', session('tenant_id'))->get();
        return view('assets.edit', compact('asset', 'rooms'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'room_id' => 'nullable|exists:rooms,id',
            'name' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
            'useful_life_years' => 'required|integer|min:1',
            'condition' => 'required|in:good,fair,poor,broken',
        ]);

        $asset->update($validated);
        $asset->updateCurrentValue();

        return redirect()->route('assets.show', $asset)
            ->with('success', 'Aset berhasil diupdate!');
    }

    public function destroy(Asset $asset)
    {
        $roomId = $asset->room_id;
        $asset->delete();

        if ($roomId) {
            return redirect()->route('rooms.show', $roomId)
                ->with('success', 'Aset berhasil dihapus!');
        }

        return redirect()->route('assets.index')
            ->with('success', 'Aset berhasil dihapus!');
    }

    public function downloadQrCode(Asset $asset)
    {
        if (!$asset->qr_code || !Storage::disk('public')->exists($asset->qr_code)) {
            return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
        }

        return Storage::disk('public')->download($asset->qr_code, $asset->code . '.png');
    }
}
