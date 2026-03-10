@extends('layouts.guest')

@section('title', 'Login - Bengkel Motor')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 gradient-bg">
    <div class="w-full max-w-md">
        <!-- Logo/Brand -->
        <div class="text-center mb-8">
            <div class="inline-block p-4 bg-white/10 backdrop-blur-lg rounded-2xl mb-4">
                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Bengkel Motor</h1>
            <p class="text-white/80">Sistem Manajemen Bengkel</p>
        </div>

        <!-- Login Card -->
        <div class="glass-effect rounded-3xl p-8 shadow-2xl">
            <h2 class="text-2xl font-bold text-white mb-6">Masuk ke Akun Anda</h2>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-xl text-green-100 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-white/90 text-sm font-medium mb-2">Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="nama@email.com"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-white/90 text-sm font-medium mb-2">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 bg-white/10 border-white/20 rounded focus:ring-2 focus:ring-purple-500"
                        >
                        <span class="ml-2 text-sm text-white/80">Ingat saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200"
                >
                    Masuk
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-white/70 text-sm">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="text-purple-300 hover:text-purple-200 font-semibold transition">
                        Daftar Sekarang
                    </a>
                </p>
            </div>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-6 glass-effect rounded-2xl p-4">
            <p class="text-white/60 text-xs text-center mb-2">Demo Login:</p>
            <div class="grid grid-cols-3 gap-2 text-xs">
                <div class="text-center">
                    <p class="text-white/80 font-semibold">Admin</p>
                    <p class="text-white/60">admin@bengkel.com</p>
                </div>
                <div class="text-center">
                    <p class="text-white/80 font-semibold">Mekanik</p>
                    <p class="text-white/60">budi@bengkel.com</p>
                </div>
                <div class="text-center">
                    <p class="text-white/80 font-semibold">Customer</p>
                    <p class="text-white/60">john@customer.com</p>
                </div>
            </div>
            <p class="text-white/60 text-xs text-center mt-2">Password: <span class="text-white/80 font-mono">password</span></p>
        </div>
    </div>
</div>
@endsection
