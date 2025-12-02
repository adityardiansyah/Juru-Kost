<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Penghuni: {{ $resident->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('residents.edit', $resident) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Personal Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Informasi Pribadi</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">Nama Lengkap</label>
                                <p class="font-semibold">{{ $resident->name }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">No. KTP</label>
                                <p class="font-semibold">{{ $resident->id_card_number ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Nomor HP</label>
                                <p class="font-semibold">{{ $resident->phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Email</label>
                                <p class="font-semibold">{{ $resident->email ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <label class="text-sm text-gray-600">Alamat</label>
                                <p class="font-semibold">{{ $resident->address ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Tanggal Masuk</label>
                                <p class="font-semibold">
                                    {{ $resident->entry_date ? $resident->entry_date->format('d F Y') : '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Tanggal Keluar</label>
                                <p class="font-semibold">
                                    {{ $resident->exit_date ? $resident->exit_date->format('d F Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Dokumen</h3>
                            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                + Upload Dokumen
                            </button>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            @forelse($resident->documents as $doc)
                                <div class="border rounded p-3">
                                    <p class="text-xs text-gray-600 mb-2">{{ strtoupper($doc->document_type) }}</p>
                                    @if (str_ends_with($doc->file_path, '.pdf'))
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                            class="text-blue-600 hover:underline text-sm">
                                            📄 Lihat PDF
                                        </a>
                                    @else
                                        <img src="{{ asset('storage/' . $doc->file_path) }}"
                                            alt="{{ $doc->document_type }}" class="w-full h-32 object-cover rounded">
                                    @endif
                                </div>
                            @empty
                                <p class="col-span-3 text-gray-600 text-center py-4">Belum ada dokumen</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Bills History --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Riwayat Tagihan</h3>

                        <div class="space-y-3">
                            @forelse($resident->bills()->latest('bill_date')->take(5)->get() as $bill)
                                <div class="flex justify-between items-center border-b pb-3">
                                    <div>
                                        <p class="font-semibold">{{ $bill->bill_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $bill->bill_date->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold">Rp
                                            {{ number_format($bill->total_amount, 0, ',', '.') }}</p>
                                        <span
                                            class="px-2 py-1 text-xs rounded
                                            {{ $bill->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $bill->status == 'unpaid' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $bill->status == 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-600 text-center py-4">Belum ada tagihan</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Current Room --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Kamar Saat Ini</h3>

                        @if ($resident->currentRoom)
                            <div class="text-center">
                                <div class="text-4xl font-bold text-blue-600 mb-2">
                                    {{ $resident->currentRoom->room->room_number }}
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $resident->currentRoom->room->roomType->name ?? '-' }}</p>
                                <p class="text-lg font-bold mb-4">Rp
                                    {{ number_format($resident->currentRoom->monthly_price, 0, ',', '.') }}/bulan</p>
                                <a href="{{ route('rooms.show', $resident->currentRoom->room) }}"
                                    class="block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Lihat Kamar
                                </a>
                            </div>
                        @else
                            <p class="text-gray-600 text-center py-4">Belum ada kamar</p>
                        @endif
                    </div>

                    {{-- Status --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Status</h3>

                        <div class="text-center">
                            <span
                                class="px-4 py-2 inline-flex text-lg font-semibold rounded-full
                                {{ $resident->status == 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $resident->status == 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $resident->status == 'blacklist' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($resident->status) }}
                            </span>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Aksi Cepat</h3>

                        <div class="space-y-2">
                            <a href="{{ route('bills.create', ['resident_id' => $resident->id]) }}"
                                class="block w-full bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                                Buat Tagihan
                            </a>
                            <button onclick="document.getElementById('moveRoomModal').classList.remove('hidden')"
                                class="block w-full bg-yellow-500 hover:bg-yellow-700 text-white text-center font-bold py-2 px-4 rounded">
                                Pindah Kamar
                            </button>
                            <a href="{{ route('maintenance-requests.create', ['resident_id' => $resident->id]) }}"
                                class="block w-full bg-orange-500 hover:bg-orange-700 text-white text-center font-bold py-2 px-4 rounded">
                                Lapor Kerusakan
                            </a>
                            <button onclick="alert('Fitur WhatsApp coming soon!')"
                                class="block w-full bg-green-500 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded">
                                Kirim WhatsApp
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Move Room Modal --}}
    <div id="moveRoomModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-bold mb-4">Pindah Kamar</h3>

                @if ($resident->currentRoom)
                    <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>Kamar Saat Ini:</strong> Kamar {{ $resident->currentRoom->room->room_number }} -
                            Rp {{ number_format($resident->currentRoom->monthly_price, 0, ',', '.') }}/bulan
                        </p>
                    </div>
                @endif

                <form action="{{ route('residents.move-room', $resident) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kamar Baru *</label>
                        <select name="new_room_id" id="new_room_id" required
                            class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            <option value="">-- Pilih Kamar Baru --</option>
                            @foreach ($availableRooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->price }}">
                                    Kamar {{ $room->room_number }} - Rp
                                    {{ number_format($room->price, 0, ',', '.') }}/bulan
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pindah *</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Harga Sewa/Bulan *</label>
                            <input type="number" name="monthly_price" id="new_monthly_price" required
                                min="0" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-4">
                        <p class="text-sm text-yellow-800">
                            <strong>Perhatian:</strong> Kamar lama akan diubah statusnya menjadi "Kosong" dan kamar baru
                            akan menjadi "occupied".
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                            onclick="document.getElementById('moveRoomModal').classList.add('hidden')"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Yakin ingin pindahkan ke kamar baru?')">
                            Pindah Kamar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('new_room_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price) {
                    document.getElementById('new_monthly_price').value = price;
                }
            });
        </script>
    @endpush
</x-app-layout>
