<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark-bg text-white min-h-screen" x-data="movieApp()">
    @include('layouts.navigation')
    <!-- Page Heading -->
    @isset($header)
    <header class="">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endisset

    <!-- Page Content -->
    <main class="px-4 sm:px-6 lg:px-8 pb-20 lg:pb-8">
        {{ $slot }}
    </main>


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


</body>
</html>
