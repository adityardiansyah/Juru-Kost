<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Tagihan Manual
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('bills.store') }}" method="POST" id="billForm">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Penghuni *</label>
                            <select name="resident_id" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 @error('resident_id') border-red-500 @enderror">
                                <option value="">-- Pilih Penghuni --</option>
                                @foreach ($residents as $resident)
                                    <option value="{{ $resident->id }}"
                                        {{ old('resident_id') == $resident->id ? 'selected' : '' }}>
                                        {{ $resident->name }} - Kamar
                                        {{ $resident->currentRoom->room->room_number ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('resident_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Tagihan *</label>
                            <input type="date" name="bill_date" value="{{ old('bill_date', date('Y-m-d')) }}"
                                required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jatuh Tempo *</label>
                        <input type="date" name="due_date"
                            value="{{ old('due_date', date('Y-m-d', strtotime('+10 days'))) }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                    </div>

                    <div class="border-t pt-4 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-gray-700">Item Tagihan</h3>
                            <button type="button" onclick="addItem()"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                + Tambah Item
                            </button>
                        </div>

                        <div id="itemsContainer">
                            <div class="item-row grid grid-cols-12 gap-2 mb-2">
                                <div class="col-span-5">
                                    <input type="text" name="items[0][description]" placeholder="Deskripsi" required
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm">
                                </div>
                                <div class="col-span-3">
                                    <input type="number" name="items[0][amount]" placeholder="Harga" required
                                        min="0"
                                        class="item-amount shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm"
                                        onchange="calculateTotal()">
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="items[0][quantity]" placeholder="Qty" required
                                        min="1" value="1"
                                        class="item-quantity shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm"
                                        onchange="calculateTotal()">
                                </div>
                                <div class="col-span-2">
                                    <span
                                        class="item-subtotal block py-2 px-3 text-gray-700 font-semibold text-sm">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 pt-4 border-t">
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Total Tagihan</p>
                                <p id="totalAmount" class="text-2xl font-bold text-blue-600">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('bills.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Buat Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let itemIndex = 1;

            function addItem() {
                const container = document.getElementById('itemsContainer');
                const newItem = `
                <div class="item-row grid grid-cols-12 gap-2 mb-2">
                    <div class="col-span-5">
                        <input type="text" name="items[${itemIndex}][description]" placeholder="Deskripsi" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm">
                    </div>
                    <div class="col-span-3">
                        <input type="number" name="items[${itemIndex}][amount]" placeholder="Harga" required min="0"
                            class="item-amount shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm"
                            onchange="calculateTotal()">
                    </div>
                    <div class="col-span-2">
                        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" required min="1" value="1"
                            class="item-quantity shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-sm"
                            onchange="calculateTotal()">
                    </div>
                    <div class="col-span-1">
                        <span class="item-subtotal block py-2 px-3 text-gray-700 font-semibold text-sm">0</span>
                    </div>
                    <div class="col-span-1">
                        <button type="button" onclick="removeItem(this)" 
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded text-sm w-full">
                            ×
                        </button>
                    </div>
                </div>
            `;
                container.insertAdjacentHTML('beforeend', newItem);
                itemIndex++;
            }

            function removeItem(button) {
                button.closest('.item-row').remove();
                calculateTotal();
            }

            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const amount = parseFloat(row.querySelector('.item-amount').value) || 0;
                    const quantity = parseInt(row.querySelector('.item-quantity').value) || 1;
                    const subtotal = amount * quantity;

                    row.querySelector('.item-subtotal').textContent = subtotal.toLocaleString('id-ID');
                    total += subtotal;
                });

                document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        </script>
    @endpush
</x-app-layout>
