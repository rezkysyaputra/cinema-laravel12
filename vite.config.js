import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/ticket.js",
                "resources/js/features/booking.js",
                "resources/js/features/payment.js",
            ],
            refresh: true,
        }),
    ],
});
