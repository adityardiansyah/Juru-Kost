<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kost: {{ $tenant->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('tenant.edit', $tenant) }}"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Info --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Informasi Kost</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm text-gray-600">Nama Kost</label>
                                    <p class="font-semibold">{{ $tenant->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Slug</label>
                                    <p class="font-semibold text-blue-600">{{ $tenant->slug }}</p>
                                </div>
                            </div>

                            @if ($tenant->description)
                                <div>
                                    <label class="text-sm text-gray-600">Deskripsi</label>
                                    <p class="text-gray-800">{{ $tenant->description }}</p>
                                </div>
                            @endif

                            @if ($tenant->address)
                                <div>
                                    <label class="text-sm text-gray-600">Alamat</label>
                                    <p class="text-gray-800">{{ $tenant->address }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                @if ($tenant->phone)
                                    <div>
                                        <label class="text-sm text-gray-600">Telepon</label>
                                        <p class="font-semibold">{{ $tenant->phone }}</p>
                                    </div>
                                @endif

                                @if ($tenant->email)
                                    <div>
                                        <label class="text-sm text-gray-600">Email</label>
                                        <p class="font-semibold">{{ $tenant->email }}</p>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">Status</label>
                                <div>
                                    <span
                                        class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $tenant->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Users/Team --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold">Tim Pengelola</h3>
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                + Tambah User
                            </button>
                        </div>

                        <div class="space-y-3">
                            @foreach ($tenant->users as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-semibold">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->pivot->role == 'owner' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $user->pivot->role == 'admin' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $user->pivot->role == 'accountant' ? 'bg-green-100 text-green-800' : '' }}">
                                        {{ ucfirst($user->pivot->role) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Sidebar Stats --}}
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Statistik</h3>

                        <div class="space-y-4">
                            <div class="border-b pb-3">
                                <label class="text-sm text-gray-600">Total Kamar</label>
                                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_rooms'] ?? 0 }}</p>
                            </div>
                            <div class="border-b pb-3">
                                <label class="text-sm text-gray-600">Penghuni Aktif</label>
                                <p class="text-2xl font-bold text-green-600">{{ $stats['active_residents'] ?? 0 }}</p>
                            </div>
                            <div class="border-b pb-3">
                                <label class="text-sm text-gray-600">Occupancy Rate</label>
                                <p class="text-2xl font-bold text-purple-600">{{ $stats['occupancy_rate'] ?? 0 }}%</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">Tagihan Tertunggak</label>
                                <p class="text-2xl font-bold text-red-600">{{ $stats['unpaid_bills'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Quick Actions</h3>

                        <div class="space-y-2">
                            <a href="{{ route('rooms.index') }}"
                                class="block w-full bg-blue-500 hover:bg-blue-700 text-white text-center font-bold py-2 px-4 rounded">
                                Kelola Kamar
                            </a>
                            <a href="{{ route('residents.index') }}"
                                class="block w-full bg-green-500 hover:bg-green-700 text-white text-center font-bold py-2 px-4 rounded">
                                Kelola Penghuni
                            </a>
                            <a href="{{ route('bills.generate') }}"
                                class="block w-full bg-yellow-500 hover:bg-yellow-700 text-white text-center font-bold py-2 px-4 rounded">
                                Generate Tagihan
                            </a>
                            <a href="{{ route('finance.index') }}"
                                class="block w-full bg-purple-500 hover:bg-purple-700 text-white text-center font-bold py-2 px-4 rounded">
                                Laporan Keuangan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
