@props(['movie'])

<a href="{{ route('movies.show', $movie) }}" class="group block overflow-hidden transition-all duration-300 rounded-2xl  hover:scale-105">
    <div class="relative aspect-[2/3] w-full">
        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-dark-bg/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-2xl rounded-b-none"></div>
    </div>
    <div class="p-3 sm:p-4">
        <h3 class="text-base sm:text-lg font-semibold text-white mb-1.5 sm:mb-2 line-clamp-1 group-hover:text-orange-400 transition-colors">{{ $movie->title }}</h3>
    </div>
</a>
