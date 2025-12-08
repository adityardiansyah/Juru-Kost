<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Hutang: {{ $debt->creditor_name }}
            </h2>
            <a href="{{ route('debts.index') }}" class="text-blue-600 hover:text-blue-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Debt Info Card --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-bold mb-4">Informasi Hutang</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Kreditor:</dt>
                                <dd class="font-semibold">{{ $debt->creditor_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Jenis:</dt>
                                <dd class="font-semibold">{{ ucfirst($debt->debt_type) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Pokok Hutang:</dt>
                                <dd class="font-semibold">Rp {{ number_format($debt->principal_amount, 0, ',', '.') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Bunga:</dt>
                                <dd class="font-semibold">{{ $debt->interest_rate }}% per tahun</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Total Hutang:</dt>
                                <dd class="font-semibold text-red-600">Rp
                                    {{ number_format($debt->total_amount, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4">Status Pembayaran</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Sudah Dibayar:</dt>
                                <dd class="font-semibold text-green-600">Rp
                                    {{ number_format($debt->total_paid, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Sisa Hutang:</dt>
                                <dd class="font-semibold text-orange-600">Rp
                                    {{ number_format($debt->remaining_balance, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Progress:</dt>
                                <dd class="font-semibold">{{ number_format($debt->payment_progress_percentage, 1) }}%
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Cicilan Terbayar:</dt>
                                <dd class="font-semibold">{{ $debt->paid_installments_count }} /
                                    {{ $debt->total_installments }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-600">Status:</dt>
                                <dd>
                                    @if ($debt->status == 'active')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @elseif($debt->status == 'paid_off')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Lunas</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                        <div class="mt-4">
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full"
                                    style="width: {{ $debt->payment_progress_percentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Schedule --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Jadwal Cicilan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pokok</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bunga</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa Saldo
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($schedule as $item)
                                <tr class="{{ $item['payment'] ? 'bg-green-50' : '' }}">
                                    <td class="px-4 py-3 text-sm">{{ $item['installment_number'] }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $item['due_date']->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm">Rp
                                        {{ number_format($item['principal'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm">Rp
                                        {{ number_format($item['interest'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold">Rp
                                        {{ number_format($item['total'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm">Rp
                                        {{ number_format($item['remaining_balance'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($item['payment'])
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                        @elseif($item['due_date'] < now())
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Terlambat</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if (!$item['payment'])
                                            <button
                                                onclick="openPaymentModal({{ $item['installment_number'] }}, '{{ $item['due_date']->format('Y-m-d') }}', {{ $item['total'] }}, {{ $item['principal'] }}, {{ $item['interest'] }})"
                                                class="text-blue-600 hover:text-blue-900">Bayar</button>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Modal --}}
    <div id="paymentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Catat Pembayaran</h3>
            <form action="{{ route('debts.payments.store', $debt) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="due_date" id="modal_due_date">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
                        <input type="number" name="amount" id="modal_amount" required step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pokok</label>
                        <input type="number" name="principal_paid" id="modal_principal" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bunga</label>
                        <input type="number" name="interest_paid" id="modal_interest" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Denda (jika ada)</label>
                        <input type="number" name="late_fee" value="0" step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bukti Pembayaran</label>
                        <input type="file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf"
                            class="mt-1 block w-full text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea name="notes" rows="2"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closePaymentModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(installment, dueDate, total, principal, interest) {
            document.getElementById('modal_due_date').value = dueDate;
            document.getElementById('modal_amount').value = total;
            document.getElementById('modal_principal').value = principal;
            document.getElementById('modal_interest').value = interest;
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
