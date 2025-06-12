<x-app-layout>
    <!-- Breadcrumb -->
    <div class="bg-dark-card border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-gray-400 hover:text-orange-500 transition">Home</a>
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('movies.index') }}" class="text-gray-400 hover:text-orange-500 transition">Movies</a>
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-orange-500">{{ $movie->title }}</span>
            </div>
        </div>
    </div>

    <!-- Movie Details -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-dark-card rounded-xl overflow-hidden shadow-xl">
                <!-- Movie Header -->
                <div class="relative h-[400px]">
                    <div class="absolute inset-0">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-card via-dark-card/80 to-transparent"></div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="flex flex-col md:flex-row gap-8">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-48 h-72 object-cover rounded-lg shadow-2xl">
                            <div class="flex-1">
                                <h1 class="text-4xl font-bold text-white mb-4">{{ $movie->title }}</h1>
                                <div class="flex items-center gap-6 text-gray-300 mb-6">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $movie->duration }} menit
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                        </svg>
                                        {{ $movie->genre }}
                                    </span>
                                </div>
                                <p class="text-gray-400 text-lg leading-relaxed">{{ $movie->synopsis }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Screening Schedule -->
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-white mb-6">Jadwal Tayang</h2>
                    @if($movie->screenings->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($movie->screenings as $screening)
                        <div class="bg-dark-bg rounded-xl p-6 border border-gray-800 hover:border-orange-500/50 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-white font-medium">{{ $screening->studio->name }}</span>
                                </div>
                                <span class="text-orange-500 font-medium">Rp {{ number_format($screening->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-gray-400 mb-6">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($screening->start_time)->format('d M Y, H:i') }}</span>
                            </div>
                            <a href="{{ route('booking.create', $screening) }}" class="block w-full bg-orange-500 text-white text-center py-3 rounded-lg hover:bg-orange-600 transition">
                                Pesan Tiket
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-400 text-lg">Tidak ada jadwal tayang tersedia.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
