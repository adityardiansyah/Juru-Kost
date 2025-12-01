<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Inventori
            </h2>
            <a href="{{ route('inventories.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('inventories.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Inventori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Awal <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity', 0) }}"
                                    min="0"
                                    class="w-full rounded-md border-gray-300 @error('quantity') border-red-500 @enderror"
                                    required>
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                    Minimum Stok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="min_stock" id="min_stock" value="{{ old('min_stock', 5) }}"
                                    min="0"
                                    class="w-full rounded-md border-gray-300 @error('min_stock') border-red-500 @enderror"
                                    required>
                                @error('min_stock')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="unit" id="unit" value="{{ old('unit', 'pcs') }}"
                                    class="w-full rounded-md border-gray-300 @error('unit') border-red-500 @enderror"
                                    required>
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Contoh: pcs, box, kg, liter</p>
                            </div>

                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="unit_price" id="unit_price"
                                    value="{{ old('unit_price', 0) }}" min="0" step="0.01"
                                    class="w-full rounded-md border-gray-300 @error('unit_price') border-red-500 @enderror"
                                    required>
                                @error('unit_price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                            <p class="text-sm text-blue-800">
                                <strong>Info:</strong> SKU akan dibuat otomatis saat menyimpan inventori.
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                            <a href="{{ route('inventories.index') }}"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
