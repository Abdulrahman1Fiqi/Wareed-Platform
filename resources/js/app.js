import './echo';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Register service worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js', { scope: '/' })
            .then(function (registration) {
                console.log('SW registered:', registration.scope);
            })
            .catch(function (error) {
                console.log('SW registration failed:', error);
            });
    });
}

// Fix iOS PWA - restore scroll position
if (window.navigator.standalone) {
    document.addEventListener('click', function (e) {
        const target = e.target.closest('a');
        if (target && target.href && !target.href.includes('#') &&
            target.href.startsWith(window.location.origin)) {
            e.preventDefault();
            window.location.href = target.href;
        }
    });
}

// Prevent double-tap zoom on iOS
let lastTouchEnd = 0;
document.addEventListener('touchend', function (e) {
    const now = Date.now();
    if (now - lastTouchEnd <= 300) {
        e.preventDefault();
    }
    lastTouchEnd = now;
}, { passive: false });

// Push notification subscription
async function subscribeToPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    try {
        const registration = await navigator.serviceWorker.ready;
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return;

        const publicKey = import.meta.env.VITE_VAPID_PUBLIC_KEY;
        if (!publicKey) return;

        const existing = await registration.pushManager.getSubscription();
        if (existing) {
            await sendSubscriptionToServer(existing);
            return;
        }

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly:      true,
            applicationServerKey: urlBase64ToUint8Array(publicKey),
        });

        await sendSubscriptionToServer(subscription);
    } catch (error) {
        console.log('Push subscription failed:', error);
    }
}

async function sendSubscriptionToServer(subscription) {
    const data = subscription.toJSON();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!csrfToken) return;

    try {
        await fetch('/push/subscribe', {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  csrfToken,
            },
            body: JSON.stringify({
                endpoint:   data.endpoint,
                public_key: data.keys?.p256dh,
                auth_token: data.keys?.auth,
            }),
        });
    } catch (error) {
        console.log('Failed to send subscription to server:', error);
    }
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
}

window.addEventListener('load', function () {
    subscribeToPush();
});