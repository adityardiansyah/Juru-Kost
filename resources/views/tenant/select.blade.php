<x-guest-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #020307 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .gradient-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveGrid 20s linear infinite;
        }

        @keyframes moveGrid {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 1);
        }

        .tenant-card {
            position: relative;
            overflow: hidden;
        }

        .tenant-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .tenant-card:hover::before {
            left: 100%;
        }

        .role-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
        }

        .active-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.8;
            }
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all 0.3s;
        }

        .icon-location {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .icon-phone {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .empty-state {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px dashed rgba(102, 126, 234, 0.3);
            border-radius: 1.5rem;
            padding: 4rem 2rem;
        }

        .create-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .header-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .alert-error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.1);
        }
    </style>

    <div class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h1 class="header-title">
                    Pilih Kost Anda
                </h1>
                <p class="text-white text-lg font-medium opacity-90">
                    Kelola semua properti kost Anda dalam satu platform
                </p>
            </div>

            <!-- Error Alert -->
            @if (session('error'))
                <div class="alert-error max-w-2xl mx-auto">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-red-800 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Tenant Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tenants as $tenant)
                    <form action="{{ route('tenant.switch') }}" method="POST" class="h-full">
                        @csrf
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">

                        <button type="submit" class="w-full h-full text-left glass-card rounded-2xl tenant-card group">
                            <div class="p-6 h-full flex flex-col">
                                <!-- Header with Title and Status -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3
                                            class="text-2xl font-bold text-gray-900 mb-1 group-hover:text-purple-600 transition-colors">
                                            {{ $tenant->name }}
                                        </h3>
                                        @if ($tenant->is_active)
                                            <span
                                                class="active-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white">
                                                <span class="w-2 h-2 bg-white rounded-full mr-2"></span>
                                                Aktif
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <svg class="w-8 h-8 text-purple-500 group-hover:text-purple-600 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="text-gray-600 mb-6 flex-grow line-clamp-2">
                                    {{ $tenant->description ?? 'Tidak ada deskripsi tersedia untuk properti ini.' }}
                                </p>

                                <!-- Contact Info -->
                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center">
                                        <div class="icon-wrapper icon-location mr-3">
                                            <span>📍</span>
                                        </div>
                                        <span class="text-sm text-gray-700 font-medium">{{ $tenant->address }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="icon-wrapper icon-phone mr-3">
                                            <span>📞</span>
                                        </div>
                                        <span class="text-sm text-gray-700 font-medium">{{ $tenant->phone }}</span>
                                    </div>
                                </div>

                                <!-- Role Badge -->
                                <div class="pt-4 border-t border-gray-200">
                                    <span class="role-badge">
                                        {{ $tenant->role->label ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="col-span-full">
                        <div class="empty-state text-center">
                            <div class="mb-6">
                                <svg class="w-24 h-24 mx-auto text-purple-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Kost</h3>
                            <p class="text-gray-600 mb-6 text-lg">Mulai kelola properti kost Anda sekarang!</p>
                            <a href="{{ route('tenant.create') }}" class="create-btn">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Buat Kost Baru
                                </span>
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-guest-layout>
