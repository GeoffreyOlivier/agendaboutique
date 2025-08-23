import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import preset from './vendor/filament/support/tailwind.config.preset';
import fs from 'fs';
import path from 'path';

const themeFilePath = path.resolve(__dirname, 'theme.json');
const activeTheme = fs.existsSync(themeFilePath) ? JSON.parse(fs.readFileSync(themeFilePath, 'utf8')).name : 'anchor';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './resources/views/components/blade.php',
        './wave/resources/views/**/*.blade.php',
        './resources/themes/' + activeTheme + '/**/*.blade.php',
        './resources/plugins/**/*.php',
        './config/*.php',
        "./vendor/namu/wirechat/**/*.blade.php",
        './vendor/namu/wirechat/resources/views/**/*.blade.php',
        './vendor/namu/wirechat/src/Livewire/**/*.php',
    ],
    
    // Désactiver la purge en développement pour inclure toutes les classes
    safelist: [
        'shrink-0',
        'inline-flex',
        'items-center',
        'justify-center',
        'relative',
        'transition',
        'overflow-visible',
        'rounded-full',
        'border',
        'text-gray-300',
        'text-gray-400',
        'bg-gray-100',
        'dark:bg-gray-600',
        'dark:text-gray-300',
        'w-full',
        'h-full',
        'object-cover',
        'object-center',
        'p-px',
        'absolute',
        'z-50',
        '-bottom-1',
        '-right-2',
        'bg-white',
        'dark:bg-gray-800',
        'w-5',
        'h-5',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                zinc: {
                    50: '#fafafa',
                    100: '#f4f4f5',
                    200: '#e4e4e7',
                    300: '#d4d4d8',
                    400: '#a1a1aa',
                    500: '#71717a',
                    600: '#52525b',
                    700: '#3f3f46',
                    800: '#27272a',
                    900: '#18181b',
                    950: '#09090b',
                }
            },
            animation: {
                'marquee': 'marquee 25s linear infinite',
            },
            keyframes: {
                'marquee': {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-100%)' },
                }
            } 
        },
    },

    plugins: [forms, require('@tailwindcss/typography')],
};
