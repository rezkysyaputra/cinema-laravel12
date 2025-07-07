// Initialize barcodes for each seat
document.addEventListener("DOMContentLoaded", function () {
    const tickets = document.querySelectorAll("[data-ticket-barcode]");
    tickets.forEach((ticket) => {
        const barcodeData = ticket.dataset.ticketBarcode;
        const barcodeId = ticket.dataset.barcodeId;
        JsBarcode(`#barcode-${barcodeId}`, barcodeData, {
            format: "CODE128",
            width: 2,
            height: 100,
            displayValue: false,
        });
    });
});

// View individual ticket
function viewTicket(ticketId, seatId) {
    const modal = document.getElementById("ticketModal");
    const content = document.getElementById("ticketModalContent");

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

    modal.classList.remove("hidden");
    modal.classList.add("flex");

    // Initialize barcode in modal
    JsBarcode("#modal-barcode", `${ticketId}-${seatId}`, {
        format: "CODE128",
        width: 2,
        height: 100,
        displayValue: false,
    });
}

// Close ticket modal
function closeTicketModal() {
    const modal = document.getElementById("ticketModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
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
    console.log("Downloading all tickets");
}
