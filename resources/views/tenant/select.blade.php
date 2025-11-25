<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Pilih Kost yang Ingin Dikelola
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Anda dapat mengelola beberapa kost sekaligus
                </p>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tenants as $tenant)
                    <form action="{{ route('tenant.switch') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                        
                        <button type="submit" class="w-full bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-shadow duration-300">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $tenant->name }}
                                    </h3>
                                    @if($tenant->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">
                                            Aktif
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-4">
                                    {{ $tenant->description ?? 'Tidak ada deskripsi' }}
                                </p>
                                
                                <div class="text-sm text-gray-500">
                                    <p>📍 {{ $tenant->address }}</p>
                                    <p>📞 {{ $tenant->phone }}</p>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <span class="text-sm font-medium text-blue-600">
                                        Role: {{ ucfirst($tenant->pivot->role) }}
                                    </span>
                                </div>
                            </div>
                        </button>
                    </form>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-600 mb-4">Anda belum memiliki kost</p>
                        <a href="{{ route('tenants.create') }}" class="text-blue-600 hover:text-blue-800">
                            Buat Kost Baru →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-guest-layout>