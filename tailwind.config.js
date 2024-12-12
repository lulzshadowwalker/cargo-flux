import daisyui from "daisyui";
import rtl from "tailwindcss-rtl";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        ,
    ],
    daisyui: {
        themes: ["light"],
    },
    theme: {
        extend: {},
    },
    plugins: [daisyui, rtl],
};
