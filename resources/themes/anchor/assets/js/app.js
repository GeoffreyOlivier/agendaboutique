// Bootstrap
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Echo configuration 
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Alpine.js
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

// Register Alpine plugins
Alpine.plugin(focus);

// Check if Alpine is already initialized by Livewire or other sources
if (typeof window.Alpine === 'undefined') {
    window.Alpine = Alpine;
    Alpine.start();
} else {
    // If Alpine already exists, just register our plugins
    if (window.Alpine.plugin && typeof window.Alpine.plugin === 'function') {
        window.Alpine.plugin(focus);
    }
}

// Demo button function (conservé pour la compatibilité)
window.demoButtonClickMessage = function(event){
    event.preventDefault(); new FilamentNotification().title('Modify this button in your theme folder').icon('heroicon-o-pencil-square').iconColor('info').send()
}