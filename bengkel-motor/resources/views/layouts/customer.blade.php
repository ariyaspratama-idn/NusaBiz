<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Portal - Bengkel Motor')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Top Navigation -->
        <nav class="bg-gradient-to-r from-purple-600 to-pink-600 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <h1 class="text-white text-xl font-bold">Bengkel Motor</h1>
                        <div class="hidden md:flex space-x-4">
                            <a href="{{ route('customer.dashboard') }}" class="text-white/90 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('customer.dashboard') ? 'bg-white/20' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('customer.vehicles.index') }}" class="text-white/90 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('customer.vehicles.*') ? 'bg-white/20' : '' }}">
                                My Vehicles
                            </a>
                            <a href="{{ route('customer.bookings.index') }}" class="text-white/90 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('customer.bookings.*') ? 'bg-white/20' : '' }}">
                                Bookings
                            </a>
                            <a href="{{ route('customer.service-history.index') }}" class="text-white/90 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('customer.service-history.*') ? 'bg-white/20' : '' }}">
                                Service History
                            </a>
                            <a href="{{ route('customer.membership-card') }}" class="text-white/90 hover:text-white px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('customer.membership-card') ? 'bg-white/20' : '' }}">
                                My Card
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-white text-sm">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg transition text-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
