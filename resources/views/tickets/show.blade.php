<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class=" p-8">
                <h1 class="text-2xl font-bold text-white mb-6">Order Details</h1>
                <div class="mb-8 space-y-2">
                    <div>
                        <span class="text-gray-400">Movie</span>
                        <span class="text-white font-semibold ml-2">{{ $ticket->screening->movie->title }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Studio</span>
                        <span class="text-white ml-2">{{ $ticket->screening->studio->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Date & Time</span>
                        <span class="text-white ml-2">{{ \Carbon\Carbon::parse($ticket->screening->start_time)->format('d M Y, H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400">Seats</span>
                        <span class="text-white ml-2">{{ $ticket->tickets->count() }} seat(s)</span>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ticket->tickets as $ticketSeat)
                        <span class="bg-orange-500/20 text-orange-500 px-3 py-1 rounded-full text-sm">
                            {{ $ticketSeat->seat->code}}
                        </span>
                        @endforeach
                    </div>
                </div>
                <div class="bg-dark-card/80 rounded-lg p-6 border border-gray-700 mb-8">
                    <h2 class="text-lg font-bold text-white mb-4">Payment Details</h2>
                    @php
                    $latestPayment = $ticket->payments->sortByDesc('created_at')->first();
                    @endphp
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Status</span>
                            @if($latestPayment && $latestPayment->transaction_status === 'settlement')
                            <span class="bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-sm font-medium">Paid</span>
                            @elseif($latestPayment && $latestPayment->transaction_status === 'pending')
                            <span class="bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm font-medium">Pending</span>
                            @elseif($latestPayment && in_array($latestPayment->transaction_status, ['cancel', 'deny', 'expire']))
                            <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm font-medium">Failed</span>
                            @else
                            <span class="bg-gray-500/20 text-gray-400 px-3 py-1 rounded-full text-sm font-medium">-</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Method</span>
                            <span class="text-white">{{ $latestPayment ? ucfirst($latestPayment->payment_type) : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Order ID</span>
                            <span class="text-white">{{ $latestPayment->order_id ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Payment Date</span>
                            <span class="text-white">{{ $latestPayment ? \Carbon\Carbon::parse($latestPayment->created_at)->format('d M Y H:i') : '-' }}</span>
                        </div>
                        @if($latestPayment && $latestPayment->transaction_status === 'pending' && $latestPayment->payment_expired_at)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Payment Expiry</span>
                            <span class="text-red-400">{{ \Carbon\Carbon::parse($latestPayment->payment_expired_at)->format('d M Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between font-semibold text-lg pt-3 border-t border-gray-700 mt-3">
                            <span class="text-white">Total</span>
                            <span class="text-orange-500">Rp {{ number_format($ticket->total_price + 5000, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <a href="{{ route('tickets.see', $ticket) }}" class="block w-full bg-orange-500 text-white text-center px-4 py-3 rounded-lg hover:bg-orange-600 transition-colors font-semibold">
                        See Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
</x-app-layout>
