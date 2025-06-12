<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark-card rounded-xl p-6">
                <h1 class="text-2xl font-bold text-white mb-6">Book Tickets</h1>

                <!-- Movie Info -->
                <div class="flex items-center gap-4 mb-8">
                    <img src="{{ $screening->movie->poster_url }}" alt="{{ $screening->movie->title }}" class="w-24 h-36 object-cover rounded-lg">
                    <div>
                        <h2 class="text-xl font-semibold text-white">{{ $screening->movie->title }}</h2>
                        <p class="text-gray-400">{{ \Carbon\Carbon::parse($screening->start_time)->format('l, M d, Y g:i A') }}</p>
                        <p class="text-gray-400 mt-2">Price: Rp {{ number_format($screening->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <form action="{{ route('booking.store', $screening) }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="movie_id" value="{{ $screening->movie->id }}">
                    <input type="hidden" name="screening_id" value="{{ $screening->id }}">

                    <!-- Seat Selection -->
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-4">Select Seats</h3>

                        <!-- Screen -->
                        <div class="bg-gray-800 rounded-lg p-4 mb-6 text-center">
                            <div class="w-3/4 h-2 bg-gray-600 mx-auto rounded-t-lg"></div>
                            <p class="text-gray-400 mt-2">Screen</p>
                        </div>

                        <!-- Seats Grid -->
                        <div class="max-w-3xl mx-auto">
                            @php
                            $seatsByRow = $allSeats->groupBy('row_letter');
                            $rows = $seatsByRow->keys()->sort();
                            @endphp

                            <!-- Seats -->
                            @foreach($rows as $rowLetter)
                            @php
                            $seats = $seatsByRow[$rowLetter]->sortBy('seat_number');
                            $leftSeats = $seats->take(5);
                            $rightSeats = $seats->slice(5);
                            @endphp

                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-gray-400 w-6">{{ $rowLetter }}</span>

                                <!-- Left Section -->
                                <div class="flex-1 grid grid-cols-5 gap-2">
                                    @foreach($leftSeats as $seat)
                                    <div class="relative">
                                        <input type="checkbox" name="selected_seats[]" value="{{ $seat->id }}" id="seat-{{ $seat->id }}" class="peer hidden" {{ in_array($seat->id, $bookedSeatIds) ? 'disabled' : '' }}>
                                        <label for="seat-{{ $seat->id }}" class="block w-full aspect-square rounded-lg cursor-pointer
                                                              {{ in_array($seat->id, $bookedSeatIds)
                                                                 ? 'bg-red-600 cursor-not-allowed'
                                                                 : 'bg-gray-600 peer-checked:bg-orange-500 hover:bg-gray-500' }}">
                                            <span class="absolute inset-0 flex items-center justify-center text-xs text-white">
                                                {{ $seat->seat_number }}
                                            </span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Center Aisle -->
                                <div class="w-8"></div>

                                <!-- Right Section -->
                                <div class="flex-1 grid grid-cols-5 gap-2">
                                    @foreach($rightSeats as $seat)
                                    <div class="relative">
                                        <input type="checkbox" name="selected_seats[]" value="{{ $seat->id }}" id="seat-{{ $seat->id }}" class="peer hidden" {{ in_array($seat->id, $bookedSeatIds) ? 'disabled' : '' }}>
                                        <label for="seat-{{ $seat->id }}" class="block w-full aspect-square rounded-lg cursor-pointer
                                                              {{ in_array($seat->id, $bookedSeatIds)
                                                                 ? 'bg-red-600 cursor-not-allowed'
                                                                 : 'bg-gray-600 peer-checked:bg-orange-500 hover:bg-gray-500' }}">
                                            <span class="absolute inset-0 flex items-center justify-center text-xs text-white">
                                                {{ $seat->seat_number }}
                                            </span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Seat Legend -->
                        <div class="flex items-center justify-center gap-6 mt-12">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-gray-600 rounded"></div>
                                <span class="text-gray-400 text-sm">Available</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-orange-500 rounded"></div>
                                <span class="text-gray-400 text-sm">Selected</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-red-600 rounded"></div>
                                <span class="text-gray-400 text-sm">Occupied</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition">
                        Proceed to Payment
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const seats = document.querySelectorAll('input[name="selected_seats[]"]');
            const selectedSeatsSpan = document.getElementById('selected-seats');
            const totalPriceSpan = document.getElementById('total-price');
            const ticketPrice = {
                {
                    $screening - > price
                }
            };

            function updateSummary() {
                const selectedSeats = document.querySelectorAll('input[name="selected_seats[]"]:checked').length;
                const total = selectedSeats * ticketPrice;

                selectedSeatsSpan.textContent = selectedSeats;
                totalPriceSpan.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            }

            seats.forEach(seat => {
                seat.addEventListener('change', updateSummary);
            });
        });

    </script>
    @endpush
</x-app-layout>
