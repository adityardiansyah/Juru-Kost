<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Aset: {{ $asset->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('assets.update', $asset) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Aset *</label>
                        <input type="text" name="name" value="{{ old('name', $asset->name) }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi (Kamar)</label>
                        <select name="room_id"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Pilih Kamar (Opsional) --</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}"
                                    {{ old('room_id', $asset->room_id) == $room->id ? 'selected' : '' }}>
                                    Kamar {{ $room->room_number }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-gray-600 text-xs mt-1">Kosongkan jika aset umum/gudang</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Beli *</label>
                            <input type="number" name="purchase_price"
                                value="{{ old('purchase_price', $asset->purchase_price) }}" required min="0"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Beli *</label>
                            <input type="date" name="purchase_date"
                                value="{{ old('purchase_date', $asset->purchase_date->format('Y-m-d')) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Umur Ekonomis (Tahun) *</label>
                            <input type="number" name="useful_life_years"
                                value="{{ old('useful_life_years', $asset->useful_life_years) }}" required
                                min="1"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kondisi *</label>
                            <select name="condition" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="good"
                                    {{ old('condition', $asset->condition) == 'good' ? 'selected' : '' }}>Baik</option>
                                <option value="fair"
                                    {{ old('condition', $asset->condition) == 'fair' ? 'selected' : '' }}>Cukup
                                </option>
                                <option value="poor"
                                    {{ old('condition', $asset->condition) == 'poor' ? 'selected' : '' }}>Buruk
                                </option>
                                <option value="broken"
                                    {{ old('condition', $asset->condition) == 'broken' ? 'selected' : '' }}>Rusak
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ url()->previous() }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
