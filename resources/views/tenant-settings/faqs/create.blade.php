<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
                        {{ isset($tenantFaq) ? 'Edit FAQ' : 'Tambah FAQ' }}</h2>

                    <form
                        action="{{ isset($tenantFaq) ? route('tenant-faqs.update', $tenantFaq) : route('tenant-faqs.store') }}"
                        method="POST">
                        @csrf
                        @if (isset($tenantFaq))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pertanyaan</label>
                            <input type="text" name="question"
                                value="{{ old('question', $tenantFaq->question ?? '') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('question')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jawaban</label>
                            <textarea name="answer" rows="4" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('answer', $tenantFaq->answer ?? '') }}</textarea>
                            @error('answer')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $tenantFaq->is_active ?? true) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                            </label>
                        </div>

                        <div class="flex space-x-4">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                                {{ isset($tenantFaq) ? 'Update' : 'Simpan' }}
                            </button>
                            <a href="{{ route('tenant-faqs.index') }}"
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
