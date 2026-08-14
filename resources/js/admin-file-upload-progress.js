/**
 * Shows a live progress bar while a file input's contents upload, since
 * large files (interest links allow up to 200MB) can take a while and a
 * plain form submit gives no feedback until the request finishes.
 */
export function initAdminFileUploadProgress() {
    document.querySelectorAll('form[data-upload-progress]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const fileInput = form.querySelector('input[type="file"]');

            if (!fileInput || fileInput.files.length === 0) {
                return;
            }

            event.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const wrapper = form.querySelector('[data-upload-progress-wrapper]');
            const fill = form.querySelector('[data-upload-progress-fill]');
            const label = form.querySelector('[data-upload-progress-label]');
            const originalButtonText = submitButton?.textContent;

            // No X-Requested-With header on purpose: Laravel treats that as an
            // AJAX request and returns a 422 JSON response on validation
            // failure instead of the normal redirect-back-with-errors this
            // relies on to re-render the form.
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.upload.addEventListener('progress', (progressEvent) => {
                if (!progressEvent.lengthComputable) {
                    return;
                }

                const percent = Math.round((progressEvent.loaded / progressEvent.total) * 100);
                fill.style.width = `${percent}%`;
                label.textContent = `${percent}%`;
            });

            xhr.addEventListener('load', () => {
                // XHR already followed Laravel's post-submit redirect (to the
                // index on success, or back to this form with flashed errors
                // on failure), consuming the one-shot session flash data in
                // the process. Render that already-fetched response directly
                // instead of navigating again, which would hit the same URL
                // with the flash gone and show a blank form either way.
                document.open();
                document.write(xhr.responseText);
                document.close();
                if (xhr.responseURL) {
                    history.replaceState(null, '', xhr.responseURL);
                }
                window.scrollTo(0, 0);
            });

            xhr.addEventListener('error', () => {
                wrapper.classList.add('hidden');
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                }
                alert('Error al subir el archivo. Inténtalo de nuevo.');
            });

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Subiendo...';
            }
            fill.style.width = '0%';
            label.textContent = '0%';
            wrapper.classList.remove('hidden');

            xhr.send(new FormData(form));
        });
    });
}
