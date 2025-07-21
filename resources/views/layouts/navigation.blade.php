    <!-- Desktop Navigation Bar -->
    <nav class="hidden lg:block bg-dark-bg sticky top-0 z-50 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <h1 class="text-xl font-bold text-orange-500">CINETIX</h1>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-orange-500' : 'text-gray-300 hover:text-orange-500' }} px-3 py-2 text-sm font-medium transition-colors">
                        Home
                    </a>
                    <a href="{{ route('movies.index') }}" class="{{ request()->routeIs('movies.*') ? 'text-orange-500' : 'text-gray-300 hover:text-orange-500' }} px-3 py-2 text-sm font-medium transition-colors">
                        Movies
                    </a>
                    <a href="{{ route('tickets.index') }}" class="{{ request()->routeIs('tickets.*') ? 'text-orange-500' : 'text-gray-300 hover:text-orange-500' }} px-3 py-2 text-sm font-medium transition-colors">
                        My Tickets
                    </a>
                </div>

                @auth
                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-300 hover:text-orange-500 transition-colors">
                            <div class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center">
                                <span class="text-white font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-dark-card rounded-lg shadow-lg py-1 z-50">
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-orange-500">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-orange-500">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <!-- Login & Register Buttons -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-orange-500 text-white font-medium hover:bg-orange-600 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg border border-orange-500 text-orange-500 font-medium hover:bg-orange-500 hover:text-white transition">
                        Register
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation -->
    <div class="lg:hidden">
        <!-- Mobile Header -->
        <header class="px-4 py-4 bg-dark-bg border-b border-gray-800">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <h1 class="text-xl font-bold text-orange-500">CINETIX</h1>
                </a>

                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="w-8 h-8 rounded-full bg-orange-500 flex items-center justify-center">
                        <span class="text-white font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </button>

                    <!-- Mobile Profile Menu -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-dark-card rounded-lg shadow-lg py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-700">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-orange-500">
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-orange-500">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="flex items-center space-x-2">
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-lg bg-orange-500 text-white text-sm font-medium hover:bg-orange-600 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-lg border border-orange-500 text-orange-500 text-sm font-medium hover:bg-orange-500 hover:text-white transition">
                        Register
                    </a>
                </div>
                @endauth
            </div>
        </header>

        <!-- Mobile Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 bg-dark-card border-t border-gray-800 z-50">
            <div class="grid grid-cols-4 h-16">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('home') ? 'text-orange-500' : 'text-gray-400 hover:text-orange-500' }} transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs mt-1">Home</span>
                </a>
                <a href="{{ route('movies.index') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('movies.*') ? 'text-orange-500' : 'text-gray-400 hover:text-orange-500' }} transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                    </svg>
                    <span class="text-xs mt-1">Movies</span>
                </a>
                <a href="{{ route('tickets.index') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('tickets.*') ? 'text-orange-500' : 'text-gray-400 hover:text-orange-500' }} transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <span class="text-xs mt-1">Tickets</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex flex-col items-center justify-center {{ request()->routeIs('settings.index') ? 'text-orange-500' : 'text-gray-400 hover:text-orange-500' }} transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0-6v2m0 16v2m8-8h2M2 12H4m15.36-6.36l1.42 1.42M4.22 19.78l1.42-1.42m12.02 0l1.42 1.42M4.22 4.22l1.42 1.42" />
                    </svg>
                    <span class="text-xs mt-1">Settings</span>
                </a>
            </div>
        </nav>
    </div>
