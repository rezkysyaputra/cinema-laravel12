<x-app-layout>
    <!-- Hero Section -->
    <div class="relative h-[600px] overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-4.0.3" alt="Cinema" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
        </div>
        <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
            <div class="max-w-2xl">
                <h1 class="text-5xl font-bold text-white mb-6 leading-tight">
                    Experience Movies Like Never Before
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Book your favorite movies, choose the best seats, and enjoy an unforgettable cinematic experience.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('movies.index') }}" class="bg-orange-500 text-white px-8 py-4 rounded-lg font-semibold hover:bg-orange-600 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                        Browse Movies
                    </a>
                    @auth
                    <a href="{{ route('tickets.index') }}" class="bg-white/10 text-white px-8 py-4 rounded-lg font-semibold hover:bg-white/20 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        My Tickets
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="py-16 bg-dark-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Now Showing Section -->
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-white">Now Showing</h2>
                    <a href="{{ route('movies.index', ['filter' => 'now_showing']) }}" class="text-orange-500 hover:text-orange-400 font-medium flex items-center gap-2">
                        View All
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($nowShowing as $movie)
                    <div class="group bg-dark-card rounded-xl overflow-hidden transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-orange-500/10">
                        <div class="relative">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-[400px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <a href="{{ route('movies.show', $movie) }}" class="block w-full bg-orange-500 text-white text-center py-3 rounded-lg hover:bg-orange-600 transition">
                                    Book Now
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-white mb-2 line-clamp-1">{{ $movie->title }}</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $movie->duration }} min
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                    </svg>
                                    {{ $movie->genre }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Coming Soon Section -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-white">Coming Soon</h2>
                    <a href="{{ route('movies.index', ['filter' => 'coming_soon']) }}" class="text-orange-500 hover:text-orange-400 font-medium flex items-center gap-2">
                        View All
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($comingSoon as $movie)
                    <div class="group bg-dark-card rounded-xl overflow-hidden transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-orange-500/10">
                        <div class="relative">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-[400px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                <a href="{{ route('movies.show', $movie) }}" class="block w-full bg-orange-500 text-white text-center py-3 rounded-lg hover:bg-orange-600 transition">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-white mb-2 line-clamp-1">{{ $movie->title }}</h3>
                            <div class="flex items-center gap-4 text-sm text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $movie->duration }} min
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                    </svg>
                                    {{ $movie->genre }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark-card border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo & Description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-8 h-8 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                        </svg>
                        <h2 class="text-xl font-bold text-orange-500">MovieApp</h2>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Your ultimate destination for movie tickets. Book your favorite movies, choose the best seats, and enjoy an unforgettable cinematic experience.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-400 hover:text-orange-500 transition">Home</a>
                        </li>
                        <li>
                            <a href="{{ route('movies.index') }}" class="text-gray-400 hover:text-orange-500 transition">Movies</a>
                        </li>
                        @auth
                        <li>
                            <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-orange-500 transition">My Tickets</a>
                        </li>
                        @endauth
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>support@movieapp.com</span>
                        </li>
                        <li class="flex items-center gap-2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>+1 234 567 890</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} MovieApp. All rights reserved.</p>
            </div>
        </div>
    </footer>
</x-app-layout>
