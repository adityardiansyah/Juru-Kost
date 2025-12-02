<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detail Aset: {{ $asset->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- QR Code --}}
                    <div class="md:col-span-1 text-center">
                        <div class="bg-gray-50 p-4 rounded-lg inline-block">
                            @if ($asset->qr_code && Storage::disk('public')->exists($asset->qr_code))
                                <img src="{{ asset('storage/' . $asset->qr_code) }}" alt="QR Code"
                                    class="w-48 h-48 object-contain">
                            @else
                                <div class="w-48 h-48 flex items-center justify-center bg-gray-200 text-gray-500">
                                    No QR Code
                                </div>
                            @endif
                            <p class="mt-2 font-mono font-bold text-lg">{{ $asset->code }}</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('assets.qrcode', $asset) }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded block w-full text-center">
                                Download QR
                            </a>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama Aset</p>
                                <p class="font-semibold text-lg">{{ $asset->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Lokasi</p>
                                <p class="font-semibold text-lg">
                                    @if ($asset->room)
                                        <a href="{{ route('rooms.show', $asset->room) }}"
                                            class="text-blue-600 hover:underline">
                                            Kamar {{ $asset->room->room_number }}
                                        </a>
                                    @else
                                        Gudang / Umum
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Kondisi</p>
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mt-1
                                    {{ $asset->condition == 'good' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $asset->condition == 'fair' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $asset->condition == 'poor' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $asset->condition == 'broken' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($asset->condition) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Umur Ekonomis</p>
                                <p class="font-semibold">{{ $asset->useful_life_years }} Tahun</p>
                            </div>
                        </div>

                        <div class="border-t my-4 pt-4">
                            <h3 class="font-bold text-gray-700 mb-3">Nilai Aset</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Harga Beli</p>
                                    <p class="font-semibold">Rp
                                        {{ number_format($asset->purchase_price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $asset->purchase_date->format('d F Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Nilai Saat Ini</p>
                                    <p class="font-semibold text-green-600">Rp
                                        {{ number_format($asset->current_value, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">Depresiasi: Rp
                                        {{ number_format($asset->purchase_price - $asset->current_value, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 mt-6">
                            <a href="{{ route('assets.edit', $asset) }}"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
