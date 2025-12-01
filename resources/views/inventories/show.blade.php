<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Inventori
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('inventories.edit', $inventory) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('inventories.index') }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Inventory Info --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-2xl font-bold mb-4">{{ $inventory->name }}</h3>

                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-600">SKU:</span>
                                    <span class="text-sm font-semibold ml-2">{{ $inventory->sku }}</span>
                                </div>

                                <div>
                                    <span class="text-sm text-gray-600">Satuan:</span>
                                    <span class="text-sm font-semibold ml-2">{{ $inventory->unit }}</span>
                                </div>

                                <div>
                                    <span class="text-sm text-gray-600">Harga Satuan:</span>
                                    <span class="text-sm font-semibold ml-2">Rp
                                        {{ number_format($inventory->unit_price, 0, ',', '.') }}</span>
                                </div>

                                <div>
                                    <span class="text-sm text-gray-600">Minimum Stok:</span>
                                    <span class="text-sm font-semibold ml-2">{{ $inventory->min_stock }}
                                        {{ $inventory->unit }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            {{-- Stock Status --}}
                            <div
                                class="bg-gray-50 rounded-lg p-6 {{ $inventory->isLowStock() ? 'border-2 border-red-500' : '' }}">
                                <div class="text-sm text-gray-600 mb-2">Stok Saat Ini</div>
                                <div
                                    class="text-4xl font-bold {{ $inventory->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $inventory->quantity }}
                                    <span class="text-xl text-gray-600">{{ $inventory->unit }}</span>
                                </div>

                                @if ($inventory->isLowStock())
                                    <div
                                        class="mt-2 px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded inline-block">
                                        ⚠️ Stok Rendah
                                    </div>
                                @endif

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <div class="text-sm text-gray-600">Nilai Total Stok</div>
                                    <div class="text-2xl font-bold text-blue-600">
                                        Rp
                                        {{ number_format($inventory->quantity * $inventory->unit_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Stock Actions --}}
                            <div class="flex gap-3 mt-4">
                                <button onclick="openStockInModal()"
                                    class="flex-1 bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                                    + Stok Masuk
                                </button>
                                <button onclick="openStockOutModal()"
                                    class="flex-1 bg-red-500 hover:bg-red-700 text-white font-bold py-3 px-4 rounded">
                                    - Stok Keluar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Inventory Logs --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Riwayat Pergerakan Stok</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tipe
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Catatan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($inventory->logs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $log->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($log->type === 'in')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                    Masuk
                                                </span>
                                            @elseif ($log->type === 'out')
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                                    Keluar
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                                    Penyesuaian
                                                </span>
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $log->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $log->type === 'in' ? '+' : '-' }}{{ $log->quantity }}
                                            {{ $inventory->unit }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $log->notes ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $log->user->name ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            Belum ada riwayat pergerakan stok
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stock In Modal --}}
    <div id="stockInModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stok Masuk</h3>
                <form action="{{ route('inventories.stock-in', $inventory) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="stock_in_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="stock_in_quantity" min="1"
                            class="w-full rounded-md border-gray-300" required>
                    </div>
                    <div class="mb-4">
                        <label for="stock_in_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes" id="stock_in_notes" rows="3" class="w-full rounded-md border-gray-300"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                        <button type="button" onclick="closeStockInModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Stock Out Modal --}}
    <div id="stockOutModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Stok Keluar</h3>
                <form action="{{ route('inventories.stock-out', $inventory) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="stock_out_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="stock_out_quantity" min="1"
                            max="{{ $inventory->quantity }}" class="w-full rounded-md border-gray-300" required>
                        <p class="text-xs text-gray-500 mt-1">Stok tersedia: {{ $inventory->quantity }}
                            {{ $inventory->unit }}</p>
                    </div>
                    <div class="mb-4">
                        <label for="stock_out_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea name="notes" id="stock_out_notes" rows="3" class="w-full rounded-md border-gray-300"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Simpan
                        </button>
                        <button type="button" onclick="closeStockOutModal()"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openStockInModal() {
            document.getElementById('stockInModal').classList.remove('hidden');
        }

        function closeStockInModal() {
            document.getElementById('stockInModal').classList.add('hidden');
        }

        function openStockOutModal() {
            document.getElementById('stockOutModal').classList.remove('hidden');
        }

        function closeStockOutModal() {
            document.getElementById('stockOutModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const stockInModal = document.getElementById('stockInModal');
            const stockOutModal = document.getElementById('stockOutModal');
            if (event.target == stockInModal) {
                closeStockInModal();
            }
            if (event.target == stockOutModal) {
                closeStockOutModal();
            }
        }
    </script>
</x-app-layout>
