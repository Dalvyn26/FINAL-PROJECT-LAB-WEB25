<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cuti-in') }} - Register</title>

        <!-- Modern Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            
            .animate-fade-in-left {
                animation: fadeInLeft 0.8s ease-out;
            }
            
            .animate-fade-in-up {
                animation: fadeInUp 0.8s ease-out;
            }
            
            .glass-card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            }
            
            .input-focus-glow:focus {
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1), inset 0 2px 4px rgba(0, 0, 0, 0.06);
                border-color: #4F46E5;
            }
            
            .gradient-button {
                background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
                transition: all 0.3s ease;
            }
            
            .gradient-button:hover {
                transform: scale(1.02);
                box-shadow: 0 10px 25px rgba(79, 70, 229, 0.3);
            }
            
            .gradient-button:active {
                transform: scale(0.98);
            }
            
            .floating-icon {
                animation: float 4s ease-in-out infinite;
            }
            
            .floating-icon:nth-child(2) {
                animation-delay: 1s;
            }
            
            .floating-icon:nth-child(3) {
                animation-delay: 2s;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-white to-indigo-50">
        <div class="min-h-screen w-full flex flex-col lg:flex-row overflow-hidden">
            <!-- Left Branding Section -->
            <div class="relative hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 items-center justify-center px-12 overflow-hidden animate-fade-in-left">
                <!-- Animated Background Elements -->
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-icon"></div>
                    <div class="absolute top-40 right-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-icon"></div>
                    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 floating-icon"></div>
                </div>
                
                <div class="relative text-center max-w-lg z-10">
                    <!-- Logo/Brand Icon -->
                    <div class="mb-8 animate-float">
                        <div class="w-32 h-32 mx-auto flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            <img src="{{ asset('logo/LogoCutiin.png') }}" alt="Cuti-in Logo" class="w-32 h-32">
                        </div>
                    </div>
                    
                    <h1 class="text-5xl font-bold text-white mb-6 bg-gradient-to-r from-white to-indigo-200 bg-clip-text text-transparent">
                        Cuti-in
                    </h1>
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed font-medium">
                        Platform manajemen cuti modern yang efisien, transparan, dan terintegrasi.
                    </p>
                    
                    <!-- Feature Icons -->
                    <div class="flex justify-center gap-6 mt-10">
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-indigo-500/20 rounded-xl flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-slate-400">Cepat</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-blue-500/20 rounded-xl flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-slate-400">Aman</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto bg-purple-500/20 rounded-xl flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-slate-400">Terpercaya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 sm:px-10 lg:px-16 animate-fade-in-up">
                <div class="glass-card rounded-2xl p-8 sm:p-10 max-w-md mx-auto w-full">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-500">
                        © {{ date('Y') }} Cuti-in. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
