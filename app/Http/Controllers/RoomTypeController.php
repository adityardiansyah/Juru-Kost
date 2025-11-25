<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::withCount('rooms')->get();

        return view('room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name',
            'description' => 'nullable|string',
        ]);

        RoomType::create($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Tipe kamar berhasil ditambahkan!');
    }

    public function edit(RoomType $roomType)
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name,' . $roomType->id,
            'description' => 'nullable|string',
        ]);

        $roomType->update($validated);

        return redirect()->route('room-types.index')
            ->with('success', 'Tipe kamar berhasil diupdate!');
    }

    public function destroy(RoomType $roomType)
    {
        // Check if room type is used by any room
        if ($roomType->rooms()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus tipe kamar yang masih digunakan!');
        }

        $roomType->delete();

        return redirect()->route('room-types.index')
            ->with('success', 'Tipe kamar berhasil dihapus!');
    }
}
