<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Keuangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Period Selector --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('finance.index') }}" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-bold mb-2">Periode</label>
                        <input type="month" name="month" value="{{ request('month', date('Y-m')) }}"
                            class="rounded-md border-gray-300">
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Tampilkan
                    </button>
                    <a href="{{ route('finance.export') }}?month={{ request('month', date('Y-m')) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Export Excel
                    </a>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-2">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600">Rp
                        {{ number_format($summary['total_income'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-2">Total Pengeluaran</p>
                    <p class="text-2xl font-bold text-red-600">Rp
                        {{ number_format($summary['total_expense'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-2">Laba Bersih</p>
                    <p class="text-2xl font-bold text-blue-600">Rp
                        {{ number_format($summary['net_cashflow'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-purple-50 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-600 mb-2">ROI</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($roi['roi_percentage'], 2) }}%</p>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Pendapatan per Kategori</h3>
                    <canvas id="incomeChart"></canvas>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Pengeluaran per Kategori</h3>
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>

            {{-- Detailed Tables --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Rincian Pendapatan</h3>
                    <div class="space-y-2">
                        @foreach ($incomes as $income)
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm">{{ $income->description }}</span>
                                <span class="font-semibold">Rp {{ number_format($income->amount, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Rincian Pengeluaran</h3>
                    <div class="space-y-2">
                        @foreach ($expenses as $expense)
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-sm">{{ $expense->description }}</span>
                                <span class="font-semibold text-red-600">Rp
                                    {{ number_format($expense->amount, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Income Chart
        new Chart(document.getElementById('incomeChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode(array_keys($incomeCategories)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($incomeCategories)) !!},
                    backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#f59e0b', '#ef4444']
                }]
            }
        });

        // Expense Chart
        new Chart(document.getElementById('expenseChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode(array_keys($expenseCategories)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($expenseCategories)) !!},
                    backgroundColor: ['#ef4444', '#f59e0b', '#8b5cf6', '#3b82f6', '#10b981']
                }]
            }
        });
    </script>
</x-app-layout>
