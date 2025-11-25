<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Kamar
            </h2>
            <a href="{{ route('rooms.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Kamar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('rooms.index') }}" class="flex gap-4">
                    <select name="status" class="rounded-md border-gray-300">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Kosong
                        </option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Terisi</option>
                        <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booking</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Perbaikan
                        </option>
                    </select>

                    <select name="room_type" class="rounded-md border-gray-300">
                        <option value="">Semua Tipe</option>
                        @foreach ($roomTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('room_type') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>

                    <a href="{{ route('rooms.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Rooms Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($rooms as $room)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if ($room->photos && count($room->photos) > 0)
                            <img src="{{ asset('storage/' . $room->photos[0]) }}" alt="Room {{ $room->room_number }}"
                                class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-4xl">🏠</span>
                            </div>
                        @endif

                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold">Kamar {{ $room->room_number }}</h3>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded
                                    {{ $room->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $room->status == 'occupied' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $room->status == 'booked' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $room->status == 'maintenance' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-2">{{ $room->roomType->name ?? '-' }}</p>
                            <p class="text-lg font-bold text-blue-600 mb-3">
                                Rp {{ number_format($room->price, 0, ',', '.') }}/bulan
                            </p>

                            @if ($room->currentResident)
                                <div class="mb-3 p-2 bg-blue-50 rounded">
                                    <p class="text-xs text-gray-600">Penghuni:</p>
                                    <p class="text-sm font-semibold">{{ $room->currentResident->resident->name }}</p>
                                </div>
                            @endif

                            <p class="text-sm text-gray-600 mb-3">{{ $room->facilities }}</p>

                            <div class="flex gap-2">
                                <a href="{{ route('rooms.show', $room) }}"
                                    class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 rounded text-sm">
                                    Detail
                                </a>
                                <a href="{{ route('rooms.edit', $room) }}"
                                    class="flex-1 bg-green-500 hover:bg-green-700 text-white text-center py-2 rounded text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12 bg-white rounded-lg">
                        <p class="text-gray-600">Belum ada data kamar</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
