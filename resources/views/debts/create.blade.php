<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Hutang Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('debts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        {{-- Creditor Name --}}
                        <div>
                            <label for="creditor_name" class="block text-sm font-medium text-gray-700">
                                Nama Kreditor <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="creditor_name" id="creditor_name"
                                value="{{ old('creditor_name') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('creditor_name') border-red-500 @enderror">
                            @error('creditor_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Debt Type --}}
                        <div>
                            <label for="debt_type" class="block text-sm font-medium text-gray-700">
                                Jenis Hutang <span class="text-red-500">*</span>
                            </label>
                            <select name="debt_type" id="debt_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach ($debtTypes as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('debt_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('debt_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Principal Amount --}}
                        <div>
                            <label for="principal_amount" class="block text-sm font-medium text-gray-700">
                                Jumlah Pokok Hutang (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="principal_amount" id="principal_amount"
                                value="{{ old('principal_amount') }}" required min="0" step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('principal_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Interest Rate --}}
                        <div>
                            <label for="interest_rate" class="block text-sm font-medium text-gray-700">
                                Bunga per Tahun (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="interest_rate" id="interest_rate"
                                value="{{ old('interest_rate', 0) }}" required min="0" max="100"
                                step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('interest_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Masukkan 0 jika tidak ada bunga</p>
                        </div>

                        {{-- Start Date --}}
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Installment Frequency --}}
                        <div>
                            <label for="installment_frequency" class="block text-sm font-medium text-gray-700">
                                Frekuensi Cicilan <span class="text-red-500">*</span>
                            </label>
                            <select name="installment_frequency" id="installment_frequency" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach ($frequencies as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('installment_frequency') == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @error('installment_frequency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Total Installments --}}
                        <div>
                            <label for="total_installments" class="block text-sm font-medium text-gray-700">
                                Jumlah Cicilan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="total_installments" id="total_installments"
                                value="{{ old('total_installments') }}" required min="1"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('total_installments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Deskripsi
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contract File --}}
                        <div>
                            <label for="contract_file" class="block text-sm font-medium text-gray-700">
                                File Kontrak/Perjanjian
                            </label>
                            <input type="file" name="contract_file" id="contract_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('contract_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Format: PDF, JPG, PNG (Max 5MB)</p>
                        </div>

                        {{-- Generate Schedule --}}
                        <div class="flex items-center">
                            <input type="checkbox" name="generate_schedule" id="generate_schedule" value="1"
                                checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="generate_schedule" class="ml-2 block text-sm text-gray-900">
                                Generate jadwal cicilan otomatis
                            </label>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('debts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            ← Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan Hutang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
