<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Package</h1>
                <p class="text-gray-600 mt-1">Kelola paket berlangganan Juru Kost</p>
            </div>
            <a href="{{ route('admin.packages.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transition">
                + Tambah Package
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Packages Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($packages as $package)
                <div
                    class="bg-white rounded-xl shadow-lg overflow-hidden border-2 {{ $package->is_active ? 'border-purple-500' : 'border-gray-300' }} relative">
                    <!-- Status Badge -->
                    <div class="absolute top-4 right-4">
                        @if ($package->is_active)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <div class="p-6">
                        <!-- Package Name & Type -->
                        <div class="mb-4">
                            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $package->name }}</h3>
                            <span
                                class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded">
                                {{ ucfirst($package->type) }}
                            </span>
                        </div>

                        <!-- Description -->
                        @if ($package->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $package->description }}</p>
                        @endif

                        <!-- Price -->
                        <div class="mb-4">
                            @if ($package->original_price && $package->original_price > $package->price)
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-lg text-gray-400 line-through">
                                        Rp {{ number_format($package->original_price, 0, ',', '.') }}
                                    </span>
                                    <span class="px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded">
                                        -{{ $package->discount_percentage }}%
                                    </span>
                                </div>
                            @endif
                            <div class="text-3xl font-black text-purple-600">
                                Rp {{ number_format($package->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Tenant Info -->
                        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Tenant Terdaftar:</span>
                                <span class="font-semibold text-gray-900">{{ $package->current_tenants }}</span>
                            </div>
                            @if ($package->max_tenants)
                                <div class="flex justify-between items-center text-sm mt-1">
                                    <span class="text-gray-600">Maksimal:</span>
                                    <span class="font-semibold text-gray-900">{{ $package->max_tenants }}</span>
                                </div>
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full transition-all"
                                            style="width: {{ $package->max_tenants > 0 ? ($package->current_tenants / $package->max_tenants) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $package->getRemainingSlots() }} slot tersisa
                                    </p>
                                </div>
                            @else
                                <p class="text-xs text-gray-500 mt-1">Unlimited</p>
                            @endif
                        </div>

                        <!-- Orders Count -->
                        <div class="mb-4 text-sm text-gray-600">
                            <span class="font-medium">{{ $package->getPaidOrdersCount() }}</span> order terbayar
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.packages.edit', $package) }}"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white text-center rounded-lg font-semibold hover:bg-blue-700 transition">
                                Edit
                            </a>

                            <form action="{{ route('admin.packages.toggle-status', $package) }}" method="POST"
                                class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full px-4 py-2 {{ $package->is_active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg font-semibold transition">
                                    {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            @if ($package->orders()->count() === 0)
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus package ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada package</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat package pertama Anda.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.packages.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                            + Tambah Package
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
