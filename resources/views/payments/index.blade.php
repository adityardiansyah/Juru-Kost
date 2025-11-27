<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('payments.index') }}" class="flex gap-4">
                    <input type="month" name="month" value="{{ request('month') }}" 
                        class="rounded-md border-gray-300">
                    
                    <select name="payment_method" class="rounded-md border-gray-300">
                        <option value="">Semua Metode</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="e-wallet" {{ request('payment_method') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Total Pembayaran Bulan Ini</p>
                    <p class="text-2xl font-bold text-green-600">{{ $summary['count'] ?? 0 }}</p>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Total Nominal</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total'] ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Rata-rata per Transaksi</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($summary['average'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Payments Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penghuni</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Tagihan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Metode</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->payment_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $payment->bill->resident->name }}</div>
                                        <div class="text-xs text-gray-500">Kamar {{ $payment->bill->resident->currentRoom->room->room_number ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('bills.show', $payment->bill) }}" class="text-sm text-blue-600 hover:text-blue-900">
                                            {{ $payment->bill->bill_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payment->payment_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-green-600">
                                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $payment->payment_method == 'cash' ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $payment->payment_method == 'transfer' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $payment->payment_method == 'e-wallet' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($payment->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                                        @if($payment->proof_file)
                                            <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="ml-2 text-green-600 hover:text-green-900">Bukti</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada pembayaran
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>