const CACHE = 'wareed-v1';

const PRECACHE = [
    '/',
    '/offline.html',
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE).then(function (cache) {
            return cache.addAll(PRECACHE);
        })
    );
});

self.addEventListener('fetch', function (event) {
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request).catch(function () {
            return caches.match(event.request).then(function (cached) {
                return cached || caches.match('/offline.html');
            });
        })
    );
});




self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body:    data.body    ?? 'New blood request',
        icon:    data.icon    ?? '/icons/icon-192.png',
        badge:   data.badge   ?? '/icons/icon-192.png',
        data:    data.url     ? { url: data.url } : {},
        actions: [
            { action: 'view', title: 'View Request' },
            { action: 'dismiss', title: 'Dismiss' }
        ],
        vibrate:   [200, 100, 200],
        requireInteraction: true,
    };

    event.waitUntil(
        self.registration.showNotification(data.title ?? 'Wareed 🩸', options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    if (event.action === 'dismiss') return;

    const url = event.notification.data?.url ?? '/donor/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            for (const client of clientList) {
                if (client.url === url && 'focus' in client) {
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});