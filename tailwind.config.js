import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "dark-bg": "#111827",
                "dark-card": "#1f2937",
                "dark-lighter": "#374151",
            },
            // backgroundImage: {
            //     "dark-bg": "linear-gradient(to right, #0f172a, #334155)",
            // },
        },
    },

    plugins: [forms],
};
