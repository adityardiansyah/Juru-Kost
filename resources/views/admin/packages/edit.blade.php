<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.packages.index') }}"
                class="text-purple-600 hover:text-purple-700 font-medium mb-2 inline-block">
                ← Kembali ke Daftar Package
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Package</h1>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.packages.update', $package) }}" method="POST"
            class="bg-white rounded-xl shadow-lg p-8">
            @csrf
            @method('PUT')

            @include('admin.packages.form')

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transition">
                    Update Package
                </button>
                <a href="{{ route('admin.packages.index') }}"
                    class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
