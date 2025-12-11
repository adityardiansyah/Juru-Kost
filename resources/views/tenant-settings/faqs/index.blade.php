<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">FAQ Management</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Drag dan drop untuk mengubah urutan
                            </p>
                        </div>
                        <a href="{{ route('tenant-faqs.create') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                            Tambah FAQ
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div id="faq-list" class="space-y-4">
                        @forelse($faqs as $faq)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-move hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                data-id="{{ $faq->id }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 flex items-start">
                                        <div class="mr-3 text-gray-400 dark:text-gray-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 8h16M4 16h16" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">
                                                {{ $faq->question }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $faq->answer }}</p>
                                            <div class="mt-2">
                                                <span
                                                    class="text-sm {{ $faq->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                                    {{ $faq->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2 ml-4">
                                        <a href="{{ route('tenant-faqs.edit', $faq) }}"
                                            class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('tenant-faqs.destroy', $faq) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">Belum ada FAQ. Silakan tambahkan FAQ pertama Anda.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const faqList = document.getElementById('faq-list');

                if (faqList && faqList.children.length > 0) {
                    new Sortable(faqList, {
                        animation: 150,
                        handle: '.cursor-move',
                        ghostClass: 'opacity-50',
                        onEnd: function(evt) {
                            // Get new order
                            const items = faqList.querySelectorAll('[data-id]');
                            const orders = {};

                            items.forEach((item, index) => {
                                orders[item.dataset.id] = index;
                            });

                            // Send to server
                            fetch('{{ route('tenant-faqs.reorder') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        orders: orders
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Show success message briefly
                                        const successMsg = document.createElement('div');
                                        successMsg.className =
                                            'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                                        successMsg.textContent = 'Urutan FAQ berhasil diupdate';
                                        document.body.appendChild(successMsg);

                                        setTimeout(() => {
                                            successMsg.remove();
                                        }, 2000);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Gagal mengupdate urutan FAQ');
                                });
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
