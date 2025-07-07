<x-app-layout>
    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark-card rounded-xl p-8">
                <h1 class="text-2xl font-bold text-white mb-6">Your Ticket(s)</h1>
                <div class="mb-8">
                    <div class="mb-2">
                        <span class="text-gray-400">Movie</span>
                        <span class="text-white font-semibold ml-2">{{ $ticket->screening->movie->title }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-400">Studio</span>
                        <span class="text-white ml-2">{{ $ticket->screening->studio->name }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-gray-400">Date & Time</span>
                        <span class="text-white ml-2">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="space-y-6">
                    @foreach($ticket->tickets as $ticketSeat)
                    <div class="bg-dark-card/70 rounded-lg p-4 flex flex-col items-center shadow-md border border-gray-700">
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="text-white font-semibold text-lg">Seat: {{ $ticketSeat->seat->row_letter }}{{ $ticketSeat->seat->seat_number }}</div>
                            <a href="{{ secure_url('tickets/'.$ticketSeat->id.'/download') }}" class="text-orange-500 underline" title="Download Ticket">
                                Download
                            </a>
                        </div>
                        <div class="flex flex-col items-center w-full">
                            <div class="mb-2">{!! $qrCodes[$ticketSeat->id] ?? '' !!}</div>
                            <div class="mt-1 text-xs text-gray-400">Ticket Code: {{ $ticketSeat->ticket_code }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-8">
                    <a href="{{ secure_url('tickets/booking/'.$ticket->id.'/download') }}" class="block w-full bg-orange-500 text-white text-center px-4 py-3 rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                        Download All Tickets
                    </a>
                </div>
                <div class="mt-12 bg-dark-card/80 rounded-lg p-6 border border-gray-700">
                    <h2 class="text-xl font-bold text-white mb-4">How to Use Your Ticket</h2>
                    <ol class="list-decimal list-inside text-gray-300 space-y-2">
                        <li>Show this ticket (with QR code) to the cinema staff when entering the studio.</li>
                        <li>Make sure the QR code is clearly visible and scannable from your phone screen.</li>
                        <li>Each seat has a unique QR code, use it according to your seat number.</li>
                        <li>If you encounter any issues, show the ticket code to the staff.</li>
                        <li>Do not share your QR code with others to prevent misuse.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    {{-- QR code now generated in backend, no JS needed --}}
    @endpush
    <x-footer />
</x-app-layout>
