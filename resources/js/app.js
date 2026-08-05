import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';

import { initClientsTicker } from './clients-ticker.js';
import { initReveal } from './reveal.js';

window.Alpine = Alpine;
Alpine.start();

function boot() {
    initClientsTicker();
    initReveal();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}
