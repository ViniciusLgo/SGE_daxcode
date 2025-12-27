import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                dax: {
                    dark: '#1F2933',
                    green: '#4B5D3B',
                    greenSoft: '#5F7250',
                    yellow: '#F4C430',
                    light: '#F8F4E6',
                },
            },
            boxShadow: {
                dax: '0 20px 40px rgba(0, 0, 0, 0.35)',
            },
        },
    },
    plugins: [forms],
};
