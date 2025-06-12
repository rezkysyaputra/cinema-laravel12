<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                Tickets
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2">Ticket Details</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Movie Information -->
                <div class="lg:col-span-2">
                    <div class="bg-dark-card rounded-xl overflow-hidden">
                        <div class="relative">
                            <img src="{{ $ticket->screening->movie->poster_url }}" alt="{{ $ticket->screening->movie->title }}" class="w-full h-[400px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <h1 class="text-3xl font-bold text-white mb-2">{{ $ticket->screening->movie->title }}</h1>
                                <div class="flex items-center gap-4 text-gray-300">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $ticket->screening->movie->duration }} min
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                                        </svg>
                                        {{ $ticket->screening->movie->genre }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-dark-card/50 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-white mb-4">Screening Details</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span>Studio {{ $ticket->screening->studio->name }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-dark-card/50 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-white mb-4">Seat Information</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>{{ $ticket->seats->count() }} Seats</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($ticket->seats as $seat)
                                            <span class="bg-orange-500/20 text-orange-500 px-3 py-1 rounded-full text-sm">
                                                {{ $seat->row_letter }}{{ $seat->seat_number }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Individual Tickets Section -->
                            <div class="bg-dark-card/50 rounded-lg p-4 mb-6">
                                <h3 class="text-lg font-semibold text-white mb-4">Individual Tickets</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($ticket->seats as $seat)
                                    <div class="bg-dark-card rounded-lg p-4 border border-gray-700">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="text-white font-medium">Seat {{ $seat->row_letter }}{{ $seat->seat_number }}</h4>
                                                <p class="text-gray-400 text-sm">{{ $ticket->screening->movie->title }}</p>
                                            </div>
                                            <div class="flex gap-2">
                                                <button onclick="viewTicket('{{ $ticket->id }}', '{{ $seat->id }}')" class="text-gray-400 hover:text-white">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </button>
                                                <button onclick="downloadTicket('{{ $ticket->id }}', '{{ $seat->id }}')" class="text-gray-400 hover:text-white">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex justify-center">
                                            <div id="barcode-{{ $seat->id }}" data-ticket-barcode="{{ $ticket->id }}-{{ $seat->id }}" data-barcode-id="{{ $seat->id }}" class="w-full max-w-[200px]"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="bg-dark-card/50 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-white mb-4">Movie Synopsis</h3>
                                <p class="text-gray-400">{{ $ticket->screening->movie->synopsis }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="lg:col-span-1">
                    <div class="bg-dark-card rounded-xl p-6 sticky top-6">
                        <h2 class="text-xl font-bold text-white mb-6">Payment Information</h2>

                        <!-- Dummy Payment Status -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400">Status</span>
                                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-medium">Paid</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400">Payment Method</span>
                                <span class="text-white">Credit Card</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Payment Date</span>
                                <span class="text-white">{{ \Carbon\Carbon::now()->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="border-t border-gray-700 pt-6 mb-6">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Ticket Price</span>
                                    <span class="text-white">Rp {{ number_format($ticket->screening->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Quantity</span>
                                    <span class="text-white">{{ $ticket->seats->count() }}x</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400">Service Fee</span>
                                    <span class="text-white">Rp 5.000</span>
                                </div>
                                <div class="flex items-center justify-between font-semibold text-lg pt-3 border-t border-gray-700">
                                    <span class="text-white">Total</span>
                                    <span class="text-orange-500">Rp {{ number_format($ticket->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="space-y-3">
                            @if($ticket->screening->start_time > now())
                            <button onclick="downloadAllTickets()" class="block w-full bg-orange-500 text-white text-center px-4 py-3 rounded-lg hover:bg-orange-600 transition-colors">
                                Download All Tickets
                            </button>
                            <button class="block w-full bg-gray-700 text-white px-4 py-3 rounded-lg hover:bg-gray-600 transition-colors">
                                Share Tickets
                            </button>
                            @else
                            <button class="block w-full bg-gray-700 text-white px-4 py-3 rounded-lg hover:bg-gray-600 transition-colors">
                                View History
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Modal -->
    <div id="ticketModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-dark-card rounded-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Ticket</h3>
                <button onclick="closeTicketModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="ticketModalContent" class="space-y-4">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        // Initialize barcodes for each seat
        document.addEventListener('DOMContentLoaded', function() {
            const tickets = document.querySelectorAll('[data-ticket-barcode]');
            tickets.forEach(ticket => {
                const barcodeData = ticket.dataset.ticketBarcode;
                const barcodeId = ticket.dataset.barcodeId;
                JsBarcode(`#barcode-${barcodeId}`, barcodeData, {
                    format: "CODE128"
                    , width: 2
                    , height: 100
                    , displayValue: false
                });
            });
        });

        // View individual ticket
        function viewTicket(ticketId, seatId) {
            const modal = document.getElementById('ticketModal');
            const content = document.getElementById('ticketModalContent');

            // Here you would typically fetch the ticket details from your backend
            // For now, we'll just show the barcode
            content.innerHTML = `
                <div class="bg-dark-card rounded-lg p-4 border border-gray-700">
                    <div class="flex justify-center mb-4">
                        <div id="modal-barcode" class="w-full max-w-[200px]"></div>
                    </div>
                    <div class="text-center text-gray-400">
                        <p>Ticket ID: ${ticketId}-${seatId}</p>
                        <p class="mt-2">Scan this barcode at the cinema</p>
                    </div>
                </div>
            `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Initialize barcode in modal
            JsBarcode("#modal-barcode", `${ticketId}-${seatId}`, {
                format: "CODE128"
                , width: 2
                , height: 100
                , displayValue: false
            });
        }

        // Close ticket modal
        function closeTicketModal() {
            const modal = document.getElementById('ticketModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Download individual ticket
        function downloadTicket(ticketId, seatId) {
            // Here you would implement the download functionality
            // This could be a PDF generation or image download
            console.log(`Downloading ticket ${ticketId} for seat ${seatId}`);
        }

        // Download all tickets
        function downloadAllTickets() {
            // Here you would implement the download all functionality
            console.log('Downloading all tickets');
        }

    </script>
    @endpush
</x-app-layout>
