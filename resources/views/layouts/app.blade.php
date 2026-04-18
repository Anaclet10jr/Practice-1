<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rwanda Realty') — Rwanda Property Marketplace</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    {{-- Tailwind CDN for dev — replace with Vite/npm build in production --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1A56DB', hover: '#1E429F' },
                        accent:  { DEFAULT: '#E3A008', hover: '#C27803' },
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 font-[Inter] text-gray-800 antialiased">

{{-- ── Navbar ────────────────────────────────────────────────────── --}}
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">RR</span>
                </div>
                <span class="font-bold text-lg text-gray-900">Rwanda Realty</span>
            </a>

            {{-- Main nav --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('properties.index') }}" class="text-gray-600 hover:text-primary">Properties</a>
                <a href="{{ route('properties.index') }}?type=sale" class="text-gray-600 hover:text-primary">Buy</a>
                <a href="{{ route('properties.index') }}?type=rent" class="text-gray-600 hover:text-primary">Rent</a>
            </div>

            {{-- Auth --}}
            <div class="flex items-center gap-3">
                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary">
                            {{ Auth::user()->name }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border hidden group-hover:block">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Admin Dashboard</a>
                            @elseif(Auth::user()->isAgent())
                                <a href="{{ route('agent.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Agent Dashboard</a>
                                <a href="{{ route('agent.properties.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">My Properties</a>
                            @endif
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-primary">Sign in</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- ── Flash messages ───────────────────────────────────────────── --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    </div>
@endif
@if(session('warning'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg text-sm">
            {{ session('warning') }}
        </div>
    </div>
@endif
@if($errors->any())
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ── Page content ─────────────────────────────────────────────── --}}
<main>
    @yield('content')
</main>

{{-- ── Footer ───────────────────────────────────────────────────── --}}
<footer class="bg-gray-900 text-gray-400 mt-16 py-12">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <span class="text-white font-bold text-lg">Rwanda Realty</span>
            <p class="mt-2 text-sm">Rwanda's trusted property marketplace.</p>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-3">Browse</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('properties.index') }}?type=sale" class="hover:text-white">Properties for Sale</a></li>
                <li><a href="{{ route('properties.index') }}?type=rent" class="hover:text-white">Properties for Rent</a></li>
            </ul>
        </div>
        <div>
            <h3 class="text-white font-semibold mb-3">Agents</h3>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('register') }}" class="hover:text-white">List Your Property</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-white">Agent Login</a></li>
            </ul>
        </div>
    </div>
    <div class="mt-8 pt-8 border-t border-gray-800 text-center text-xs">
        © {{ date('Y') }} Rwanda Realty. Built with Laravel.
    </div>
</footer>

@stack('scripts')
</body>
</html>
