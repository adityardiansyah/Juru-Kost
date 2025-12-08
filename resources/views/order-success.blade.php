<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Berhasil - Juru Kost</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Pesanan Berhasil!</h1>
                <p class="text-xl text-gray-600">Terima kasih atas kepercayaan Anda</p>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="border-b border-gray-200 pb-4 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Nomor Pesanan</h2>
                    <p class="text-2xl font-black gradient-text">{{ $order->order_number }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Informasi Pelanggan</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">Nama:</span> {{ $order->customer_name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $order->customer_email }}</p>
                            <p><span class="font-medium">Telepon:</span> {{ $order->customer_phone }}</p>
                            @if ($order->company_name)
                                <p><span class="font-medium">Kost:</span> {{ $order->company_name }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Detail Paket</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p><span class="font-medium">Paket:</span> {{ $order->package->name }}</p>
                            <p><span class="font-medium">Harga:</span> Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p><span class="font-medium">Metode Pembayaran:</span>
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <span class="font-medium">Status Pembayaran:</span> Menunggu Pembayaran
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Langkah Selanjutnya</h2>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-bold">1</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Cek Email Anda</h3>
                            <p class="text-sm text-gray-600">Kami telah mengirimkan konfirmasi pesanan dan instruksi
                                pembayaran ke <span class="font-medium">{{ $order->customer_email }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-bold">2</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Lakukan Pembayaran</h3>
                            <p class="text-sm text-gray-600">Ikuti instruksi pembayaran yang tertera di email.
                                Pembayaran
                                dapat dilakukan melalui {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-bold">3</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Konfirmasi Pembayaran</h3>
                            <p class="text-sm text-gray-600">Setelah melakukan pembayaran, tim kami akan memverifikasi
                                dan
                                mengaktifkan akun Anda dalam 1x24 jam.</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 font-bold">4</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">Mulai Gunakan Juru Kost</h3>
                            <p class="text-sm text-gray-600">Setelah pembayaran dikonfirmasi, Anda akan menerima email
                                dengan akses login dan panduan memulai.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Info -->
            <div
                class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white text-center">
                <h3 class="text-lg font-bold mb-2">Butuh Bantuan?</h3>
                <p class="mb-4">Tim support kami siap membantu Anda 24/7</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="mailto:support@jurukost.com"
                        class="inline-flex items-center justify-center px-6 py-2 bg-white text-purple-600 rounded-full font-semibold hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email Support
                    </a>
                    <a href="https://wa.me/6281234567890"
                        class="inline-flex items-center justify-center px-6 py-2 bg-white text-purple-600 rounded-full font-semibold hover:bg-gray-100 transition">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-8">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center text-purple-600 hover:text-purple-700 font-semibold transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>

</html>
