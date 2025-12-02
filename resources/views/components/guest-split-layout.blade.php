<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex bg-gray-100 dark:bg-gray-900">
        <!-- Left Side - Decorative -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-indigo-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-700 opacity-90"></div>
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center mix-blend-overlay">
            </div>

            <div class="relative z-10 w-full flex flex-col justify-center px-12 text-white">
                <h2 class="text-4xl font-bold mb-6">Welcome Back!</h2>
                <p class="text-lg text-indigo-100 mb-8">Manage your boarding house efficiently and effortlessly with
                    Juru Kost.</p>

                <div class="flex items-center space-x-4">
                    <div class="flex -space-x-2">
                        <img class="w-10 h-10 rounded-full border-2 border-indigo-500"
                            src="https://ui-avatars.com/api/?name=User+One&background=random" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-indigo-500"
                            src="https://ui-avatars.com/api/?name=User+Two&background=random" alt="User">
                        <img class="w-10 h-10 rounded-full border-2 border-indigo-500"
                            src="https://ui-avatars.com/api/?name=User+Three&background=random" alt="User">
                    </div>
                    <span class="text-sm font-medium">Trusted by 1000+ Owners</span>
                </div>
            </div>

            <!-- Decorative Circles -->
            <div class="absolute -bottom-24 -left-24 w-64 h-64 rounded-full bg-indigo-500 opacity-20 blur-3xl"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 rounded-full bg-purple-500 opacity-20 blur-3xl"></div>
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white dark:bg-gray-900">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:text-left">
                    <a href="/" class="inline-block mb-4">
                        <x-application-logo class="w-12 h-12 fill-current text-indigo-600" />
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sign in to your account</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Or <a href="{{ route('register') }}"
                            class="font-medium text-indigo-600 hover:text-indigo-500">start your 14-day free trial</a>
                    </p>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
