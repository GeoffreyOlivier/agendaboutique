// WireChat Service Worker for notifications
const CACHE_NAME = 'wirechat-v1';

// Install event
self.addEventListener('install', (event) => {
    console.log('WireChat Service Worker installed');
    self.skipWaiting(); // Activate immediately
});

// Activate event
self.addEventListener('activate', (event) => {
    console.log('WireChat Service Worker activated');
    event.waitUntil(self.clients.claim()); // Take control of all pages
});

// Handle messages from the main thread
self.addEventListener('message', (event) => {
    const { type, tag } = event.data;
    
    if (type === 'SHOW_NOTIFICATION') {
        const { title, options } = event.data;
        
        // Show notification
        self.registration.showNotification(title, {
            ...options,
            tag: tag || 'wirechat-notification'
        });
        
    } else if (type === 'CLOSE_NOTIFICATION') {
        // Close notification with specific tag
        self.registration.getNotifications({ tag }).then(notifications => {
            notifications.forEach(notification => notification.close());
        });
    }
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
    const notification = event.notification;
    const url = notification.data?.url || '/chats';
    
    event.notification.close();
    
    // Open or focus the chat window
    event.waitUntil(
        self.clients.matchAll({ type: 'window' }).then(clientList => {
            // Check if there's already a window open with the URL
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            
            // If no window is open, open a new one
            if (self.clients.openWindow) {
                return self.clients.openWindow(url);
            }
        })
    );
});

// Handle push events (for future use)
self.addEventListener('push', (event) => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: data.icon || '/favicon.ico',
            badge: data.badge || '/favicon.ico',
            tag: data.tag || 'wirechat-notification',
            data: data.data || {}
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});
