<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Juru Kost - Sistem Manajemen Kost Modern & Otomatis</title>

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

        .feature-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: blob 7s infinite;
        }

        @keyframes blob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        .scroll-smooth {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="antialiased bg-gray-50 scroll-smooth">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/80 backdrop-blur-md z-50 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold gradient-text">Juru Kost</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-purple-600 transition">Fitur</a>
                    <a href="#benefits" class="text-gray-700 hover:text-purple-600 transition">Keunggulan</a>
                    <a href="#pricing" class="text-gray-700 hover:text-purple-600 transition">Harga</a>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-semibold hover:shadow-lg transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-purple-600 transition font-medium">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-semibold hover:shadow-lg transition">
                                    Daftar Gratis
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Animated Background Blobs -->
        <div class="blob w-96 h-96 bg-purple-400 top-0 left-0"></div>
        <div class="blob w-96 h-96 bg-indigo-400 bottom-0 right-0" style="animation-delay: 2s"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-black text-gray-900 mb-6 leading-tight">
                    Pemilik Kost Lain Sibuk<br>
                    <span class="gradient-text">Rekap Manual</span>,<br>
                    Anda Sibuk <span class="gradient-text">Hitung Cuan</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Saat kompetitor masih pusing dengan Excel, sistem kami sudah siapkan laporan keuangan, kelola
                    penghuni, dan pantau kamar secara otomatis. 🚀
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('order.index') }}"
                        class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-bold text-lg hover:shadow-2xl transition transform hover:scale-105">
                        Mulai Gratis Sekarang
                    </a>
                    <a href="#features"
                        class="px-8 py-4 bg-white text-purple-600 rounded-full font-bold text-lg border-2 border-purple-600 hover:bg-purple-50 transition">
                        Lihat Fitur Lengkap
                    </a>
                </div>
                <p class="mt-4 text-sm text-gray-500">✨ Tidak perlu kartu kredit • Setup 5 menit • Support 24/7</p>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Penyakit Klasik Pemilik Kost...
                </h2>
                <p class="text-xl text-gray-600">Apakah Anda mengalami hal ini?</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-6 bg-red-50 rounded-2xl border-2 border-red-200">
                    <div class="text-4xl mb-4">⏰</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Waktu Habis Buat Rekap</h3>
                    <p class="text-gray-600">Berjam-jam cek pembayaran, catat pengeluaran, update status kamar
                        manual</p>
                </div>

                <div class="p-6 bg-red-50 rounded-2xl border-2 border-red-200">
                    <div class="text-4xl mb-4">💸</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Salah Hitung, Profit Ilang</h3>
                    <p class="text-gray-600">Lupa catat pengeluaran, tagihan terlewat, laporan keuangan berantakan
                    </p>
                </div>

                <div class="p-6 bg-red-50 rounded-2xl border-2 border-red-200">
                    <div class="text-4xl mb-4">😵</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Data Penghuni Kacau</h3>
                    <p class="text-gray-600">Dokumen hilang, riwayat pembayaran tidak jelas, kontrak kedaluwarsa</p>
                </div>

                <div class="p-6 bg-red-50 rounded-2xl border-2 border-red-200">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Laporan Keuangan Amburadul</h3>
                    <p class="text-gray-600">Tidak tahu profit sebenarnya, sulit analisa bisnis, investor bingung</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Fitur Unggulan <span class="gradient-text">Juru Kost</span>
                </h2>
                <p class="text-xl text-gray-600">Semua yang Anda butuhkan untuk mengelola kost modern</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Manajemen Penghuni</h3>
                    <p class="text-gray-600 mb-4">Kelola data penghuni lengkap dengan dokumen, riwayat pembayaran,
                        dan status kamar. Semua tersimpan rapi dan aman.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Upload & simpan dokumen penghuni
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tracking riwayat kamar
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Status penghuni real-time
                        </li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Keuangan Otomatis</h3>
                    <p class="text-gray-600 mb-4">Sistem billing otomatis, laporan keuangan real-time, dan analisa
                        profit yang akurat. Tidak perlu Excel lagi!</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Generate tagihan otomatis
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Laporan pemasukan & pengeluaran
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tracking pembayaran real-time
                        </li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Manajemen Kamar</h3>
                    <p class="text-gray-600 mb-4">Pantau status kamar, atur tipe dan harga, kelola ketersediaan
                        dengan mudah. Dashboard visual yang intuitif.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Status kamar real-time
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kelola tipe & harga kamar
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Riwayat status kamar
                        </li>
                    </ul>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Manajemen Aset</h3>
                    <p class="text-gray-600 mb-4">Catat semua aset kost, jadwal maintenance, dan inventaris dengan
                        rapi. Tidak ada lagi aset yang hilang track.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Database aset lengkap
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Jadwal maintenance otomatis
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tracking inventaris
                        </li>
                    </ul>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Laporan & Analitik</h3>
                    <p class="text-gray-600 mb-4">Dashboard analitik lengkap dengan grafik interaktif. Lihat
                        performa bisnis kost Anda dalam sekali pandang.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Dashboard real-time
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Grafik keuangan interaktif
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Export laporan PDF
                        </li>
                    </ul>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Multi-Tenant & Role</h3>
                    <p class="text-gray-600 mb-4">Kelola banyak kost sekaligus dengan sistem role-based. Owner,
                        admin, dan akuntan punya akses sesuai tugasnya.</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Kelola banyak kost
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Role-based access control
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Tim kolaborasi mudah
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Kenapa <span class="gradient-text">Juru Kost</span>?
                </h2>
                <p class="text-xl text-gray-600">Lebih dari sekedar software manajemen kost</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">⚡</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Setup 5 Menit</h3>
                    <p class="text-gray-600">Tidak perlu training lama. Interface intuitif dan mudah dipahami. Langsung
                        produktif hari pertama.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">💰</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Hemat Waktu & Biaya</h3>
                    <p class="text-gray-600">Otomasi 80% pekerjaan admin. Hemat biaya karyawan dan fokus ke hal yang
                        lebih penting.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">🔒</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Data Aman Terjamin</h3>
                    <p class="text-gray-600">Enkripsi tingkat bank, backup otomatis, dan server Indonesia. Data Anda
                        100% aman.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">📱</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Akses Dimana Saja</h3>
                    <p class="text-gray-600">Web-based system yang bisa diakses dari HP, tablet, atau laptop. Kelola
                        kost dari mana saja.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">🎯</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Update Berkala</h3>
                    <p class="text-gray-600">Fitur baru ditambahkan rutin berdasarkan feedback pengguna. Investasi yang
                        terus berkembang.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">💬</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Support 24/7</h3>
                    <p class="text-gray-600">Tim support siap membantu kapan saja. Chat, email, atau video call. Kami
                        ada untuk Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gradient-to-br from-purple-50 to-indigo-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                    Penawaran <span class="gradient-text">Super Spesial!</span>
                </h2>
                <p class="text-xl text-gray-600">Investasi sekali, untung selamanya</p>
            </div>

            <!-- Main Pricing Card -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-purple-500 relative">
                    <!-- Popular Badge -->
                    <div
                        class="absolute top-0 right-0 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-6 py-2 rounded-bl-2xl font-bold text-sm">
                        🔥 PALING LARIS
                    </div>

                    <div class="p-8 md:p-12">
                        <!-- Plan Name -->
                        <div class="text-center mb-8">
                            <h3 class="text-3xl font-black text-gray-900 mb-2">Akses Lifetime</h3>
                            <p class="text-gray-600">Satu kali bayar, akses selamanya!</p>
                        </div>

                        <!-- Price -->
                        <div class="text-center mb-8">
                            <div class="flex items-center justify-center gap-4 mb-4">
                                <span class="text-2xl text-gray-400 line-through">Rp 2.499.000</span>
                                <span class="bg-red-500 text-white px-4 py-1 rounded-full text-sm font-bold">
                                    HEMAT 88%
                                </span>
                            </div>
                            <div class="flex items-baseline justify-center">
                                <span class="text-5xl md:text-7xl font-black gradient-text">Rp 299.000</span>
                            </div>
                            <p class="text-gray-500 mt-2">Pembayaran satu kali selamanya</p>
                        </div>

                        <!-- Urgency Banner -->
                        <div
                            class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-4 rounded-xl mb-8 text-center">
                            <p class="font-bold text-lg mb-1">⚡ Penawaran Terbatas!</p>
                            <p class="text-sm">Harga spesial ini hanya untuk <span class="font-bold">500 pengguna
                                    pertama</span></p>
                            <p class="text-xs mt-1 opacity-90">Setelah kuota habis, harga kembali normal Rp 2.499.000
                            </p>
                        </div>

                        <!-- Features List -->
                        <div class="grid md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <h4 class="font-bold text-gray-900 mb-4 text-lg">✨ Fitur Utama:</h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Manajemen Penghuni Unlimited</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Billing & Tagihan Otomatis</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Laporan Keuangan Real-time</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Manajemen Kamar & Aset</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Multi-Tenant (Kelola Banyak Kost)</span>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-bold text-gray-900 mb-4 text-lg">🎁 Bonus Eksklusif:</h4>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Update Fitur Selamanya</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Priority Support 24/7</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Template Dokumen Lengkap</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Video Tutorial Lengkap</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0 mt-0.5"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-gray-700">Konsultasi Setup Gratis</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Total Value -->
                        <div class="bg-gradient-to-r from-purple-100 to-indigo-100 p-6 rounded-2xl mb-8 text-center">
                            <p class="text-gray-700 mb-2">Total Nilai Paket:</p>
                            <p class="text-3xl font-black gradient-text">Rp 5.999.000</p>
                            <p class="text-sm text-gray-600 mt-1">Anda dapatkan SEMUA dengan harga spesial!</p>
                        </div>

                        <!-- CTA Button -->
                        <div class="text-center">
                            <a href="{{ route('order.index') }}"
                                class="inline-block w-full md:w-auto px-12 py-5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-black text-xl hover:shadow-2xl transition transform hover:scale-105 mb-4">
                                🚀 Amankan Harga Spesial Sekarang!
                            </a>
                            <p class="text-sm text-gray-500">✨ Tidak perlu kartu kredit • Garansi 30 hari uang kembali
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div class="bg-white p-4 rounded-xl shadow-md">
                        <div class="text-3xl mb-2">🔒</div>
                        <p class="text-sm font-semibold text-gray-700">Pembayaran Aman</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-md">
                        <div class="text-3xl mb-2">💯</div>
                        <p class="text-sm font-semibold text-gray-700">Garansi 30 Hari</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-md">
                        <div class="text-3xl mb-2">⚡</div>
                        <p class="text-sm font-semibold text-gray-700">Akses Instan</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-md">
                        <div class="text-3xl mb-2">🎓</div>
                        <p class="text-sm font-semibold text-gray-700">Training Gratis</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial Teaser -->
            <div class="mt-16 text-center">
                <p class="text-gray-600 mb-6 text-lg">Bergabung dengan <span class="font-bold text-purple-600">500+
                        pemilik kost</span> yang sudah merasakan manfaatnya</p>
                <div class="flex justify-center items-center gap-2">
                    <div class="flex -space-x-2">
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 border-2 border-white">
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-400 border-2 border-white">
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-400 border-2 border-white">
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-400 border-2 border-white">
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-gradient-to-br from-red-400 to-pink-400 border-2 border-white">
                        </div>
                    </div>
                    <div class="flex items-center gap-1 ml-2">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-2 text-sm font-semibold text-gray-700">4.9/5</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                Siap Pensiun dari Excel & Fokus ke Profit?
            </h2>
            <p class="text-xl text-white/90 mb-8">
                Bergabung dengan ratusan pemilik kost yang sudah beralih ke sistem otomatis
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('order.index') }}"
                    class="px-8 py-4 bg-white text-purple-600 rounded-full font-bold text-lg hover:shadow-2xl transition transform hover:scale-105">
                    Mulai Gratis Sekarang
                </a>
                <a href="#features"
                    class="px-8 py-4 bg-transparent text-white rounded-full font-bold text-lg border-2 border-white hover:bg-white/10 transition">
                    Pelajari Lebih Lanjut
                </a>
            </div>
            <p class="mt-6 text-white/80 text-sm">
                ✨ Gratis 14 hari trial • Tidak perlu kartu kredit • Batal kapan saja
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-2xl font-bold gradient-text mb-4">Juru Kost</h3>
                    <p class="text-gray-400">Sistem manajemen kost modern untuk pemilik kost yang ingin fokus ke
                        profit, bukan administrasi.</p>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition">Fitur</a></li>
                        <li><a href="#benefits" class="hover:text-white transition">Keunggulan</a></li>
                        <li><a href="#pricing" class="hover:text-white transition">Harga</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Kontak</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Juru Kost. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
