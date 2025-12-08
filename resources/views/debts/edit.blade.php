<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Hutang: {{ $debt->creditor_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <form action="{{ route('debts.update', $debt) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="creditor_name" class="block text-sm font-medium text-gray-700">Nama
                                Kreditor</label>
                            <input type="text" name="creditor_name" id="creditor_name"
                                value="{{ old('creditor_name', $debt->creditor_name) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="debt_type" class="block text-sm font-medium text-gray-700">Jenis Hutang</label>
                            <select name="debt_type" id="debt_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @foreach ($debtTypes as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('debt_type', $debt->debt_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $debt->description) }}</textarea>
                        </div>

                        <div>
                            <label for="contract_file" class="block text-sm font-medium text-gray-700">File
                                Kontrak</label>
                            @if ($debt->contract_file)
                                <p class="text-sm text-gray-500 mb-2">File saat ini: <a
                                        href="{{ Storage::url($debt->contract_file) }}" target="_blank"
                                        class="text-blue-600">Lihat</a></p>
                            @endif
                            <input type="file" name="contract_file" id="contract_file" accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1 block w-full text-sm text-gray-500">
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between">
                        <a href="{{ route('debts.show', $debt) }}" class="text-sm text-gray-600 hover:text-gray-900">←
                            Kembali</a>
                        <button type="submit"
                            class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Update Hutang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
