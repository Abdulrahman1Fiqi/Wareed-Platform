import './bootstrap';
import './echo';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/sw.js');
    });
}