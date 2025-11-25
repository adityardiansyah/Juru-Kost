<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Tagihan
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('bills.generate') }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Generate Bulanan
                </a>
                <a href="{{ route('bills.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Buat Tagihan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Statistics --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Total Tagihan Bulan Ini</p>
                    <p class="text-2xl font-bold">{{ $stats['total_bills'] ?? 0 }}</p>
                </div>
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Sudah Dibayar</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['paid'] ?? 0 }}</p>
                </div>
                <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Belum Dibayar</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['unpaid'] ?? 0 }}</p>
                </div>
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600">Terlambat</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] ?? 0 }}</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('bills.index') }}" class="flex gap-4">
                    <select name="status" class="rounded-md border-gray-300">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar
                        </option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Bayar Sebagian
                        </option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat
                        </option>
                    </select>

                    <input type="month" name="month" value="{{ request('month') }}"
                        class="rounded-md border-gray-300">

                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>
            </div>

            {{-- Bills Table --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Tagihan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penghuni
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Terbayar
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bills as $bill)
                                <tr class="{{ $bill->isOverdue() ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $bill->bill_number }}</div>
                                        <div class="text-xs text-gray-500">Jatuh tempo:
                                            {{ $bill->due_date->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $bill->resident->name }}
                                        </div>
                                        <div class="text-xs text-gray-500">Kamar
                                            {{ $bill->resident->currentRoom->room->room_number ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $bill->bill_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                                        Rp {{ number_format($bill->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <span
                                            class="{{ $bill->paid_amount > 0 ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                            Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $bill->status == 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $bill->status == 'unpaid' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $bill->status == 'partial' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $bill->status == 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ucfirst($bill->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('bills.show', $bill) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-2">Detail</a>
                                        @if ($bill->status != 'paid')
                                            <a href="{{ route('payments.create', ['bill_id' => $bill->id]) }}"
                                                class="text-green-600 hover:text-green-900">Bayar</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Belum ada tagihan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4">
                    {{ $bills->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
