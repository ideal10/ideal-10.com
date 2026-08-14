import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';

import { initClientsTicker } from './clients-ticker.js';
import { initReveal } from './reveal.js';
import { initAdminDeleteModal } from './admin-delete-modal.js';
import { initAdminRowDblClick } from './admin-row-dblclick.js';
import { initAdminFileUploadProgress } from './admin-file-upload-progress.js';

window.Alpine = Alpine;
Alpine.start();

function boot() {
    initClientsTicker();
    initReveal();
    initAdminDeleteModal();
    initAdminRowDblClick();
    initAdminFileUploadProgress();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
    boot();
}
