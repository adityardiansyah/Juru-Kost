<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order - Juru Kost</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold gradient-text">Juru Kost</a>
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-purple-600 transition">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Order Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h1 class="text-3xl font-black text-gray-900 mb-2">Informasi Pemesanan</h1>
                    <p class="text-gray-600 mb-8">Lengkapi data di bawah untuk melanjutkan pemesanan</p>

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('order.store') }}" method="POST" id="orderForm">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        <!-- Customer Information -->
                        <div class="space-y-6">
                            <div>
                                <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" id="customer_name"
                                    value="{{ old('customer_name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="Masukkan nama lengkap Anda">
                            </div>

                            <div>
                                <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="customer_email" id="customer_email"
                                    value="{{ old('customer_email') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="email@example.com">
                                <p class="mt-1 text-sm text-gray-500">Konfirmasi pesanan akan dikirim ke email ini</p>
                            </div>

                            <div>
                                <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="customer_phone" id="customer_phone"
                                    value="{{ old('customer_phone') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="08xxxxxxxxxx">
                            </div>

                            <div>
                                <label for="company_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Kost (Opsional)
                                </label>
                                <input type="text" name="company_name" id="company_name"
                                    value="{{ old('company_name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="Nama kost Anda">
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <label
                                        class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition">
                                        <input type="radio" name="payment_method" value="bank_transfer" required
                                            class="sr-only peer">
                                        <div class="flex flex-col items-center w-full peer-checked:text-purple-600">
                                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            <span class="text-sm font-medium">Bank Transfer</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-lg opacity-0 peer-checked:opacity-100 transition">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition">
                                        <input type="radio" name="payment_method" value="gopay"
                                            class="sr-only peer">
                                        <div class="flex flex-col items-center w-full peer-checked:text-purple-600">
                                            <span class="text-2xl mb-2">💳</span>
                                            <span class="text-sm font-medium">GoPay</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-lg opacity-0 peer-checked:opacity-100 transition">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition">
                                        <input type="radio" name="payment_method" value="ovo"
                                            class="sr-only peer">
                                        <div class="flex flex-col items-center w-full peer-checked:text-purple-600">
                                            <span class="text-2xl mb-2">💰</span>
                                            <span class="text-sm font-medium">OVO</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-lg opacity-0 peer-checked:opacity-100 transition">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition">
                                        <input type="radio" name="payment_method" value="dana"
                                            class="sr-only peer">
                                        <div class="flex flex-col items-center w-full peer-checked:text-purple-600">
                                            <span class="text-2xl mb-2">💵</span>
                                            <span class="text-sm font-medium">DANA</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-lg opacity-0 peer-checked:opacity-100 transition">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition">
                                        <input type="radio" name="payment_method" value="credit_card"
                                            class="sr-only peer">
                                        <div class="flex flex-col items-center w-full peer-checked:text-purple-600">
                                            <span class="text-2xl mb-2">💳</span>
                                            <span class="text-sm font-medium">Credit Card</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-lg opacity-0 peer-checked:opacity-100 transition">
                                        </div>
                                    </label>

                                    <label
                                        class="relative flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition">
                                        <input type="radio" name="payment_method" value="qris"
                                            class="sr-only peer">
                                        <div class="flex flex-col items-center w-full peer-checked:text-purple-600">
                                            <span class="text-2xl mb-2">📱</span>
                                            <span class="text-sm font-medium">QRIS</span>
                                        </div>
                                        <div
                                            class="absolute inset-0 border-2 border-purple-600 rounded-lg opacity-0 peer-checked:opacity-100 transition">
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Catatan (Opsional)
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                    placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                            </div>

                            <!-- reCAPTCHA -->
                            <div>
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}">
                                </div>
                                @error('recaptcha')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-bold text-lg hover:shadow-2xl transition transform hover:scale-105">
                                    🚀 Proses Pesanan
                                </button>
                                <p class="mt-3 text-center text-sm text-gray-500">
                                    Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <div class="border-b border-gray-200 pb-4 mb-4">
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $package->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $package->description }}</p>
                    </div>

                    <!-- Features -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Fitur Utama:</h4>
                        <ul class="space-y-1">
                            @foreach ($package->features ?? [] as $feature)
                                <li class="flex items-start text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-200 pt-4 space-y-2">
                        @if ($package->original_price)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga Normal</span>
                                <span class="text-gray-400 line-through">Rp
                                    {{ number_format($package->original_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Diskon ({{ $package->discount_percentage }}%)</span>
                                <span class="text-green-600">-Rp
                                    {{ number_format($package->original_price - $package->price, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Total</span>
                            <span class="gradient-text">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Remaining Slots (if limited) -->
                    @if ($package->max_tenants)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-yellow-800 font-medium">
                                ⚡ Tersisa {{ $package->getRemainingSlots() }} slot lagi!
                            </p>
                        </div>
                    @endif

                    <!-- Trust Badges -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="space-y-3">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Pembayaran Aman
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Garansi 30 Hari
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                Support 24/7
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
