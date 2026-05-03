const CACHE_NAME = 'wareed-v2';
const OFFLINE_URL = '/offline.html';

const PRECACHE_URLS = [
    '/',
    OFFLINE_URL,
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.addAll(PRECACHE_URLS);
        }).then(function () {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames
                    .filter(function (name) { return name !== CACHE_NAME; })
                    .map(function (name) { return caches.delete(name); })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    if (event.request.method !== 'GET') return;

    if (event.request.url.includes('/broadcasting/auth')) return;
    if (event.request.url.includes('/api/')) return;
    if (event.request.url.includes('ws://') || event.request.url.includes('wss://')) return;
    if (event.request.url.includes('reverb.laravel.cloud')) return;

    if (!event.request.url.startsWith(self.location.origin)) return;

    event.respondWith(
        fetch(event.request)
            .then(function (response) {
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then(function (cache) {
                        const url = event.request.url;
                        if (
                            url.includes('/build/') ||
                            url.includes('/icons/') ||
                            url.includes('/manifest.json') ||
                            url.endsWith('.css') ||
                            url.endsWith('.js') ||
                            url.endsWith('.png') ||
                            url.endsWith('.svg')
                        ) {
                            cache.put(event.request, responseToCache);
                        }
                    });
                }
                return response;
            })
            .catch(function () {
                return caches.match(event.request).then(function (cached) {
                    if (cached) return cached;
                    if (event.request.destination === 'document') {
                        return caches.match(OFFLINE_URL);
                    }
                });
            })
    );
});

self.addEventListener('push', function (event) {
    if (!event.data) return;

    let data;
    try {
        data = event.data.json();
    } catch (e) {
        data = { title: 'Wareed 🩸', body: event.data.text() };
    }

    const options = {
        body:               data.body    ?? 'New blood request',
        icon:               data.icon    ?? '/icons/icon-192.png',
        badge:              '/icons/icon-192.png',
        data:               { url: data.url ?? '/donor/dashboard' },
        vibrate:            [200, 100, 200],
        requireInteraction: true,
        actions: [
            { action: 'view',    title: '🩸 View Request' },
            { action: 'dismiss', title: 'Dismiss' },
        ],
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
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});