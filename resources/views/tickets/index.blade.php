<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <div class="sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">My Tickets</h1>
                <p class="text-sm sm:text-base text-gray-400">View and manage your movie tickets</p>
            </div>

            <!-- Filter Section -->
            <form method="GET" action="{{ route('tickets.index') }}" class="bg-dark-card rounded-xl p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex flex-wrap gap-3 sm:gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label for="status" class="block text-xs text-gray-400 mb-1">Status</label>
                        <select name="status" id="status" class="w-full bg-dark-card border border-gray-700 rounded-lg px-3 sm:px-4 py-1.5 sm:py-2 text-sm sm:text-base text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <option value="all" {{ (isset($status) && $status == 'all') ? 'selected' : '' }}>All Tickets</option>
                            <option value="paid" {{ (isset($status) && $status == 'paid') ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ (isset($status) && $status == 'pending') ? 'selected' : '' }}>Pending Payment</option>
                            <option value="upcoming" {{ (isset($status) && $status == 'upcoming') ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ (isset($status) && $status == 'past') ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="search" class="block text-xs text-gray-400 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ isset($search) ? $search : '' }}" placeholder="Search by movie title..." class="w-full bg-dark-card border border-gray-700 rounded-lg pl-8 sm:pl-10 pr-3 sm:pr-4 py-1.5 sm:py-2 text-sm sm:text-base text-white placeholder-gray-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                            <div class="absolute inset-y-0 left-0 pl-2 sm:pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 border border-transparent rounded-lg shadow-sm text-xs sm:text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Tickets List -->
            <div class="space-y-3 sm:space-y-4">
                @forelse ($tickets as $ticket)
                <div class="bg-dark-card rounded-xl overflow-hidden group hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-300">
                    <div class="flex">
                        <div class="relative w-28 sm:w-32 hidden sm:block">
                            <img src="{{ $ticket->screening->movie->poster_url }}" alt="{{ $ticket->screening->movie->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-card to-transparent opacity-60"></div>
                        </div>
                        <div class="flex-1 p-3 sm:p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white mb-1">{{ $ticket->screening->movie->title }}</h3>
                                    <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                                        @if($ticket->status === 'paid')
                                        <span class="bg-green-500/20 text-green-400 px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium">Paid</span>
                                        @elseif($ticket->status === 'pending')
                                        <span class="bg-yellow-500/20 text-yellow-400 px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium">Pending Payment</span>
                                        @php
                                        $latestPayment = $ticket->payments()->orderByDesc('created_at')->first();
                                        @endphp
                                        @if($latestPayment && $latestPayment->payment_expired_at)
                                        <span class="text-red-400 text-xs ml-2">Pay before: {{ \Carbon\Carbon::parse($latestPayment->payment_expired_at)->format('d M Y H:i') }}</span>
                                        @endif
                                        @elseif($ticket->status === 'failed')
                                        <span class="bg-red-500/20 text-red-400 px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-medium">Payment Failed</span>
                                        @endif
                                        <span class="text-gray-300 text-xs">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                                <div class="text-orange-500 font-bold text-sm sm:text-base">
                                    Rp {{ number_format($ticket->total_price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-1.5 sm:mt-2">
                                <div class="flex items-center gap-3 sm:gap-4 text-gray-400 text-xs sm:text-sm">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span>Studio {{ $ticket->screening->studio->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>{{ $ticket->seats->count() }} Seats</span>
                                    </div>
                                </div>
                                <div>
                                    @if($ticket->status === 'pending')
                                    <a href="{{ route('payment.create', $ticket) }}" class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-xs sm:text-sm">
                                        Pay Now
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @elseif($ticket->status === 'paid' && $ticket->screening->start_time > now())
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-xs sm:text-sm">
                                        View Details
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @else
                                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors text-xs sm:text-sm">
                                        View History
                                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 sm:py-12">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                    <h3 class="mt-2 text-base sm:text-lg font-medium text-white">No tickets found</h3>
                    <p class="mt-1 text-sm sm:text-base text-gray-400">Get your first ticket by booking a movie!</p>
                    <div class="mt-4 sm:mt-6">
                        <a href="{{ route('movies.index') }}" class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 border border-transparent rounded-lg shadow-sm text-xs sm:text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Browse Movies
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
            <div class="mt-6 sm:mt-8">
                {{ $tickets->links() }}
            </div>
            @endif
        </div>
    </div>

    <x-footer />

    @push('scripts')
    <!-- JS filter removed, now handled by PHP -->
    @endpush
</x-app-layout>
