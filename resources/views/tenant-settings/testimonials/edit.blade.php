<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                        {{ isset($tenantTestimonial) ? 'Edit Testimonial' : 'Tambah Testimonial' }}</h2>

                    <form
                        action="{{ isset($tenantTestimonial) ? route('tenant-testimonials.update', $tenantTestimonial) : route('tenant-testimonials.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($tenantTestimonial))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama</label>
                                <input type="text" name="name"
                                    value="{{ old('name', $tenantTestimonial->name ?? '') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status/Pekerjaan</label>
                                <input type="text" name="role"
                                    value="{{ old('role', $tenantTestimonial->role ?? '') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                @error('role')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <select name="rating" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                                @for ($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}"
                                        {{ old('rating', $tenantTestimonial->rating ?? 5) == $i ? 'selected' : '' }}>
                                        {{ $i }} Bintang
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Testimonial</label>
                            <textarea name="content" rows="4" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $tenantTestimonial->content ?? '') }}</textarea>
                            @error('content')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $tenantTestimonial->is_active ?? true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                {{ isset($tenantTestimonial) ? 'Update' : 'Simpan' }}
                            </button>
                            <a href="{{ route('tenant-testimonials.index') }}"
                                class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-6 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
