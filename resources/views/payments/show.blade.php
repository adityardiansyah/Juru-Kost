<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pembayaran: {{ $payment->payment_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-bold mb-4">Informasi Pembayaran</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">No. Pembayaran:</span>
                                <p class="font-semibold">{{ $payment->payment_number }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Tanggal Pembayaran:</span>
                                <p class="font-semibold">{{ $payment->payment_date->format('d F Y') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Jumlah:</span>
                                <p class="text-2xl font-bold text-green-600">Rp
                                    {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Metode Pembayaran:</span>
                                <p class="font-semibold">{{ ucfirst($payment->payment_method) }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold mb-4">Informasi Tagihan</h3>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-600">No. Tagihan:</span>
                                <p class="font-semibold">
                                    <a href="{{ route('bills.show', $payment->bill) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        {{ $payment->bill->bill_number }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Penghuni:</span>
                                <p class="font-semibold">{{ $payment->bill->resident->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Kamar:</span>
                                <p class="font-semibold">
                                    {{ $payment->bill->resident->currentRoom->room->room_number ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($payment->notes)
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <span class="text-sm text-gray-600">Catatan:</span>
                        <p class="mt-1">{{ $payment->notes }}</p>
                    </div>
                @endif

                @if ($payment->proof_file)
                    <div class="mb-6">
                        <h3 class="font-bold mb-4">Bukti Pembayaran</h3>
                        @if (str_ends_with($payment->proof_file, '.pdf'))
                            <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Download PDF
                            </a>
                        @else
                            <img src="{{ asset('storage/' . $payment->proof_file) }}" alt="Bukti Pembayaran"
                                class="max-w-md rounded shadow">
                        @endif
                    </div>
                @endif

                <div class="flex justify-between pt-6 border-t">
                    <a href="{{ route('payments.index') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Kembali
                    </a>
                    <a href="{{ route('bills.show', $payment->bill) }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Lihat Tagihan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
