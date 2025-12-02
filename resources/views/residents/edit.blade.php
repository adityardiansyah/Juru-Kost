<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Penghuni: {{ $resident->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('residents.update', $resident) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ old('name', $resident->name) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor HP *</label>
                            <input type="text" name="phone" value="{{ old('phone', $resident->phone) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $resident->email) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">No. KTP</label>
                            <input type="text" name="id_card_number" value="{{ old('id_card_number', $resident->id_card_number) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                        <textarea name="address" rows="2"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('address', $resident->address) }}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Masuk</label>
                            <input type="date" name="entry_date" 
                                value="{{ old('entry_date', $resident->entry_date?->format('Y-m-d')) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Keluar</label>
                            <input type="date" name="exit_date" 
                                value="{{ old('exit_date', $resident->exit_date?->format('Y-m-d')) }}"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Status *</label>
                            <select name="status" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700">
                                <option value="active" {{ old('status', $resident->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $resident->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="blacklist" {{ old('status', $resident->status) == 'blacklist' ? 'selected' : '' }}>Blacklist</option>
                            </select>
                        </div>
                    </div>

                    @if($resident->currentRoom)
                    <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>Kamar Saat Ini:</strong> Kamar {{ $resident->currentRoom->room->room_number }} - 
                            Rp {{ number_format($resident->currentRoom->monthly_price, 0, ',', '.') }}/bulan
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Untuk pindah kamar, gunakan fitur "Pindah Kamar" di halaman detail penghuni
                        </p>
                    </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <a href="{{ route('residents.show', $resident) }}" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>