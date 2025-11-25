<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kamar {{ $room->room_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('rooms.edit', $room) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <form action="{{ route('rooms.destroy', $room) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus kamar ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Room Info --}}
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Informasi Kamar</h3>

                    @if ($room->photos && count($room->photos) > 0)
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            @foreach ($room->photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="Room Photo"
                                    class="w-full h-48 object-cover rounded">
                            @endforeach
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-semibold">Nomor Kamar:</span>
                            <span>{{ $room->room_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Tipe:</span>
                            <span>{{ $room->roomType->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Harga:</span>
                            <span class="text-blue-600 font-bold">Rp
                                {{ number_format($room->price, 0, ',', '.') }}/bulan</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Status:</span>
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded
                                {{ $room->status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $room->status == 'occupied' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($room->status) }}
                            </span>
                        </div>
                        <div>
                            <span class="font-semibold">Fasilitas:</span>
                            <p class="text-gray-600 mt-1">{{ $room->facilities ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Current Resident --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Penghuni Saat Ini</h3>

                    @if ($room->currentResident)
                        <div class="space-y-3">
                            <p class="font-semibold">{{ $room->currentResident->resident->name }}</p>
                            <p class="text-sm text-gray-600">📞 {{ $room->currentResident->resident->phone }}</p>
                            <p class="text-sm text-gray-600">📅 Masuk:
                                {{ $room->currentResident->start_date->format('d/m/Y') }}</p>
                            <a href="{{ route('residents.show', $room->currentResident->resident) }}"
                                class="block text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                                Lihat Detail
                            </a>
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-4">Kamar ini belum berpenghuni</p>
                        @if ($room->status == 'available')
                            <a href="{{ route('residents.create', ['room_id' => $room->id]) }}"
                                class="block text-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                                + Tambah Penghuni
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Assets --}}
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Aset di Kamar Ini</h3>
                    <a href="{{ route('assets.create', ['room_id' => $room->id]) }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                        + Tambah Aset
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 text-left">Nama Aset</th>
                                <th class="px-4 py-2 text-left">Kode</th>
                                <th class="px-4 py-2 text-right">Harga Beli</th>
                                <th class="px-4 py-2 text-right">Nilai Sekarang</th>
                                <th class="px-4 py-2 text-center">Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($room->assets as $asset)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $asset->name }}</td>
                                    <td class="px-4 py-2">{{ $asset->code }}</td>
                                    <td class="px-4 py-2 text-right">Rp
                                        {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-right">Rp
                                        {{ number_format($asset->current_value, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <span
                                            class="px-2 py-1 text-xs rounded
                                            {{ $asset->condition == 'excellent' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $asset->condition == 'good' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $asset->condition == 'fair' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                            {{ ucfirst($asset->condition) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-600">Belum ada aset</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
