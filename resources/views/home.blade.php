<x-app-layout>
    <!-- Hero Section -->
    <div class="relative h-[500px] sm:h-[600px] overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1536440136628-849c177e76a1?ixlib=rb-4.0.3" alt="Cinema" class="w-full h-full object-cover scale-105 blur-[2px] brightness-75">
            <div class="absolute inset-0 bg-gradient-to-t from-dark-bg via-dark-bg/60 to-transparent"></div>
        </div>
        <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-center">
            <div class="max-w-xl sm:max-w-2xl w-full text-center mx-auto">
                <div class="inline-block bg-orange-500/10 text-orange-400 px-3 py-1 text-xs sm:px-4 sm:py-1.5 sm:text-sm font-medium mb-4 sm:mb-6 backdrop-blur-sm">
                    Welcome to CINETIX
                </div>
                <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold text-white mb-4 sm:mb-6 leading-tight tracking-tight">
                    Experience Movies Like <span class="text-orange-500">Never Before</span>
                </h1>
                <p class="text-base sm:text-xl text-gray-300 mb-6 sm:mb-8 leading-relaxed">
                    Book your favorite movies, choose the best seats, and enjoy an unforgettable cinematic experience with premium comfort and sound.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <a href="{{ route('movies.index') }}" class="bg-orange-500 text-white px-6 py-3 sm:px-8 sm:py-4 rounded-xl font-semibold hover:bg-orange-600 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                        </svg>
                        Browse Movies
                    </a>
                    @auth
                    <a href="{{ route('tickets.index') }}" class="bg-white/10 text-white px-6 py-3 sm:px-8 sm:py-4 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 backdrop-blur-sm text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        My Tickets
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 sm:py-16 bg-dark-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Now Showing Section -->
            <div class="mb-8 sm:mb-16">
                <div class="flex items-center justify-between mb-6 sm:mb-8">
                    <div>
                        <h2 class="text-xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">Now Showing</h2>
                        <p class="text-sm sm:text-base text-gray-400 hidden sm:block">Watch the latest blockbuster movies in our premium theaters</p>
                    </div>
                    <a href="{{ route('movies.index', ['filter' => 'now_showing']) }}" class="text-orange-500 hover:text-orange-400 font-medium flex items-center gap-1.5 sm:gap-2 group text-sm sm:text-base">
                        View All
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($nowShowing as $movie)
                    <x-movie-card :movie="$movie" />
                    @endforeach
                </div>
            </div>

            <!-- Coming Soon Section -->
            <div>
                <div class="flex items-center justify-between mb-6 sm:mb-8">
                    <div>
                        <h2 class="text-xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">Coming Soon</h2>
                        <p class="text-sm sm:text-base text-gray-400 hidden sm:block">Get ready for these upcoming blockbuster movies</p>
                    </div>
                    <a href="{{ route('movies.index', ['filter' => 'coming_soon']) }}" class="text-orange-500 hover:text-orange-400 font-medium flex items-center gap-1.5 sm:gap-2 group text-sm sm:text-base">
                        View All
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($comingSoon as $movie)
                    <x-movie-card :movie="$movie" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <x-footer />
</x-app-layout>
