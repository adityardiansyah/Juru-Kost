<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Kamar {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('rooms.update', $room) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Kamar *</label>
                        <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('room_number') border-red-500 @enderror"
                            required>
                        @error('room_number')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Kamar</label>
                        <select name="room_type_id"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">-- Pilih Tipe --</option>
                            @foreach ($roomTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga per Bulan *</label>
                        <input type="number" name="price" value="{{ old('price', $room->price) }}"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror"
                            required min="0">
                        @error('price')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fasilitas</label>
                        <textarea name="facilities" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('facilities', $room->facilities) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status *</label>
                        <select name="status"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                            <option value="available"
                                {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Kosong</option>
                            <option value="occupied"
                                {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>
                                Terisi</option>
                            <option value="booked" {{ old('status', $room->status) == 'booked' ? 'selected' : '' }}>
                                Booking</option>
                            <option value="maintenance"
                                {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Perbaikan
                            </option>
                        </select>
                    </div>

                    @if ($room->photos && count($room->photos) > 0)
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Foto Saat Ini</label>
                            <div class="grid grid-cols-4 gap-2" id="currentPhotos">
                                @foreach ($room->photos as $index => $photo)
                                    <div class="relative photo-item" data-photo="{{ $photo }}">
                                        <img src="{{ asset('storage/' . $photo) }}" alt="Room Photo"
                                            class="w-full h-24 object-cover rounded">
                                        <button type="button" onclick="removePhoto('{{ $photo }}', this)"
                                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="keep_photos" id="keepPhotos"
                                value="{{ json_encode($room->photos) }}">
                        </div>
                    @endif

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Upload Foto Baru (Opsional)</label>
                        <input type="file" name="photos[]" multiple accept="image/*"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-gray-600 text-xs mt-1">Upload foto baru akan ditambahkan ke foto yang ada</p>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('rooms.show', $room) }}"
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

    <script>
        let keepPhotos = @json($room->photos ?? []);

        function removePhoto(photo, button) {
            if (confirm('Yakin ingin hapus foto ini?')) {
                // Remove from array
                keepPhotos = keepPhotos.filter(p => p !== photo);

                // Update hidden input
                document.getElementById('keepPhotos').value = JSON.stringify(keepPhotos);

                // Remove from DOM
                button.closest('.photo-item').remove();
            }
        }
    </script>
</x-app-layout>
