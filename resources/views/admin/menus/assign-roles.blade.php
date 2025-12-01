<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Assign Roles to Menu') }}: {{ $menu->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('admin.menus.update-roles', $menu) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Select roles that should have access to the <strong>{{ $menu->name }}</strong> menu.
                                @if ($menu->requires_superuser)
                                    <br><span class="text-purple-600 dark:text-purple-400 font-bold">Note: This menu is
                                        marked as Superuser Only. Role assignments will be ignored for
                                        non-superusers.</span>
                                @endif
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($roles as $role)
                                    <label
                                        class="relative flex items-start p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors {{ in_array($role->id, $assignedRoleIds) ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700' }}">
                                        <div class="min-w-0 flex-1 text-sm">
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $role->label }}
                                            </div>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                {{ $role->name }}
                                            </p>
                                        </div>
                                        <div class="ml-3 flex items-center h-5">
                                            <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                {{ in_array($role->id, $assignedRoleIds) ? 'checked' : '' }}>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Assignments') }}</x-primary-button>
                            <a href="{{ route('admin.menus.index') }}"
                                class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">{{ __('Cancel') }}</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
