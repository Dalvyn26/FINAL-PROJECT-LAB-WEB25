<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ mobileMenuOpen: false }" x-cloak>
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
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Mobile Menu Button -->
            <div class="fixed top-4 left-4 z-40 md:hidden">
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50"
                    aria-label="Toggle menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Mobile Sidebar Backdrop -->
            <div
                x-show="mobileMenuOpen"
                @click="mobileMenuOpen = false"
                class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden"
                x-transition:enter="ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
            </div>

            <!-- Main Content -->
            <div class="md:ml-64">
                <!-- Page Header -->
                @isset($header)
                    <header class="bg-white border-b border-slate-200 shadow-sm">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Alpine.js script -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('mobileMenu', () => ({
                    open: false,

                    init() {
                        // Close sidebar when window is resized to desktop size
                        window.addEventListener('resize', () => {
                            if (window.innerWidth >= 768) {
                                this.open = false;
                            }
                        });
                    }
                }));
            });
        </script>

        <!-- Add x-cloak style -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>
