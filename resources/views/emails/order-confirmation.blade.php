<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 30px 20px;
        }

        .order-number {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }

        .order-number strong {
            color: #667eea;
            font-size: 18px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .details-table th,
        .details-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }

        .details-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 18px;
        }

        .total-row td {
            color: #667eea;
        }

        .steps {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .step {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }

        .step-number {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-center;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .step-content h4 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .step-content p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✅ Pesanan Berhasil!</h1>
            <p style="margin: 10px 0 0 0;">Terima kasih atas kepercayaan Anda</p>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $order->customer_name }}</strong>,</p>

            <p>Terima kasih telah memesan <strong>{{ $package->name }}</strong> di Juru Kost! Pesanan Anda telah kami
                terima dan sedang menunggu pembayaran.</p>

            <div class="order-number">
                <strong>Nomor Pesanan: {{ $order->order_number }}</strong>
            </div>

            <h3>Detail Pesanan</h3>
            <table class="details-table">
                <tr>
                    <th>Paket</th>
                    <td>{{ $package->name }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>Rp {{ number_format($order->package_price, 0, ',', '.') }}</td>
                </tr>
                @if ($order->discount_amount > 0)
                    <tr>
                        <th>Diskon</th>
                        <td style="color: #28a745;">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total-row">
                    <th>Total Pembayaran</th>
                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                </tr>
            </table>

            <h3>Langkah Selanjutnya</h3>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Lakukan Pembayaran</h4>
                        <p>Silakan lakukan pembayaran sebesar <strong>Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</strong> melalui
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Konfirmasi Pembayaran</h4>
                        <p>Kirim bukti pembayaran ke email kami di <strong>payment@jurukost.com</strong> dengan subject
                            "Konfirmasi Pembayaran - {{ $order->order_number }}"</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Verifikasi</h4>
                        <p>Tim kami akan memverifikasi pembayaran Anda dalam waktu 1x24 jam.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Akses Akun</h4>
                        <p>Setelah pembayaran dikonfirmasi, Anda akan menerima email dengan detail akses dan panduan
                            memulai.</p>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="mailto:payment@jurukost.com?subject=Konfirmasi%20Pembayaran%20-%20{{ $order->order_number }}"
                    class="button">
                    Kirim Bukti Pembayaran
                </a>
            </div>

            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim support kami:
            </p>
            <ul>
                <li>Email: <a href="mailto:support@jurukost.com">support@jurukost.com</a></li>
                <li>WhatsApp: <a href="https://wa.me/6281234567890">+62 812-3456-7890</a></li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Juru Kost</strong> - Sistem Manajemen Kost Modern</p>
            <p>
                <a href="{{ url('/') }}">Beranda</a> |
                <a href="mailto:support@jurukost.com">Support</a> |
                <a href="#">Syarat & Ketentuan</a>
            </p>
            <p style="margin-top: 10px; color: #999; font-size: 12px;">
                Email ini dikirim otomatis, mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>

</html>
