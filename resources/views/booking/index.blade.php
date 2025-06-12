<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark-card rounded-xl p-6">
                <h1 class="text-2xl font-bold text-white mb-6">Book Tickets</h1>

                <!-- Movies Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @forelse($movies as $movie)
                    <div class="bg-gray-800 rounded-xl overflow-hidden">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-[400px] object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-white mb-2">{{ $movie->title }}</h3>
                            <p class="text-gray-400 text-sm mb-4">{{ $movie->duration }} min | {{ $movie->genre }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-orange-500">{{ $movie->release_date->format('M d, Y') }}</span>
                                <a href="{{ route('booking.create', $movie) }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-400 text-lg">No movies available for booking at the moment.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $movies->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
