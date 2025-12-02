<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Tagihan: {{ $bill->bill_number }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('bills.download-pdf', $bill) }}"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Download PDF
                </a>
                @if ($bill->status != 'paid')
                    <a href="{{ route('payments.create', ['bill_id' => $bill->id]) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                {{-- Header --}}
                <div class="border-b pb-6 mb-6">
                    <div class="flex justify-between">
                        <div>
                            <h1 class="text-3xl font-bold">TAGIHAN</h1>
                            <p class="text-gray-600">{{ $bill->bill_number }}</p>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-4 py-2 inline-flex text-lg font-semibold rounded-full
                                {{ $bill->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $bill->status == 'unpaid' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $bill->status == 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ strtoupper($bill->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Bill Info --}}
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-bold mb-2">Kepada:</h3>
                        <p class="font-semibold">{{ $bill->resident->name }}</p>
                        <p class="text-sm text-gray-600">{{ $bill->resident->phone }}</p>
                        <p class="text-sm text-gray-600">Kamar
                            {{ $bill->resident->currentRoom->room->room_number ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        <div class="mb-2">
                            <span class="text-sm text-gray-600">Tanggal Tagihan:</span>
                            <p class="font-semibold">{{ $bill->bill_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">Jatuh Tempo:</span>
                            <p class="font-semibold {{ $bill->isOverdue() ? 'text-red-600' : '' }}">
                                {{ $bill->due_date->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Bill Items --}}
                <div class="mb-6">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Deskripsi</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold">Qty</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Harga</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($bill->items as $item)
                                <tr>
                                    <td class="px-4 py-3">{{ $item->description }}</td>
                                    <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">TOTAL</td>
                                <td class="px-4 py-3 text-right font-bold text-lg">Rp
                                    {{ number_format($bill->total_amount, 0, ',', '.') }}</td>
                            </tr>
                            @if ($bill->paid_amount > 0)
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right text-green-600">Terbayar</td>
                                    <td class="px-4 py-3 text-right text-green-600 font-semibold">Rp
                                        {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-right font-bold text-red-600">Sisa</td>
                                    <td class="px-4 py-3 text-right font-bold text-red-600 text-lg">Rp
                                        {{ number_format($bill->remaining_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>

                {{-- Payment History --}}
                @if ($bill->payments->count() > 0)
                    <div class="border-t pt-6">
                        <h3 class="font-bold mb-4">Riwayat Pembayaran</h3>
                        <div class="space-y-3">
                            @foreach ($bill->payments as $payment)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                                    <div>
                                        <p class="font-semibold">{{ $payment->payment_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $payment->payment_date->format('d F Y') }}
                                            - {{ ucfirst($payment->payment_method) }}</p>
                                    </div>
                                    <p class="font-bold text-green-600">Rp
                                        {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
