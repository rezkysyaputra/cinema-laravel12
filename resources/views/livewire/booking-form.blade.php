<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-card rounded-xl p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Pesan Tiket</h1>

            <!-- Movie Info -->
            <div class="flex items-center gap-4 mb-8">
                <img src="{{ $screening->movie->poster_url }}" alt="{{ $screening->movie->title }}" class="w-24 h-36 object-cover rounded-lg">
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $screening->movie->title }}</h2>
                    <p class="text-gray-400">{{ \Carbon\Carbon::parse($screening->start_time)->format('l, d M Y, H:i') }}</p>
                    <p class="text-gray-400 mt-2">Studio: {{ $screening->studio->name }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Seat Selection -->
                <div class="lg:col-span-2">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-white">Pilih Kursi</h3>
                            <p class="text-gray-400 text-sm">Kapasitas: {{ $screening->studio->capacity }} kursi</p>
                        </div>
                        <div class="flex justify-end gap-4 text-sm">
                            <p class="text-gray-400">Tersedia: <span class="text-white">{{ $screening->studio->capacity - count($bookedSeatIds) }}</span></p>
                            <p class="text-gray-400">Terpesan: <span class="text-white">{{ count($bookedSeatIds) }}</span></p>
                        </div>
                    </div>

                    <!-- Screen -->
                    <div class="bg-gray-800 rounded-lg p-4 mb-6 text-center">
                        <div class="w-3/4 h-2 bg-gray-600 mx-auto rounded-t-lg"></div>
                        <p class="text-gray-400 mt-2">Layar</p>
                    </div>

                    <!-- Seats Grid -->
                    <div class="max-w-3xl mx-auto overflow-x-auto flex flex-col items-start  md:items-center">
                        <!-- Kolom Label -->
                        <div class="flex items-center gap-2 mb-2 min-w-max justify-center">
                            <span class="w-6"></span>
                            <div class="flex-1 flex gap-2 justify-center">
                                @for ($col = 1; $col <= $screening->studio->column; $col++)
                                    <span class="w-10 text-center text-xs text-gray-400">{{ $col }}</span>
                                    @endfor
                            </div>
                        </div>
                        @for ($row = 1; $row <= $screening->studio->row; $row++)
                            @php
                            $rowLetter = chr(64 + $row);
                            $seats = $this->seatsByRow[$row] ?? [];
                            @endphp
                            <div class="flex items-center gap-2 mb-3 min-w-max justify-center">
                                <!-- Label Baris -->
                                <span class="text-gray-400 w-6 flex items-center justify-center font-semibold">{{ $rowLetter }}</span>
                                <!-- Seats Row -->
                                <div class="flex-1 flex gap-2 justify-center">
                                    @foreach($seats as $seat)
                                    @php
                                    $isBooked = in_array($seat->id, $bookedSeatIds);
                                    $isSelected = in_array($seat->id, $selectedSeats);
                                    @endphp
                                    <button type="button" wire:click="toggleSeat({{ $seat->id }})" class="w-10 h-10 rounded-lg border-2 flex items-center justify-center font-bold text-xs transition-all duration-200
                                            {{ $isBooked
                                                ? 'bg-red-600 border-red-700 text-white cursor-not-allowed opacity-60'
                                                : ($isSelected
                                                    ? 'bg-orange-500 border-orange-400 text-white scale-110 shadow-lg hover:bg-orange-600'
                                                    : 'bg-gray-600 border-gray-500 text-white hover:bg-gray-500 hover:scale-105') }}" style="transition: box-shadow 0.2s, transform 0.2s;" {{ $isBooked ? 'disabled' : '' }} aria-label="Kursi {{ $rowLetter }}{{ $seat->number }}">
                                        {{ $seat->number }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                            @endfor
                    </div>

                    <!-- Seat Legend -->
                    <div class="flex items-center justify-center gap-6 mt-8">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-gray-600 rounded"></div>
                            <span class="text-gray-400 text-sm">Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-orange-500 rounded"></div>
                            <span class="text-gray-400 text-sm">Dipilih</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-600 rounded"></div>
                            <span class="text-gray-400 text-sm">Sudah Dipesan</span>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-dark-bg rounded-lg p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Ringkasan Pesanan</h3>

                        <!-- Selected Seats -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-400 mb-3">Kursi yang Dipilih:</h4>
                            @if(count($selectedSeats) > 0)
                            <div class="space-y-2">
                                @php
                                $selectedSeatModels = \App\Models\Seat::whereIn('id', $selectedSeats)->get();
                                @endphp
                                @foreach($selectedSeatModels as $seat)
                                <div class="flex items-center justify-between bg-gray-800 rounded-lg p-3">
                                    <div>
                                        <span class="text-white font-medium">{{ chr(64 + $seat->row) }}{{ $seat->number }}</span>
                                        <p class="text-gray-400 text-xs">{{ $screening->studio->name }}</p>
                                    </div>
                                    <span class="text-orange-400 font-medium">{{ $this->formattedPricePerSeat }}</span>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-gray-500 text-sm">Belum ada kursi yang dipilih</p>
                            @endif
                        </div>

                        <!-- Price Breakdown -->
                        <div class="border-t border-gray-700 pt-4 mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400">Harga per kursi:</span>
                                <span class="text-white">{{ $this->formattedPricePerSeat }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-400">Jumlah kursi:</span>
                                <span class="text-white">{{ count($selectedSeats) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-lg font-semibold">
                                <span class="text-white">Total:</span>
                                <span class="text-orange-400">{{ $this->formattedTotalPrice }}</span>
                            </div>
                        </div>

                        <!-- Proceed Button -->
                        <button wire:click="proceedToPayment" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition disabled:opacity-50 disabled:cursor-not-allowed" {{ count($selectedSeats) == 0 ? 'disabled' : '' }}>
                            Lanjut ke Pembayaran
                        </button>

                        @if(session()->has('error'))
                        <div class="mt-4 p-3 bg-red-500/20 border border-red-500/50 rounded-lg">
                            <p class="text-red-400 text-sm">{{ session('error') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
