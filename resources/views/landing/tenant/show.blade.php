<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $tenant->name }} - Kost di {{ $tenant->address }} | Juru Kost</title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="{{ Str::limit($tenant->description, 155) }} - Kost nyaman di {{ $tenant->address }}. Lihat kamar tersedia dan hubungi kami sekarang!">
    <meta name="keywords" content="kost {{ $tenant->address }}, {{ $tenant->name }}, sewa kost, kost murah, kost nyaman">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $tenant->name }} - Kost di {{ $tenant->address }}">
    <meta property="og:description" content="{{ Str::limit($tenant->description, 155) }}">
    @if ($availableRooms->isNotEmpty() && $availableRooms->first()->photos)
        <meta property="og:image" content="{{ Storage::url($availableRooms->first()->photos[0]) }}">
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $tenant->name }} - Kost di {{ $tenant->address }}">
    <meta property="twitter:description" content="{{ Str::limit($tenant->description, 155) }}">
    @if ($availableRooms->isNotEmpty() && $availableRooms->first()->photos)
        <meta property="twitter:image" content="{{ Storage::url($availableRooms->first()->photos[0]) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide img {
            width: 100%;
            height: 256px;
            object-fit: cover;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">

        <!-- Navbar -->
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">🏠 Juru Kost</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="#rooms"
                            class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">Kamar</a>
                        <a href="#location"
                            class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">Lokasi</a>
                        <a href="#contact"
                            class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">Hubungi</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-white overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-90"></div>
                <div
                    class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
                </div>
                <div
                    class="absolute top-0 right-1/4 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
                </div>
            </div>

            <div class="relative max-w-7xl mx-auto py-24 px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="mt-4 text-5xl font-extrabold text-gray-900 sm:text-6xl sm:tracking-tight lg:text-7xl mb-6">
                    {{ $tenant->name }}
                </h1>
                <div class="flex items-center justify-center space-x-2 text-gray-500 mb-8">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-xl font-medium">{{ $tenant->address }}</span>
                </div>
                <p class="max-w-2xl mx-auto text-xl text-gray-600 leading-relaxed">
                    {{ $tenant->description }}
                </p>
                <div class="mt-8 mb-16">
                    <a href="#rooms"
                        class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Lihat Kamar
                    </a>
                </div>
            </div>
        </div>

        <!-- Available Rooms Section -->
        <div id="rooms" class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Pilihan Kamar</h2>
                    <p class="mt-4 text-lg text-gray-500">Temukan kamar yang sesuai dengan kebutuhan dan budget Anda</p>
                </div>

                @if ($availableRooms->isEmpty())
                    <div class="text-center py-12 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada kamar tersedia</h3>
                        <p class="mt-2 text-gray-500">Silakan hubungi kami untuk informasi waiting list.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-y-10 gap-x-8 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($availableRooms as $room)
                            <div
                                class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col">
                                <div class="relative bg-gray-200 overflow-hidden">
                                    @if ($room->photos && count($room->photos) > 0)
                                        <div class="swiper roomSwiper-{{ $room->id }}">
                                            <div class="swiper-wrapper">
                                                @foreach ($room->photos as $photo)
                                                    <div class="swiper-slide">
                                                        <img src="{{ Storage::url($photo) }}"
                                                            alt="Room {{ $room->room_number }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="swiper-pagination"></div>
                                            <div class="swiper-button-next"></div>
                                            <div class="swiper-button-prev"></div>
                                        </div>
                                    @else
                                        <div
                                            class="w-full h-64 bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-sm">Tidak ada foto</span>
                                        </div>
                                    @endif
                                    <div class="absolute top-4 right-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                            Tersedia
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">Kamar {{ $room->room_number }}
                                            </h3>
                                            <p class="text-sm text-indigo-600 font-medium mt-1">
                                                {{ $room->roomType ? $room->roomType->name : 'Standard Room' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900">Rp
                                                {{ number_format($room->price / 1000, 0) }}k</p>
                                            <p class="text-xs text-gray-500">/ bulan</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 pt-4 mt-auto">
                                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">
                                            Fasilitas</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @if ($room->facilities)
                                                @foreach (explode(',', $room->facilities) as $facility)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ trim($facility) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-sm text-gray-400 italic">Tidak ada data
                                                    fasilitas</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button onclick="selectRoom('{{ $room->room_number }}')"
                                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                                            Pilih Kamar Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Testimonials Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Testimoni Penghuni</h2>
                    <p class="mt-4 text-lg text-gray-500">Apa kata mereka yang sudah tinggal di sini</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($testimonials as $testimonial)
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $testimonial->rating)
                                            ⭐
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-600 mb-4">"{{ $testimonial->content }}"</p>
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                    {{ $testimonial->avatar_initial }}
                                </div>
                                <div class="ml-3">
                                    <p class="font-semibold text-gray-900">{{ $testimonial->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $testimonial->role }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8">
                            <p class="text-gray-500">Belum ada testimoni untuk ditampilkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Location Map Section -->
        <div id="location" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Lokasi</h2>
                    <p class="mt-4 text-lg text-gray-500">{{ $tenant->address }}</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="https://www.google.com/maps?q={{ urlencode($tenant->address) }}&output=embed"
                            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade" class="w-full">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="py-16 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Pertanyaan Umum</h2>
                    <p class="mt-4 text-lg text-gray-500">Jawaban untuk pertanyaan yang sering ditanyakan</p>
                </div>

                <div class="space-y-4">
                    @forelse($faqs as $faq)
                        <details class="group bg-gray-50 rounded-lg border border-gray-200">
                            <summary
                                class="flex justify-between items-center cursor-pointer p-6 font-semibold text-gray-900">
                                <span>{{ $faq->question }}</span>
                                <svg class="w-5 h-5 text-gray-500 group-open:rotate-180 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <div class="px-6 pb-6 text-gray-600">
                                {{ $faq->answer }}
                            </div>
                        </details>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada FAQ untuk ditampilkan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Contact Form Section -->
        <div id="contact" class="bg-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                    <div class="bg-indigo-600 py-8 px-8 text-center">
                        <h2 class="text-3xl font-bold text-white">Tertarik? Hubungi Kami</h2>
                        <p class="mt-2 text-indigo-100">Isi formulir di bawah untuk menjadwalkan survey lokasi</p>
                    </div>

                    <div class="p-8 sm:p-12">
                        <form id="contactForm" class="space-y-8">
                            <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 mb-1 mt-4">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition-colors">
                                </div>

                                <div>
                                    <label for="origin"
                                        class="block text-sm font-medium text-gray-700 mb-1 mt-4">Asal Kota</label>
                                    <input type="text" name="origin" id="origin" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition-colors">
                                </div>

                                <div>
                                    <label for="job_status"
                                        class="block text-sm font-medium text-gray-700 mb-1 mt-4">Status
                                        Pekerjaan</label>
                                    <select name="job_status" id="job_status"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition-colors">
                                        <option value="Mahasiswa">Mahasiswa</option>
                                        <option value="Karyawan">Karyawan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="gender"
                                        class="block text-sm font-medium text-gray-700 mb-1 mt-4">Jenis Kelamin</label>
                                    <select name="gender" id="gender"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition-colors">
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="survey_date"
                                        class="block text-sm font-medium text-gray-700 mb-1 mt-4">Rencana
                                        Survey</label>
                                    <input type="date" name="survey_date" id="survey_date" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition-colors">
                                </div>

                                <div>
                                    <label for="survey_time"
                                        class="block text-sm font-medium text-gray-700 mb-1 mt-4">Jam Survey</label>
                                    <input type="time" name="survey_time" id="survey_time" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 bg-gray-50 focus:bg-white transition-colors">
                                </div>
                            </div>

                            <input type="hidden" id="selected_room" name="selected_room">

                            <div class="pt-4">
                                <button type="submit"
                                    class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-1">
                                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                    Kirim Pesan via WhatsApp
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-gray-900 text-white border-t border-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between text-center">
                    <span
                        class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">
                        Juru Kost
                    </span>
                    <p class="text-center text-base text-gray-400">
                        &copy; {{ date('Y') }} Juru Kost. dibuat dengan <span class="text-indigo-400">❤️</span>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // Initialize Swiper for each room
        document.addEventListener('DOMContentLoaded', function() {
            @foreach ($availableRooms as $room)
                @if ($room->photos && count($room->photos) > 1)
                    new Swiper('.roomSwiper-{{ $room->id }}', {
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        loop: true,
                    });
                @endif
            @endforeach
        });

        function selectRoom(roomNumber) {
            document.getElementById('selected_room').value = roomNumber;
            document.getElementById('contact').scrollIntoView({
                behavior: 'smooth'
            });
        }

        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const tenantPhone = "{{ $tenant->phone }}";
            const name = document.getElementById('name').value;
            const origin = document.getElementById('origin').value;
            const jobStatus = document.getElementById('job_status').value;
            const gender = document.getElementById('gender').value;
            const surveyDate = document.getElementById('survey_date').value;
            const surveyTime = document.getElementById('survey_time').value;
            const selectedRoom = document.getElementById('selected_room').value;

            let message = `Halo, saya tertarik dengan kost Anda`;
            if (selectedRoom) {
                message += ` (Kamar ${selectedRoom})`;
            }
            message += `.\n\n` +
                `Nama: ${name}\n` +
                `Asal: ${origin}\n` +
                `Status: ${jobStatus}\n` +
                `Jenis Kelamin: ${gender}\n` +
                `Rencana Survey: ${surveyDate} jam ${surveyTime}\n\n` +
                `Mohon infonya lebih lanjut. Terima kasih.`;

            const encodedMessage = encodeURIComponent(message);

            let phone = tenantPhone.replace(/\D/g, '');
            if (phone.startsWith('0')) {
                phone = '62' + phone.substring(1);
            }

            const whatsappUrl = `https://wa.me/${phone}?text=${encodedMessage}`;
            window.open(whatsappUrl, '_blank');
        });
    </script>
</body>

</html>
