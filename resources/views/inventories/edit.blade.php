<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Inventori
            </h2>
            <a href="{{ route('inventories.show', $inventory) }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('inventories.update', $inventory) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Inventori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                value="{{ old('name', $inventory->name) }}"
                                class="w-full rounded-md border-gray-300 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                SKU
                            </label>
                            <input type="text" value="{{ $inventory->sku }}"
                                class="w-full rounded-md border-gray-300 bg-gray-100" disabled>
                            <p class="text-xs text-gray-500 mt-1">SKU tidak dapat diubah</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Stok Saat Ini
                            </label>
                            <input type="text" value="{{ $inventory->quantity }} {{ $inventory->unit }}"
                                class="w-full rounded-md border-gray-300 bg-gray-100" disabled>
                            <p class="text-xs text-gray-500 mt-1">Gunakan tombol Stok Masuk/Keluar untuk mengubah jumlah
                                stok</p>
                        </div>

                        <div class="mb-4">
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-2">
                                Minimum Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="min_stock" id="min_stock"
                                value="{{ old('min_stock', $inventory->min_stock) }}" min="0"
                                class="w-full rounded-md border-gray-300 @error('min_stock') border-red-500 @enderror"
                                required>
                            @error('min_stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                    Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="unit" id="unit"
                                    value="{{ old('unit', $inventory->unit) }}"
                                    class="w-full rounded-md border-gray-300 @error('unit') border-red-500 @enderror"
                                    required>
                                @error('unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga Satuan <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="unit_price" id="unit_price"
                                    value="{{ old('unit_price', $inventory->unit_price) }}" min="0"
                                    step="0.01"
                                    class="w-full rounded-md border-gray-300 @error('unit_price') border-red-500 @enderror"
                                    required>
                                @error('unit_price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                            <a href="{{ route('inventories.show', $inventory) }}"
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
