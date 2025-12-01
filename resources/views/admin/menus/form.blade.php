<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $menu->exists ? __('Edit Menu') : __('Create Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}"
                        method="POST">
                        @csrf
                        @if ($menu->exists)
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Menu Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                    :value="old('name', $menu->name)" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Route Name -->
                            <div>
                                <x-input-label for="route_name" :value="__('Route Name (Optional)')" />
                                <x-text-input id="route_name" name="route_name" type="text" class="mt-1 block w-full"
                                    :value="old('route_name', $menu->route_name)" placeholder="e.g. dashboard" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty if this is a parent
                                    menu with dropdown.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('route_name')" />
                            </div>

                            <!-- Parent Menu -->
                            <div>
                                <x-input-label for="parent_id" :value="__('Parent Menu')" />
                                <select id="parent_id" name="parent_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">None (Root Menu)</option>
                                    @foreach ($parentMenus as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ old('parent_id', $menu->parent_id) == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('parent_id')" />
                            </div>

                            <!-- Icon SVG -->
                            <div>
                                <x-input-label for="icon_svg" :value="__('Icon SVG Path (Optional)')" />
                                <textarea id="icon_svg" name="icon_svg" rows="3"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    placeholder="M3 12l2-2...">{{ old('icon_svg', $menu->icon_svg) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Paste the SVG path data (d
                                    attribute) here. Only needed for root menus.</p>
                                <x-input-error class="mt-2" :messages="$errors->get('icon_svg')" />
                            </div>

                            <!-- Order -->
                            <div>
                                <x-input-label for="order" :value="__('Order')" />
                                <x-text-input id="order" name="order" type="number" class="mt-1 block w-full"
                                    :value="old('order', $menu->order ?? 0)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('order')" />
                            </div>

                            <!-- Options -->
                            <div class="flex flex-col space-y-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                        {{ old('is_active', $menu->is_active ?? true) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-600 dark:text-gray-400">{{ __('Active') }}</span>
                                </label>

                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="requires_superuser" value="1"
                                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                        {{ old('requires_superuser', $menu->requires_superuser) ? 'checked' : '' }}>
                                    <span
                                        class="ml-2 text-gray-600 dark:text-gray-400">{{ __('Requires Superuser') }}</span>
                                </label>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                                <a href="{{ route('admin.menus.index') }}"
                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
