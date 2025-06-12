<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <form action="{{ route('movies.index') }}" method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-dark-card border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-orange-500" placeholder="Search movies...">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                        Search
                    </button>
                </form>
            </div>

            <!-- Now Showing Section -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Now Showing</h2>
                        <p class="text-gray-400">Watch the latest movies in our theaters</p>
                    </div>
                    <a href="{{ route('movies.index', ['filter' => 'now_showing']) }}" class="text-orange-500 hover:text-orange-400 transition">
                        View All
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($nowShowingMovies as $movie)
                    <a href="{{ route('movies.show', $movie) }}" class="group">
                        <div class="bg-dark-card rounded-lg overflow-hidden transition-transform duration-300 group-hover:scale-105">
                            <div class="relative aspect-[2/3]">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute bottom-0 left-0 right-0 p-4">
                                        <h3 class="text-lg font-semibold text-white">{{ $movie->title }}</h3>
                                        <div class="flex items-center gap-2 text-sm text-gray-300 mt-1">
                                            <span>{{ $movie->duration }} min</span>
                                            <span>•</span>
                                            <span>{{ $movie->genre }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Coming Soon Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Coming Soon</h2>
                        <p class="text-gray-400">Get ready for these upcoming releases</p>
                    </div>
                    <a href="{{ route('movies.index', ['filter' => 'coming_soon']) }}" class="text-orange-500 hover:text-orange-400 transition">
                        View All
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($comingSoonMovies as $movie)
                    <a href="{{ route('movies.show', $movie) }}" class="group">
                        <div class="bg-dark-card rounded-lg overflow-hidden transition-transform duration-300 group-hover:scale-105">
                            <div class="relative aspect-[2/3]">
                                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute bottom-0 left-0 right-0 p-4">
                                        <h3 class="text-lg font-semibold text-white">{{ $movie->title }}</h3>
                                        <div class="flex items-center gap-2 text-sm text-gray-300 mt-1">
                                            <span>{{ $movie->duration }} min</span>
                                            <span>•</span>
                                            <span>{{ $movie->genre }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
