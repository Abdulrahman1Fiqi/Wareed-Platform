import './echo';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js');
    });
}




async function subscribeToPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    const registration = await navigator.serviceWorker.ready;

    const permission = await Notification.requestPermission();
    if (permission !== 'granted') return;

    const existing = await registration.pushManager.getSubscription();
    if (existing) {
        await sendSubscriptionToServer(existing);
        return;
    }

    const response = await fetch('/push/vapid-public-key');
    const { publicKey } = await response.json();

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicKey),
    });

    await sendSubscriptionToServer(subscription);
}

async function sendSubscriptionToServer(subscription) {
    const data = subscription.toJSON();
    await fetch('/push/subscribe', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        },
        body: JSON.stringify({
            endpoint:   data.endpoint,
            public_key: data.keys?.p256dh,
            auth_token: data.keys?.auth,
        }),
    });
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