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