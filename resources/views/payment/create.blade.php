<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">
        <div class="sm:px-6 lg:px-8">
            <div class="bg-dark-card rounded-xl p-4 sm:p-6">
                <h1 class="text-xl sm:text-2xl font-bold text-white mb-4 sm:mb-6">Payment Details</h1>

                @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
                @endif

                <!-- Booking Summary -->
                <div class="bg-gray-800 rounded-lg p-4 sm:p-6 mb-6 sm:mb-8">
                    <h2 class="text-lg sm:text-xl font-semibold text-white mb-4">Booking Summary</h2>

                    <!-- Movie Info -->
                    <div class="flex items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                        <img src="{{ $booking->screening->movie->poster_url }}" alt="{{ $booking->screening->movie->title }}" class="w-20 sm:w-24 h-28 sm:h-36 object-cover rounded-lg">
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-white">{{ $booking->screening->movie->title }}</h3>
                            <p class="text-sm sm:text-base text-gray-400">{{ \Carbon\Carbon::parse($booking->screening->start_time)->format('l, M d, Y g:i A') }}</p>
                            <p class="text-sm sm:text-base text-gray-400 mt-1 sm:mt-2">Studio: {{ $booking->screening->studio->name }}</p>
                        </div>
                    </div>

                    <!-- Selected Seats -->
                    <div class="mb-4 sm:mb-6">
                        <h4 class="text-sm sm:text-base text-white font-medium mb-2">Selected Seats</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($booking->seats as $seat)
                            <span class="bg-orange-500  text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm">
                                {{ $seat->code }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-gray-700 pt-3 sm:pt-4">
                        <div class="flex justify-between text-gray-400 text-sm sm:text-base mb-2">
                            <span>Ticket Price ({{ count($booking->seats) }}x)</span>
                            <span>Rp {{ number_format($booking->screening->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400 text-sm sm:text-base mb-2">
                            <span>Service Fee</span>
                            <span>Rp 5.000</span>
                        </div>
                        <div class="flex justify-between text-white font-semibold text-base sm:text-lg mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-gray-700">
                            <span>Total Amount</span>
                            <span>Rp {{ number_format($booking->total_price + 5000, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                @if($booking->payments->isNotEmpty())
                @php
                $latestPayment = $booking->payments->sortByDesc('created_at')->first();
                @endphp
                <div class="mb-6">
                    <div class="bg-gray-800 rounded-lg p-4">
                        <h3 class="text-white font-semibold mb-2">Payment Status</h3>
                        <div class="flex items-center gap-2">
                            @switch($latestPayment->status)
                            @case('success')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Paid
                            </span>
                            @break
                            @case('pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pending
                            </span>
                            @break
                            @case('failed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Failed
                            </span>
                            @break
                            @default
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($latestPayment->transaction_status) }}
                            </span>
                            @endswitch
                        </div>
                    </div>
                </div>
                @endif

                <!-- Midtrans Payment Button -->
                <button id="pay-button" class="w-full bg-orange-500 text-white py-2.5 sm:py-3 rounded-lg font-semibold hover:bg-orange-600 transition text-sm sm:text-base disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <span id="button-text">Pay Now</span>
                    <svg id="loading-spinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>

                <!-- Error Modal -->
                <div x-data="{ errorOpen: false, errorMsg: '' }" x-cloak>
                    <div x-show="errorOpen" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto">
                            <h2 class="text-lg font-semibold mb-4 text-red-600">Pembayaran Gagal</h2>
                            <p class="mb-6 text-gray-600" x-text="errorMsg"></p>
                            <div class="flex justify-end">
                                <button @click="errorOpen = false" class="px-4 py-2 rounded bg-orange-500 text-white hover:bg-orange-600">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    @push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        const payButton = document.getElementById('pay-button');
        const buttonText = document.getElementById('button-text');
        const loadingSpinner = document.getElementById('loading-spinner');
        let modalScope = null;

        function setLoading(isLoading) {
            payButton.disabled = isLoading;
            buttonText.textContent = isLoading ? 'Processing...' : 'Pay Now';
            loadingSpinner.classList.toggle('hidden', !isLoading);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Alpine.js scope for error modal
            modalScope = document.querySelector('[x-data]') && Alpine && Alpine.$data(document.querySelector('[x-data]'));

            payButton.addEventListener('click', function() {
                setLoading(true);
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        window.location.href = '{{ route("tickets.index") }}';
                    }
                    , onPending: function(result) {
                        window.location.href = '{{ route("tickets.index") }}';
                    }
                    , onError: function(result) {
                        setLoading(false);
                        if (modalScope) {
                            modalScope.errorMsg = 'Pembayaran gagal! Silakan coba lagi.';
                            modalScope.errorOpen = true;
                        }
                    }
                    , onClose: function() {
                        setLoading(false);
                        if (modalScope) {
                            modalScope.errorMsg = 'Anda menutup jendela pembayaran. Booking akan dibatalkan jika pembayaran tidak diselesaikan.';
                            modalScope.errorOpen = true;
                        }
                    }
                });
            });
        });

    </script>
    @endpush
</x-app-layout>
