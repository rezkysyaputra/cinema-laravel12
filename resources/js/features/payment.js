document.addEventListener("DOMContentLoaded", function () {
    const payButton = document.getElementById("pay-button");
    if (!payButton) return;

    payButton.onclick = function () {
        snap.pay(window.snapToken, {
            onSuccess: function (result) {
                window.location.href = window.paymentSuccessUrl;
            },
            onPending: function (result) {
                window.location.href = window.paymentPendingUrl;
            },
            onError: function (result) {
                window.location.href = window.paymentErrorUrl;
            },
            onClose: function () {
                // Handle when customer closes the popup without finishing the payment
            },
        });
    };
});
