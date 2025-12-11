<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Testimonial Management</h2>
                        <a href="{{ route('tenant-testimonials.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Tambah Testimonial
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($testimonials as $testimonial)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-xl">
                                        {{ $testimonial->avatar_initial }}
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $testimonial->name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $testimonial->role }}</p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span
                                            class="{{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}">⭐</span>
                                    @endfor
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $testimonial->content }}</p>
                                <div class="flex justify-between items-center">
                                    <span
                                        class="text-sm {{ $testimonial->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $testimonial->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('tenant-testimonials.edit', $testimonial) }}"
                                            class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                        <form action="{{ route('tenant-testimonials.destroy', $testimonial) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-gray-500 text-center py-8">
                                Belum ada testimonial. Silakan tambahkan testimonial pertama Anda.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
