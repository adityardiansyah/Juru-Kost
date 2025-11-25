<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $rooms = Room::with('roomType', 'currentResident.resident')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->room_type, function ($query, $typeId) {
                $query->where('room_type_id', $typeId);
            })
            ->paginate(20);

        $roomTypes = RoomType::all();

        return view('rooms.index', compact('rooms', 'roomTypes'));
    }

    public function create()
    {
        $roomTypes = RoomType::all();
        return view('rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_type_id' => 'nullable|exists:room_types,id',
            'room_number' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'facilities' => 'nullable|string',
            'status' => 'required|in:available,occupied,booked,maintenance',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle photo uploads
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('rooms', 'public');
                $photos[] = $path;
            }
        }
        $validated['photos'] = $photos;

        $room = Room::create($validated);

        return redirect()->route('rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function show(Room $room)
    {
        $room->load('roomType', 'currentResident.resident', 'assets');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::all();
        return view('rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_type_id' => 'nullable|exists:room_types,id',
            'room_number' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'facilities' => 'nullable|string',
            'status' => 'required|in:available,occupied,booked,maintenance',
        ]);

        // Log status change
        if ($room->status !== $validated['status']) {
            $room->statusLogs()->create([
                'tenant_id' => session('tenant_id'),
                'old_status' => $room->status,
                'new_status' => $validated['status'],
                'changed_by' => auth()->id(),
            ]);
        }

        $room->update($validated);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Kamar berhasil diupdate!');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Kamar berhasil dihapus!');
    }
}
