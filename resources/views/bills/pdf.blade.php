<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tagihan #{{ $bill->bill_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #2c3e50;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #7f8c8d;
        }

        .invoice-info {
            margin-bottom: 30px;
            overflow: hidden;
        }

        .invoice-info .left {
            float: left;
            width: 50%;
        }

        .invoice-info .right {
            float: right;
            width: 40%;
            text-align: right;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .table th,
        .table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background-color: #f8f9fa;
            text-align: left;
            font-weight: bold;
            color: #2c3e50;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            width: 100%;
            text-align: right;
        }

        .total-section table {
            float: right;
            width: 40%;
        }

        .total-section td {
            padding: 5px;
        }

        .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin-top: 10px;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-unpaid {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-partial {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $bill->tenant->name }}</h1>
        <p>{{ $bill->tenant->address }}</p>
        <p>Telp: {{ $bill->tenant->phone }} | Email: {{ $bill->tenant->email }}</p>
    </div>

    <div class="invoice-info">
        <div class="left">
            <strong>Tagihan Kepada:</strong><br>
            {{ $bill->resident->name }}<br>
            Kamar: {{ $bill->resident->currentRoom->room->room_number ?? '-' }}<br>
            {{ $bill->resident->phone }}
        </div>
        <div class="right">
            <strong>Detail Tagihan:</strong><br>
            No: {{ $bill->bill_number }}<br>
            Tanggal: {{ $bill->bill_date->format('d/m/Y') }}<br>
            Jatuh Tempo: {{ $bill->due_date->format('d/m/Y') }}<br>

            @php
                $statusClass = match ($bill->status) {
                    'paid' => 'status-paid',
                    'unpaid' => 'status-unpaid',
                    'partial' => 'status-partial',
                    'overdue' => 'status-overdue',
                    default => '',
                };

                $statusLabel = match ($bill->status) {
                    'paid' => 'Lunas',
                    'unpaid' => 'Belum Lunas',
                    'partial' => 'Bayar Sebagian',
                    'overdue' => 'Terlambat',
                    default => $bill->status,
                };
            @endphp
            <span class="status {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th style="width: 50%">Deskripsi</th>
                <th style="width: 10%" class="text-right">Qty</th>
                <th style="width: 15%" class="text-right">Harga</th>
                <th style="width: 20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bill->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>Total Tagihan:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Sudah Dibayar:</td>
                <td class="text-right">Rp {{ number_format($bill->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="grand-total">Sisa Tagihan:</td>
                <td class="text-right grand-total">Rp
                    {{ number_format($bill->total_amount - $bill->paid_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih atas pembayaran Anda.</p>
        <p>Dokumen ini dibuat secara otomatis oleh sistem JuruKost.</p>
    </div>
</body>

</html>
