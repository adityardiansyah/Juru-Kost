<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Record Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($bill)
                    {{-- Bill Info --}}
                    <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
                        <h3 class="font-bold text-blue-900 mb-2">Informasi Tagihan</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-blue-700">No. Tagihan: <strong>{{ $bill->bill_number }}</strong></p>
                                <p class="text-blue-700">Penghuni: <strong>{{ $bill->resident->name }}</strong></p>
                            </div>
                            <div class="text-right">
                                <p class="text-blue-700">Total: <strong>Rp
                                        {{ number_format($bill->total_amount, 0, ',', '.') }}</strong></p>
                                <p class="text-blue-700">Terbayar: <strong>Rp
                                        {{ number_format($bill->paid_amount, 0, ',', '.') }}</strong></p>
                                <p class="text-lg font-bold text-red-600">Sisa: Rp
                                    {{ number_format($bill->remaining_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if (!$bill)
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Tagihan *</label>
                            <select name="bill_id" required
                                class="shadow border rounded w-full py-2 px-3 text-gray-700 @error('bill_id') border-red-500 @enderror">
                                <option value="">-- Pilih Tagihan --</option>
                                @foreach ($unpaidBills as $unpaidBill)
                                    <option value="{{ $unpaidBill->id }}">
                                        {{ $unpaidBill->bill_number }} - {{ $unpaidBill->resident->name }} -
                                        Sisa: Rp {{ number_format($unpaidBill->remaining_amount, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bill_id')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <input type="hidden" name="bill_id" value="{{ $bill->id }}">
                    @endif

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Pembayaran *</label>
                            <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}"
                                required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('payment_date') border-red-500 @enderror">
                            @error('payment_date')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Pembayaran *</label>
                            <input type="number" name="amount"
                                value="{{ old('amount', $bill ? $bill->remaining_amount : '') }}" required
                                min="0"
                                @if ($bill) max="{{ $bill->remaining_amount }}" @endif
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 @error('amount') border-red-500 @enderror">
                            @error('amount')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                            @if ($bill)
                                <p class="text-gray-600 text-xs mt-1">Maksimal: Rp
                                    {{ number_format($bill->remaining_amount, 0, ',', '.') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran *</label>
                        <select name="payment_method" required
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 @error('payment_method') border-red-500 @enderror">
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash
                            </option>
                            <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>
                                Transfer Bank</option>
                            <option value="e-wallet" {{ old('payment_method') == 'e-wallet' ? 'selected' : '' }}>
                                E-Wallet (GoPay, OVO, dll)</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Bukti Pembayaran</label>
                        <input type="file" name="proof_file" accept="image/*,application/pdf"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                        <p class="text-gray-600 text-xs mt-1">Upload foto bukti transfer atau struk pembayaran (JPG,
                            PNG, PDF, max 2MB)</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catatan</label>
                        <textarea name="notes" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
                            placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ $bill ? route('bills.show', $bill) : route('payments.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Record Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
