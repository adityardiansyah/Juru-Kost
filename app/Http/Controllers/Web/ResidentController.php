<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\Room;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $residents = Resident::with('currentRoom.room')
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->paginate(20);

        return view('residents.index', compact('residents'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'available')->get();
        return view('residents.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'id_card_number' => 'nullable|string',
            'entry_date' => 'nullable|date',
            'exit_date' => 'nullable|date|after:entry_date',
            'status' => 'required|in:active,inactive,blacklist',
            'room_id' => 'nullable|exists:rooms,id',
            'monthly_price' => 'nullable|numeric|min:0',
        ]);

        $resident = Resident::create($validated);

        // Assign to room if provided
        if ($request->room_id) {
            $room = Room::find($request->room_id);

            $resident->roomLogs()->create([
                'tenant_id' => session('tenant_id'),
                'room_id' => $room->id,
                'start_date' => $request->entry_date ?? now(),
                'monthly_price' => $request->monthly_price ?? $room->price,
            ]);

            $room->update(['status' => 'occupied']);
        }

        return redirect()->route('residents.index')
            ->with('success', 'Penghuni berhasil ditambahkan!');
    }

    public function show(Resident $resident)
    {
        $resident->load('currentRoom.room', 'documents', 'bills');
        return view('residents.show', compact('resident'));
    }

    public function edit(Resident $resident)
    {
        $rooms = Room::where('status', 'available')
            ->orWhere('id', $resident->currentRoom->room_id ?? null)
            ->get();

        return view('residents.edit', compact('resident', 'rooms'));
    }

    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'id_card_number' => 'nullable|string',
            'entry_date' => 'nullable|date',
            'exit_date' => 'nullable|date|after:entry_date',
            'status' => 'required|in:active,inactive,blacklist',
        ]);

        $resident->update($validated);

        return redirect()->route('residents.show', $resident)
            ->with('success', 'Data penghuni berhasil diupdate!');
    }

    public function destroy(Resident $resident)
    {
        // Check unpaid bills
        $unpaidBills = $resident->bills()
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->count();

        if ($unpaidBills > 0) {
            return back()->with('error', 'Tidak dapat menghapus penghuni dengan tagihan yang belum lunas!');
        }

        $resident->delete();

        return redirect()->route('residents.index')
            ->with('success', 'Penghuni berhasil dihapus!');
    }

    public function uploadDocument(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:ktp,contract,other',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        $resident->documents()->create([
            'tenant_id' => session('tenant_id'),
            'document_type' => $validated['document_type'],
            'file_path' => $path,
        ]);

        return back()->with('success', 'Dokumen berhasil diupload!');
    }

    public function moveRoom(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'new_room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'monthly_price' => 'nullable|numeric|min:0',
        ]);

        // Close current room
        $currentLog = $resident->currentRoom;
        if ($currentLog) {
            $currentLog->update(['end_date' => now()]);
            $currentLog->room->update(['status' => 'available']);
        }

        // Create new room log
        $newRoom = Room::find($validated['new_room_id']);

        $resident->roomLogs()->create([
            'tenant_id' => session('tenant_id'),
            'room_id' => $newRoom->id,
            'start_date' => $validated['start_date'],
            'monthly_price' => $validated['monthly_price'] ?? $newRoom->price,
        ]);

        $newRoom->update(['status' => 'occupied']);

        return back()->with('success', 'Penghuni berhasil dipindahkan ke kamar baru!');
    }
}
