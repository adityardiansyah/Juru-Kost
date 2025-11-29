<nav x-data="{ open: false }" class="">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="text-xl font-bold text-blue-600">🏠 Kost Management</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out
                            {{ request()->routeIs('rooms.*') || request()->routeIs('room-types.*') ? 'border-indigo-400 text-white' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Kamar
                            <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('rooms.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Data Kamar</a>
                                <a href="{{ route('room-types.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Tipe Kamar</a>
                            </div>
                        </div>
                    </div>

                    <x-nav-link :href="route('residents.index')" :active="request()->routeIs('residents.*')">
                        Penghuni
                    </x-nav-link>

                    <x-nav-link :href="route('bills.index')" :active="request()->routeIs('bills.*')">
                        Tagihan
                    </x-nav-link>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Keuangan
                            <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('finance.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Keuangan</a>
                                <a href="{{ route('finance.incomes.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pendapatan</a>
                                <a href="{{ route('finance.expenses.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengeluaran</a>
                                <div class="border-t my-1"></div>
                                <a href="{{ route('finance.income-categories.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kategori
                                    Pemasukan</a>
                                <a href="{{ route('finance.expense-categories.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Kategori
                                    Pengeluaran</a>
                                {{-- <a href="{{ route('finance.hpp') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">HPP & BEP</a> --}}
                            </div>
                        </div>
                    </div>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Lainnya
                            <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                <a href="{{ route('assets.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Aset</a>
                                <a href="{{ route('inventories.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Inventori</a>
                                <a href="{{ route('maintenance-requests.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Maintenance</a>
                                <a href="{{ route('reports.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Tenant Switcher -->
                @if (session('tenant_id'))
                    <div class="mr-4">
                        <a href="{{ route('tenant.select') }}"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                            🏠 {{ auth()->user()->tenants()->find(session('tenant_id'))->name ?? 'Pilih Kost' }}
                            <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')">
                Kamar
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('residents.index')" :active="request()->routeIs('residents.*')">
                Penghuni
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bills.index')" :active="request()->routeIs('bills.*')">
                Tagihan
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('finance.index')" :active="request()->routeIs('finance.*')">
                Keuangan
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')">
                Aset
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @if (session('tenant_id'))
                <div class="px-4 mb-3">
                    <a href="{{ route('tenant.select') }}"
                        class="block w-full text-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                        🏠 Ganti Kost
                    </a>
                </div>
            @endif

            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
