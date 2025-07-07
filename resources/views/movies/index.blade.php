<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter and Search Bar -->
            <div class="bg-dark-card rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
                <form action="{{ route('movies.index') }}" method="GET" class="flex flex-wrap gap-3 sm:gap-4 items-center">
                    <div class="flex-1 min-w-[150px]">
                        <select name="filter" id="filter" class="w-full bg-dark-card border border-gray-700 rounded-lg px-3 sm:px-4 py-1.5 sm:py-2 text-sm sm:text-base text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>All Movies</option>
                            <option value="now_showing" {{ request('filter') == 'now_showing' ? 'selected' : '' }}>Now Showing</option>
                            <option value="coming_soon" {{ request('filter') == 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" class="w-full bg-dark-card border border-gray-700 rounded-lg pl-8 sm:pl-10 pr-3 sm:pr-4 py-1.5 sm:py-2 text-sm sm:text-base text-white placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500" placeholder="Search movies...">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm sm:text-base">
                        Apply Filters
                    </button>
                </form>
            </div>

            <!-- Movies List -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                @forelse($movies as $movie)
                <a href="{{ route('movies.show', $movie) }}" class="group">
                    <div class="bg-dark-card rounded-xl overflow-hidden transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-orange-500/10">
                        <div class="relative aspect-[2/3]">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 sm:p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <h3 class="text-sm sm:text-lg font-semibold text-white mb-1 line-clamp-1">{{ $movie->title }}</h3>
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-sm text-gray-300">
                                    <span>{{ $movie->duration }} min</span>
                                    <span>•</span>
                                    <span>{{ $movie->genre }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-8">
                    <h3 class="text-xl font-medium text-white mb-2">No movies found</h3>
                    <p class="text-gray-400">Try adjusting your filters or search terms.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($movies->hasPages())
            <div class="mt-8">
                {{ $movies->appends(request()->except('page'))->links() }}
            </div>
            @endif
        </div>
    </div>

    <x-footer />
</x-app-layout>
