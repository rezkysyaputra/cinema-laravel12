document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("bookingForm");
    const seats = document.querySelectorAll('input[name="selected_seats[]"]');
    const selectedSeatsSpan = document.getElementById("selected-seats");
    const totalPriceSpan = document.getElementById("total-price");
    const ticketPrice = window.ticketPrice || 0;

    function updateSummary() {
        const selectedSeats = document.querySelectorAll(
            'input[name="selected_seats[]"]:checked'
        ).length;
        const total = selectedSeats * ticketPrice;

        if (selectedSeatsSpan) {
            selectedSeatsSpan.textContent = selectedSeats;
        }
        if (totalPriceSpan) {
            totalPriceSpan.textContent = `Rp ${total.toLocaleString("id-ID")}`;
        }
    }

    seats.forEach((seat) => {
        seat.addEventListener("change", updateSummary);
    });

    // Form validation
    form.addEventListener("submit", function (e) {
        const selectedSeats = document.querySelectorAll(
            'input[name="selected_seats[]"]:checked'
        ).length;

        if (selectedSeats === 0) {
            e.preventDefault();
            alert("Silakan pilih minimal satu kursi.");
            return false;
        }

        // Disable submit button and show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = "Memproses...";
        submitButton.classList.add("opacity-75");

        // Allow form to submit
        return true;
    });
});
