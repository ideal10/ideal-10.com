import './bootstrap';

import Alpine from 'alpinejs';
import 'flowbite';

import { initClientsTicker } from './clients-ticker.js';
import { initReveal } from './reveal.js';
import { initAdminDeleteModal } from './admin-delete-modal.js';
import { initAdminRowDblClick } from './admin-row-dblclick.js';
import { initAdminFileUploadProgress } from './admin-file-upload-progress.js';
import { entityEditor } from './admin-entity-editor.js';
import { entityDirectory } from './admin-entity-directory.js';

window.Alpine = Alpine;
Alpine.data('entityEditor', entityEditor);
Alpine.data('entityDirectory', entityDirectory);
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
