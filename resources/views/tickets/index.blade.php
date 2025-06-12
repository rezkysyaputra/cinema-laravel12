<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">My Tickets</h1>
                <p class="text-gray-400">View and manage your movie tickets</p>
            </div>

            <!-- Simple Filter Section -->
            <div class="bg-dark-card rounded-xl p-4 mb-8">
                <div class="flex flex-wrap gap-4 items-center">
                    <div class="flex-1 min-w-[200px]">
                        <select id="status" class="w-full bg-dark-card border border-gray-700 rounded-lg px-4 py-2 text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <option value="all">All Tickets</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="past">Past</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <div class="relative">
                            <input type="text" id="search" placeholder="Search by movie title..." class="w-full bg-dark-card border border-gray-700 rounded-lg pl-10 pr-4 py-2 text-white placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tickets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($tickets as $ticket)
                <div class="bg-dark-card rounded-xl overflow-hidden group hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-300">
                    <div class="relative">
                        <img src="{{ $ticket->screening->movie->poster_url }}" alt="{{ $ticket->screening->movie->title }}" class="w-full h-56 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-card to-transparent opacity-60"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $ticket->screening->movie->title }}</h3>
                            <div class="flex items-center gap-2">
                                @if($ticket->screening->start_time > now())
                                <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">Upcoming</span>
                                @else
                                <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-sm font-medium">Past</span>
                                @endif
                                <span class="text-gray-300 text-sm">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>Studio {{ $ticket->screening->studio->name }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $ticket->seats->count() }} Seats</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                <div class="text-orange-500 font-bold text-lg">
                                    Rp {{ number_format($ticket->total_price, 0, ',', '.') }}
                                </div>
                                <div>
                                    @if($ticket->screening->start_time > now())
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                                        View Details
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @else
                                    <button class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                        View History
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-white">No tickets found</h3>
                        <p class="mt-1 text-gray-400">Get your first ticket by booking a movie!</p>
                        <div class="mt-6">
                            <a href="{{ route('movies.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Browse Movies
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
            <div class="mt-8">
                {{ $tickets->links() }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const searchInput = document.getElementById('search');

            function filterTickets() {
                const status = statusSelect.value;
                const search = searchInput.value.toLowerCase();
                const tickets = document.querySelectorAll('.ticket-card');

                tickets.forEach(ticket => {
                    const movieTitle = ticket.querySelector('h3').textContent.toLowerCase();
                    const ticketStatus = ticket.querySelector('.status-badge').textContent.toLowerCase();

                    const matchesStatus = status === 'all' || ticketStatus.includes(status);
                    const matchesSearch = movieTitle.includes(search);

                    ticket.style.display = matchesStatus && matchesSearch ? 'block' : 'none';
                });
            }

            statusSelect.addEventListener('change', filterTickets);
            searchInput.addEventListener('input', filterTickets);
        });

    </script>
    @endpush
</x-app-layout>
