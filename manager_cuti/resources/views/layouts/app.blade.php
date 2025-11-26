<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }" x-cloak>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- SweetAlert2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex">
            <!-- Mobile Hamburger Menu Button -->
            <div class="fixed top-4 right-4 z-50 lg:hidden">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="p-2.5 rounded-xl bg-white border border-slate-200/60 text-slate-600 hover:text-slate-900 hover:bg-slate-50 shadow-sm hover:shadow-md transition-all duration-200"
                    aria-label="Toggle menu">
                    <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar -->
            @include('layouts.sidebar')


            <!-- Main Content -->
            <div class="lg:ml-64 flex-1 min-h-screen transition-all duration-300">
                <!-- Page Header -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-sm border-b border-slate-100 shadow-sm sticky top-0 z-30">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="py-8 px-4 sm:px-6 lg:px-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- Alpine.js script -->
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('mainLayout', () => ({
                    sidebarOpen: false,

                    init() {
                        // Close sidebar when window is resized to desktop size
                        window.addEventListener('resize', () => {
                            if (window.innerWidth >= 1024) {  // lg breakpoint
                                this.sidebarOpen = false;
                            }
                        });
                    }
                }));
            });
        </script>

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

        <!-- SweetAlert2 Configuration Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-detect flash messages (success/error)
                @if(session('success'))
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#ffffff',
                        color: '#1f2937',
                        iconColor: '#22c55e'
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#e11d48',
                        customClass: {
                            popup: 'rounded-lg shadow-lg',
                            title: 'font-semibold',
                            confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg'
                        }
                    });
                @endif

                // Global delete/action confirmation
                document.addEventListener('submit', function(e) {
                    const form = e.target;
                    const confirmDelete = form.getAttribute('data-confirm-delete');

                    if (confirmDelete) {
                        e.preventDefault(); // Prevent form submission

                        Swal.fire({
                            title: 'Apakah Anda yakin?',
                            text: "Data yang dihapus tidak bisa dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#4f46e5',
                            cancelButtonColor: '#e11d48',
                            confirmButtonText: 'Ya, Hapus!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-lg shadow-lg',
                                confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg',
                                cancelButton: 'bg-rose-600 hover:bg-rose-700 text-white font-medium py-2 px-4 rounded-lg'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Submit the form programmatically
                                form.submit();
                            }
                        });
                    }
                });
            });
        </script>

        <!-- Add x-cloak style -->
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </body>
</html>