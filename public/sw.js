// Service Worker for Web Push Notifications
self.addEventListener('push', function(event) {
    if (event.data) {
        const data = event.data.json();
        
        const title = data.title || 'أركاني';
        const options = {
            body: data.body || 'لديك إشعار جديد',
            icon: data.icon || '/icon-192x192.png',
            badge: data.badge || '/badge-72x72.png',
            tag: data.tag || 'default',
            requireInteraction: data.requireInteraction || true,
            data: data.data || {}
        };
        
        event.waitUntil(
            self.registration.showNotification(title, options)
        );
    }
});

// Handle notification click
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    const url = event.notification.data?.url || '/admin/notifications';
    
    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(function(clientList) {
            // Check if window is already open
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            // Open new window if not already open
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});

// Install event
self.addEventListener('install', function(event) {
    self.skipWaiting();
});

// Activate event
self.addEventListener('activate', function(event) {
    event.waitUntil(self.clients.claim());
});
