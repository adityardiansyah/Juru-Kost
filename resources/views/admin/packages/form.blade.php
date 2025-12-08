<!-- Error Messages -->
@if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="space-y-6">
    <!-- Package Name -->
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
            Nama Package <span class="text-red-500">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name', $package->name ?? '') }}" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="e.g., Akses Lifetime">
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
            Deskripsi
        </label>
        <textarea name="description" id="description" rows="3"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="Deskripsi singkat tentang package">{{ old('description', $package->description ?? '') }}</textarea>
    </div>

    <!-- Type -->
    <div>
        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
            Tipe Package <span class="text-red-500">*</span>
        </label>
        <select name="type" id="type" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            <option value="lifetime" {{ old('type', $package->type ?? '') == 'lifetime' ? 'selected' : '' }}>
                Lifetime
            </option>
            <option value="monthly" {{ old('type', $package->type ?? '') == 'monthly' ? 'selected' : '' }}>
                Monthly
            </option>
            <option value="yearly" {{ old('type', $package->type ?? '') == 'yearly' ? 'selected' : '' }}>
                Yearly
            </option>
        </select>
    </div>

    <!-- Price & Original Price -->
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                Harga <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-gray-500">Rp</span>
                <input type="number" name="price" id="price" value="{{ old('price', $package->price ?? '') }}"
                    required min="0" step="1000"
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    placeholder="299000">
            </div>
        </div>

        <div>
            <label for="original_price" class="block text-sm font-semibold text-gray-700 mb-2">
                Harga Normal (Opsional)
            </label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-gray-500">Rp</span>
                <input type="number" name="original_price" id="original_price"
                    value="{{ old('original_price', $package->original_price ?? '') }}" min="0" step="1000"
                    class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    placeholder="2499000">
            </div>
            <p class="mt-1 text-sm text-gray-500">Untuk menampilkan diskon</p>
        </div>
    </div>

    <!-- Max Tenants -->
    <div>
        <label for="max_tenants" class="block text-sm font-semibold text-gray-700 mb-2">
            Maksimal Tenant (Opsional)
        </label>
        <input type="number" name="max_tenants" id="max_tenants"
            value="{{ old('max_tenants', $package->max_tenants ?? '') }}" min="1"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="100">
        <p class="mt-1 text-sm text-gray-500">Kosongkan untuk unlimited. Package akan otomatis nonaktif saat mencapai
            limit.</p>
    </div>

    <!-- Sort Order -->
    <div>
        <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">
            Urutan Tampilan
        </label>
        <input type="number" name="sort_order" id="sort_order"
            value="{{ old('sort_order', $package->sort_order ?? 0) }}" min="0"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
            placeholder="0">
        <p class="mt-1 text-sm text-gray-500">Angka lebih kecil akan ditampilkan lebih dulu</p>
    </div>

    <!-- Features -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Fitur Utama
        </label>
        <div id="features-container" class="space-y-2">
            @php
                $features = old('features', $package->features ?? ['']);
                if (empty($features)) {
                    $features = [''];
                }
            @endphp
            @foreach ($features as $index => $feature)
                <div class="flex gap-2">
                    <input type="text" name="features[]" value="{{ $feature }}"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="e.g., Manajemen Penghuni Unlimited">
                    <button type="button" onclick="this.parentElement.remove()"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Hapus
                    </button>
                </div>
            @endforeach
        </div>
        <button type="button" onclick="addFeature()"
            class="mt-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition">
            + Tambah Fitur
        </button>
    </div>

    <!-- Bonus Features -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Bonus Eksklusif
        </label>
        <div id="bonus-features-container" class="space-y-2">
            @php
                $bonusFeatures = old('bonus_features', $package->bonus_features ?? ['']);
                if (empty($bonusFeatures)) {
                    $bonusFeatures = [''];
                }
            @endphp
            @foreach ($bonusFeatures as $index => $feature)
                <div class="flex gap-2">
                    <input type="text" name="bonus_features[]" value="{{ $feature }}"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="e.g., Update Fitur Selamanya">
                    <button type="button" onclick="this.parentElement.remove()"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Hapus
                    </button>
                </div>
            @endforeach
        </div>
        <button type="button" onclick="addBonusFeature()"
            class="mt-2 px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition">
            + Tambah Bonus
        </button>
    </div>

    <!-- Is Active -->
    <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1"
            {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}
            class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
        <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">
            Aktifkan package ini
        </label>
    </div>
</div>

<script>
    function addFeature() {
        const container = document.getElementById('features-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" name="features[]"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                placeholder="e.g., Manajemen Penghuni Unlimited">
            <button type="button" onclick="this.parentElement.remove()"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                Hapus
            </button>
        `;
        container.appendChild(div);
    }

    function addBonusFeature() {
        const container = document.getElementById('bonus-features-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <input type="text" name="bonus_features[]"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                placeholder="e.g., Update Fitur Selamanya">
            <button type="button" onclick="this.parentElement.remove()"
                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                Hapus
            </button>
        `;
        container.appendChild(div);
    }
</script>
