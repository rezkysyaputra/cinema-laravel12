<x-filament::page>
    <div class="space-y-6">
        <!-- Simple Header -->
        {{-- <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Tiket</h1>
        </div> --}}

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Scanner & Manual Input -->
            <div class="space-y-4">
                <!-- QR Scanner -->
                <x-filament::card>
                    <h2 class="text-lg font-semibold mb-4">Scanner QR Code</h2>

                    <div id="qr-reader" class="w-full h-48 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center mb-4">
                        <div class="text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                            <p class="text-sm">Klik "Mulai Scan" untuk mengaktifkan kamera</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <x-filament::button color="success" id="start-scan" type="button" size="sm">
                            Mulai Scan
                        </x-filament::button>
                        <x-filament::button color="danger" id="stop-scan" type="button" class="hidden" size="sm">
                            Stop Scan
                        </x-filament::button>
                    </div>
                </x-filament::card>

                <!-- Manual Input & Inline Result -->
                <x-filament::card>
                    <h2 class="text-lg font-semibold mb-4">Verifikasi Manual</h2>

                    <form id="verify-form" class="space-y-3 mb-4">
                        <div class="flex gap-2">
                            <x-filament::input id="ticket_code" name="ticket_code" class="flex-1" placeholder="Masukkan kode tiket..." required />
                            <x-filament::button type="submit" color="primary" id="verify-btn" size="sm">
                                Verifikasi
                            </x-filament::button>
                        </div>
                    </form>
                    <div id="inline-result" class="mt-4"></div>
                </x-filament::card>
            </div>

            <!-- Right Column: (empty, previously recent verifications) -->
            <div class="space-y-4">
                <!-- (Intentionally left blank) -->
            </div>
        </div>

        <!-- Ticket List -->
        <x-filament::card>
            <h2 class="text-lg font-semibold mb-4">Daftar Tiket</h2>
            {{ $this->table }}
        </x-filament::card>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        const qrRegionId = "qr-reader";
        const startScanBtn = document.getElementById('start-scan');
        const stopScanBtn = document.getElementById('stop-scan');
        const ticketInput = document.getElementById('ticket_code');
        const verifyBtn = document.getElementById('verify-btn');
        const inlineResult = document.getElementById('inline-result');

        // QR Scanner
        startScanBtn.addEventListener('click', function() {
            startScanBtn.classList.add('hidden');
            stopScanBtn.classList.remove('hidden');

            html5QrCode = new Html5Qrcode(qrRegionId);
            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10
                    , qrbox: {
                        width: 200
                        , height: 200
                    }
                }
                , qrCodeMessage => {
                    ticketInput.value = qrCodeMessage;
                    stopScan();
                    verifyTicket(qrCodeMessage);
                }
                , errorMessage => {}
            );
        });

        stopScanBtn.addEventListener('click', stopScan);

        function stopScan() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    startScanBtn.classList.remove('hidden');
                    stopScanBtn.classList.add('hidden');
                });
            }
        }

        // Manual Verification
        document.getElementById('verify-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const code = ticketInput.value.trim();
            if (code) {
                verifyTicket(code);
            }
        });

        // Inline Verification Result
        function showInlineResult(type, html) {
            inlineResult.innerHTML = html;
            inlineResult.className = 'mt-4';
            inlineResult.style.animation = 'fadeInUp 0.4s';
        }

        // Add animation style
        if (!document.getElementById('fadeInUp-style')) {
            const style = document.createElement('style');
            style.id = 'fadeInUp-style';
            style.innerHTML = `
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }`;
            document.head.appendChild(style);
        }

        // Verification Function
        function verifyTicket(ticketCode) {
            showInlineResult('loading', `
                <div class='flex items-center gap-3 px-5 py-4 rounded-xl shadow bg-blue-50 border border-blue-200 animate-fadeInUp'>
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                        <svg class='animate-spin h-7 w-7 text-blue-500' xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24'>
                            <circle class='opacity-25' cx='12' cy='12' r='10' stroke='currentColor' stroke-width='4'></circle>
                            <path class='opacity-75' fill='currentColor' d='M4 12a8 8 0 018-8v8z'></path>
                        </svg>
                    </div>
                    <div>
                        <div class='font-semibold text-blue-800 text-lg mb-1'>Memverifikasi tiket...</div>
                        <div class='text-blue-600 text-sm'>Mohon tunggu sebentar</div>
                    </div>
                </div>
            `);
            verifyBtn.setAttribute('disabled', 'disabled');
            fetch('/admin/tickets/verify', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify({
                        ticket_code: ticketCode
                    })
                })
                .then(res => res.json())
                .then(data => {
                    verifyBtn.removeAttribute('disabled');
                    if (data.status === 'success') {
                        showInlineResult('success', `
                        <div class='flex gap-4 px-6 py-5 rounded-2xl shadow-lg bg-green-50 border border-green-200 animate-fadeInUp items-center'>
                            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-green-100">
                                <svg class='w-8 h-8 text-green-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class='font-bold text-green-800 text-lg mb-1'>✅ ${data.message}</div>
                                <div class='text-green-700 text-sm mb-3'>Verifikasi berhasil - Tiket dapat digunakan</div>
                                <div class='grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 bg-white rounded-lg p-3 border border-green-100'>
                                    <div><span class='font-medium text-gray-600'>Film:</span> <span class='font-semibold text-gray-900'>${data.data.movie}</span></div>
                                    <div><span class='font-medium text-gray-600'>Kursi:</span> <span class='font-semibold text-gray-900'>${data.data.seat}</span></div>
                                    <div><span class='font-medium text-gray-600'>Studio:</span> <span class='font-semibold text-gray-900'>${data.data.studio}</span></div>
                                    <div><span class='font-medium text-gray-600'>Jadwal:</span> <span class='font-semibold text-gray-900'>${data.data.date}</span></div>
                                </div>
                            </div>
                        </div>
                    `);
                        ticketInput.value = '';
                    } else {
                        // Tentukan icon dan warna berdasarkan jenis error
                        let icon = 'M6 18L18 6M6 6l12 12'; // default X icon
                        let bgColor = 'bg-red-50';
                        let borderColor = 'border-red-200';
                        let iconBgColor = 'bg-red-100';
                        let iconColor = 'text-red-500';
                        let textColor = 'text-red-800';
                        let subTextColor = 'text-red-700';

                        // Customize berdasarkan pesan error
                        if (data.message.includes('belum bisa dipakai')) {
                            icon = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z';
                            bgColor = 'bg-yellow-50';
                            borderColor = 'border-yellow-200';
                            iconBgColor = 'bg-yellow-100';
                            iconColor = 'text-yellow-500';
                            textColor = 'text-yellow-800';
                            subTextColor = 'text-yellow-700';
                        } else if (data.message.includes('sudah digunakan')) {
                            icon = 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                            bgColor = 'bg-orange-50';
                            borderColor = 'border-orange-200';
                            iconBgColor = 'bg-orange-100';
                            iconColor = 'text-orange-500';
                            textColor = 'text-orange-800';
                            subTextColor = 'text-orange-700';
                        } else if (data.message.includes('tidak berlaku')) {
                            icon = 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                            bgColor = 'bg-gray-50';
                            borderColor = 'border-gray-200';
                            iconBgColor = 'bg-gray-100';
                            iconColor = 'text-gray-500';
                            textColor = 'text-gray-800';
                            subTextColor = 'text-gray-700';
                        }

                        showInlineResult('error', `
                        <div class='flex gap-4 px-6 py-5 rounded-2xl shadow-lg ${bgColor} border ${borderColor} animate-fadeInUp items-center'>
                            <div class="flex items-center justify-center w-14 h-14 rounded-full ${iconBgColor}">
                                <svg class='w-8 h-8 ${iconColor}' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='${icon}'></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class='font-bold ${textColor} text-lg mb-1'>❌ ${data.message}</div>
                                <div class='${subTextColor} text-sm'>Verifikasi gagal - Tiket tidak dapat digunakan</div>
                            </div>
                        </div>
                    `);
                    }
                })
                .catch(() => {
                    verifyBtn.removeAttribute('disabled');
                    showInlineResult('error', `
                    <div class='flex gap-4 px-6 py-5 rounded-2xl shadow-lg bg-red-50 border border-red-200 animate-fadeInUp items-center'>
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
                            <svg class='w-8 h-8 text-red-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class='font-bold text-red-800 text-lg mb-1'>❌ Terjadi kesalahan</div>
                            <div class='text-red-700 text-sm'>Gagal terhubung ke server. Periksa koneksi internet Anda.</div>
                        </div>
                    </div>
                `);
                });
        }

    </script>
    @endpush
</x-filament::page>
