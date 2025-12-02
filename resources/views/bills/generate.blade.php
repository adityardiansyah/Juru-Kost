<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Generate Tagihan Bulanan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Sistem akan membuat tagihan untuk semua penghuni aktif</li>
                                        <li>Tagihan yang sudah ada untuk bulan ini tidak akan dibuat ulang</li>
                                        <li>Jatuh tempo otomatis 10 hari setelah tanggal tagihan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('bills.generate.process') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Pilih Bulan *
                        </label>
                        <input type="month" name="month" value="{{ date('Y-m') }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <p class="text-gray-600 text-xs mt-1">Format: Tahun-Bulan (contoh: 2025-01 untuk Januari 2025)
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded p-4 mb-6">
                        <h4 class="font-semibold mb-2">Preview</h4>
                        <p class="text-sm text-gray-600">Penghuni aktif saat ini:
                            <strong>{{ $activeResidents }}</strong></p>
                        <p class="text-sm text-gray-600">Tagihan akan dibuat untuk penghuni yang memiliki kamar aktif
                        </p>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('bills.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                            onclick="return confirm('Yakin ingin generate tagihan untuk bulan ini?')">
                            Generate Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
