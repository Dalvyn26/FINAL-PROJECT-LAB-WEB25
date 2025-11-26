<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cuti-in') }} - Login</title>

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
                background: #ffffff !important;
                border: 1px solid rgba(226, 232, 240, 0.8);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.05);
                position: relative;
                z-index: 10;
            }
            
            /* Prevent any overlay from blocking the form */
            #login-form-section {
                position: relative !important;
                z-index: 9999 !important;
                background-color: #ffffff !important;
            }
            
            #login-form-card {
                position: relative !important;
                z-index: 9999 !important;
                background-color: #ffffff !important;
                opacity: 1 !important;
                pointer-events: auto !important;
            }
            
            .input-focus-glow:focus {
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1), inset 0 2px 4px rgba(0, 0, 0, 0.06);
                border-color: #4F46E5;
            }
            
            /* Modern Toggle Switch */
            .remember-toggle {
                position: relative;
                display: inline-flex;
                align-items: center;
                cursor: pointer;
                user-select: none;
            }
            
            .remember-toggle input[type="checkbox"] {
                position: absolute;
                opacity: 0;
                width: 0;
                height: 0;
                pointer-events: none;
            }
            
            .toggle-switch-modern {
                position: relative;
                display: inline-block;
                width: 52px;
                height: 28px;
                margin-right: 12px;
            }
            
            .toggle-slider-modern {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
                border: 2px solid #e2e8f0;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 28px;
                box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .toggle-slider-modern:before {
                position: absolute;
                content: "";
                height: 20px;
                width: 20px;
                left: 2px;
                top: 50%;
                transform: translateY(-50%);
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border-radius: 50%;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2), 0 1px 2px rgba(0, 0, 0, 0.1);
            }
            
            .remember-toggle input:checked + .toggle-switch-modern .toggle-slider-modern {
                background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
                border-color: #4F46E5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1), inset 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .remember-toggle input:checked + .toggle-switch-modern .toggle-slider-modern:before {
                transform: translateY(-50%) translateX(24px);
                box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3), 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            .remember-toggle:hover .toggle-slider-modern {
                border-color: #cbd5e1;
                box-shadow: 0 0 0 4px rgba(148, 163, 184, 0.1), inset 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .remember-toggle input:checked:hover + .toggle-switch-modern .toggle-slider-modern {
                border-color: #6366F1;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15), inset 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            
            .remember-toggle:active .toggle-slider-modern:before {
                transform: translateY(-50%) translateX(22px) scale(0.95);
            }
            
            .remember-toggle input:checked:active + .toggle-switch-modern .toggle-slider-modern:before {
                transform: translateY(-50%) translateX(26px) scale(0.95);
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
            
            /* Force form visibility - prevent any overlay */
            #login-form-section {
                background: #ffffff !important;
                z-index: 9999 !important;
                position: relative !important;
            }
            
            #login-form-card {
                background: #ffffff !important;
                z-index: 9999 !important;
                position: relative !important;
                opacity: 1 !important;
                visibility: visible !important;
                display: block !important;
            }
            
            /* Make sure form inputs are clickable and visible */
            #login-form-card input,
            #login-form-card button,
            #login-form-card a,
            #login-form-card label,
            #login-form-card form {
                position: relative !important;
                z-index: 10000 !important;
                pointer-events: auto !important;
                opacity: 1 !important;
                visibility: visible !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white lg:bg-gradient-to-br lg:from-slate-50 lg:via-white lg:to-indigo-50">
        <div class="min-h-screen w-full flex flex-col lg:flex-row relative" style="isolation: isolate;">
            <!-- Left Branding Section -->
            <div class="relative hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 items-center justify-center px-12 animate-fade-in-left" style="position: relative; z-index: 1; overflow: hidden;">
                <!-- Animated Background Elements -->
                <div class="absolute inset-0" style="pointer-events: none; z-index: 0;">
                    <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-500/10 rounded-full filter blur-xl floating-icon"></div>
                    <div class="absolute top-40 right-10 w-72 h-72 bg-blue-500/10 rounded-full filter blur-xl floating-icon"></div>
                    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-purple-500/10 rounded-full filter blur-xl floating-icon"></div>
                </div>
                
                <div class="relative text-center max-w-lg" style="z-index: 10;">
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
            <div id="login-form-section" class="relative w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 sm:px-10 lg:px-16 animate-fade-in-up" style="background: #ffffff; position: relative; z-index: 9999 !important; isolation: isolate;">
                <div id="login-form-card" class="rounded-2xl p-8 sm:p-10 max-w-md mx-auto w-full border-2 border-slate-300 shadow-2xl" style="background: #ffffff; position: relative; z-index: 9999 !important; opacity: 1 !important;">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-slate-900 mb-2">Welcome Back</h1>
                        <p class="text-slate-600 text-base">Masuk ke akun Anda untuk melanjutkan</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full pl-12 pr-4 py-3 border-2 border-slate-300 rounded-xl focus:outline-none input-focus-glow transition-all duration-200 bg-white text-slate-900 placeholder-slate-400"
                                    placeholder="nama@email.com"
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full pl-12 pr-4 py-3 border-2 border-slate-300 rounded-xl focus:outline-none input-focus-glow transition-all duration-200 bg-white text-slate-900 placeholder-slate-400"
                                    placeholder="Masukkan password"
                                >
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="remember-toggle">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                >
                                <span class="toggle-switch-modern">
                                    <span class="toggle-slider-modern"></span>
                                </span>
                                <span class="text-sm text-slate-700 font-medium select-none">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-all duration-200 hover:underline">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <div class="pt-4">
                            <button type="submit" class="w-full gradient-button text-white py-3.5 px-4 rounded-xl font-semibold text-base shadow-lg">
                                Masuk
                            </button>
                        </div>
                    </form>
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