import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                blood: {
                    50:  '#fff1f1',
                    100: '#ffe1e1',
                    500: '#C0392B',
                    600: '#a93226',
                    700: '#922b21',
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}