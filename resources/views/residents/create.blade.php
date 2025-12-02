<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Penghuni Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('residents.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor HP *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('phone') border-red-500 @enderror"
                                placeholder="081234567890">
                            @error('phone')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">No. KTP</label>
                            <input type="text" name="id_card_number" value="{{ old('id_card_number') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                                maxlength="16">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                        <textarea name="address" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                            <input type="date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Keluar</label>
                            <input type="date" name="exit_date" value="{{ old('exit_date') }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Status *</label>
                            <select name="status" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                                <option value="blacklist" {{ old('status') == 'blacklist' ? 'selected' : '' }}>
                                    Blacklist</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-4">
                        <h3 class="font-bold text-gray-700 mb-3">Informasi Kamar</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Kamar</label>
                                <select name="room_id" id="room_id"
                                    class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                    <option value="">-- Pilih Kamar --</option>
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}" data-price="{{ $room->price }}"
                                            {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            Kamar {{ $room->room_number }} - Rp
                                            {{ number_format($room->price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-gray-600 text-xs mt-1">Kosongkan jika belum ada kamar</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa/Bulan</label>
                                <input type="number" name="monthly_price" id="monthly_price"
                                    value="{{ old('monthly_price') }}"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                                    min="0">
                                <p class="text-gray-600 text-xs mt-1">Otomatis occupied dari harga kamar</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('residents.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('room_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price) {
                    document.getElementById('monthly_price').value = price;
                }
            });
        </script>
    @endpush
</x-app-layout>
