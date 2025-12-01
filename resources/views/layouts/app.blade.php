<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val));
$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Juru Kost') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Top Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed w-full z-30">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-4">
                        <!-- Sidebar Toggle Button -->
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Logo -->
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">🏠 Juru Kost</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode"
                            class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>

                        <!-- Tenant Switcher -->
                        <!-- Tenant Switcher -->
                        @if (session('tenant_id'))
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="hidden sm:flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                        <span class="mr-2">🏠</span>
                                        {{ auth()->user()->tenants()->find(session('tenant_id'))->name ?? 'Pilih Kost' }}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-2 text-xs text-gray-400 dark:text-gray-500">
                                        {{ __('Switch Tenant') }}
                                    </div>

                                    @foreach (auth()->user()->tenants as $tenant)
                                        <x-dropdown-link :href="route('tenant.switch', $tenant->id)" class="flex items-center">
                                            @if ($tenant->id == session('tenant_id'))
                                                <svg class="mr-2 h-4 w-4 text-green-500" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <span class="w-6"></span>
                                            @endif
                                            {{ $tenant->name }}
                                        </x-dropdown-link>
                                    @endforeach

                                    <div class="border-t border-gray-100 dark:border-gray-600"></div>

                                    <x-dropdown-link :href="route('tenant.create')"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                                        <div class="flex items-center">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            {{ __('Tambah Tenant Baru') }}
                                        </div>
                                    </x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        @endif

                        <!-- Profile Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-200 transition">
                                    <div>{{ Auth::user()->name }}</div>
                                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    Profile
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="fixed left-0 top-16 h-[calc(100vh-4rem)] bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto transition-all duration-300 z-20">

            <!-- Navigation Links -->
            <!-- Navigation Links -->
            <nav class="p-4 space-y-1">
                @php
                    $menus = \App\Models\Menu::with('children')
                        ->root()
                        ->ordered()
                        ->active()
                        ->get()
                        ->filter(function ($menu) {
                            return $menu->isAccessibleBy(auth()->user());
                        });
                @endphp

                @foreach ($menus as $menu)
                    @if ($menu->hasChildren())
                        <!-- Dropdown Menu -->
                        <div x-data="{ open: {{ request()->routeIs($menu->children->pluck('route_name')->filter()->map(fn($r) => explode('.', $r)[0] . '.*')->all()) ? 'true' : 'false' }} }">
                            <button @click="open = !open" :title="!sidebarOpen ? '{{ $menu->name }}' : ''"
                                class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs($menu->children->pluck('route_name')->filter()->map(fn($r) => explode('.', $r)[0] . '.*')->all()) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <span class="flex items-center min-w-0">
                                    @if ($menu->icon_svg)
                                        <svg class="w-5 h-5 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $menu->icon_svg }}" />
                                        </svg>
                                    @endif
                                    <span x-show="sidebarOpen" class="whitespace-nowrap">{{ $menu->name }}</span>
                                </span>
                                <svg x-show="sidebarOpen" class="w-4 h-4 transition-transform flex-shrink-0"
                                    :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open && sidebarOpen" class="ml-4 mt-1 space-y-1">
                                @foreach ($menu->children as $child)
                                    @if ($child->isAccessibleBy(auth()->user()))
                                        <a href="{{ $child->route_name ? route($child->route_name) : '#' }}"
                                            class="block px-4 py-2 text-sm rounded-lg {{ request()->routeIs($child->route_name) ? 'text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                            {{ $child->name }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Single Menu -->
                        <a href="{{ $menu->route_name ? route($menu->route_name) : '#' }}"
                            :title="!sidebarOpen ? '{{ $menu->name }}' : ''"
                            class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs($menu->route_name) ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            @if ($menu->icon_svg)
                                <svg class="w-5 h-5 flex-shrink-0" :class="sidebarOpen ? 'mr-3' : ''" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $menu->icon_svg }}" />
                                </svg>
                            @endif
                            <span x-show="sidebarOpen" class="whitespace-nowrap">{{ $menu->name }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </aside>

        <!-- Main Content -->
        <div :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-20'" class="pt-16 transition-all duration-300">
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Alerts -->
            @if (session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert type="error">
                    {{ session('error') }}
                </x-alert>
            @endif

            @if (session('warning'))
                <x-alert type="warning">
                    {{ session('warning') }}
                </x-alert>
            @endif

            @if ($errors->any())
                <x-alert type="error">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <!-- Page Content -->
            <main>
                <div class="max-w-7xl mx-auto pl-24 pr-4 lg:pl-0 lg:pr-0">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>

</html>
