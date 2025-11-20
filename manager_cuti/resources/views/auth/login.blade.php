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
    <body class="font-sans antialiased">
        <div class="min-h-screen w-full flex flex-col lg:flex-row">
            <!-- Left Branding Section -->
            <div class="hidden lg:flex lg:w-1/2 bg-slate-900 items-center justify-center px-12">
                <div class="text-center max-w-lg">
                    <h1 class="text-4xl font-bold text-white mb-6">Sistem Manajemen Cuti</h1>
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed">
                        Platform manajemen cuti profesional untuk perusahaan modern. 
                        Pengelolaan cuti yang efisien dan terintegrasi untuk semua level organisasi.
                    </p>
                    <div class="w-24 h-24 mx-auto bg-indigo-700 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <p class="text-slate-400 mt-6 text-base">
                        Solusi digital untuk manajemen cuti karyawan yang efektif dan transparan
                    </p>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="w-full lg:w-1/2 bg-white flex flex-col justify-center px-6 py-12 sm:px-10">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-slate-800">Welcome Back</h1>
                    <p class="text-slate-600 mt-2">Please sign in to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="Enter your email"
                        >
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        >
                        <label for="remember_me" class="ms-2 block text-sm text-slate-600">
                            Remember me
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div class="mt-8">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-semibold transition duration-300 ease-in-out transform hover:scale-[1.02]">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-slate-600 text-sm">
                        © {{ date('Y') }} Sistem Manajemen Cuti. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>