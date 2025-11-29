<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit User: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama *</label>
                        <input type="text" name="name" value="{{ $user->name }}" required
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password (Kosongkan jika tidak
                            diubah)</label>
                        <input type="password" name="password"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_superuser" value="1"
                                {{ $user->is_superuser ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700 font-bold">Superuser (Akses Penuh)</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Superuser dapat mengakses semua data dan semua kost
                            tanpa batasan.</p>
                    </div>

                    <div class="mb-4 border-t pt-4">
                        <h3 class="text-lg font-bold mb-2">Akses Kost</h3>
                        <div id="tenant-access-container">
                            @foreach ($user->tenants as $index => $userTenant)
                                <div class="flex gap-4 mb-2 tenant-row">
                                    <select name="tenant_access[{{ $index }}][tenant_id]"
                                        class="shadow border rounded w-1/2 py-2 px-3 text-gray-700">
                                        <option value="">-- Pilih Kost --</option>
                                        @foreach ($tenants as $tenant)
                                            <option value="{{ $tenant->id }}"
                                                {{ $tenant->id == $userTenant->id ? 'selected' : '' }}>
                                                {{ $tenant->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="tenant_access[{{ $index }}][role_id]"
                                        class="shadow border rounded w-1/2 py-2 px-3 text-gray-700">
                                        <option value="">-- Pilih Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $role->id == $userTenant->pivot->role_id ? 'selected' : '' }}>
                                                {{ $role->label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="text-red-500 hover:text-red-700">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addTenantRow()"
                            class="text-sm text-blue-500 hover:text-blue-700 mt-2">+ Tambah Akses Kost Lain</button>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let rowCount = {{ $user->tenants->count() + 1 }};

        function addTenantRow() {
            const container = document.getElementById('tenant-access-container');
            const row = document.createElement('div');
            row.className = 'flex gap-4 mb-2 tenant-row';
            row.innerHTML = `
                <select name="tenant_access[${rowCount}][tenant_id]" class="shadow border rounded w-1/2 py-2 px-3 text-gray-700">
                    <option value="">-- Pilih Kost --</option>
                    @foreach ($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                    @endforeach
                </select>
                <select name="tenant_access[${rowCount}][role_id]" class="shadow border rounded w-1/2 py-2 px-3 text-gray-700">
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->label }}</option>
                    @endforeach
                </select>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">Hapus</button>
            `;
            container.appendChild(row);
            rowCount++;
        }
    </script>
</x-app-layout>
