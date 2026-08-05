/**
 * Retargets the shared admin delete-confirmation modal's form action to
 * whichever row's delete button was clicked. Flowbite handles opening and
 * closing the modal itself via the `data-modal-toggle`/`data-modal-hide`
 * attributes already present on the markup.
 */
export function initAdminDeleteModal() {
    const form = document.getElementById('delete-form');

    if (!form) {
        return;
    }

    document.querySelectorAll('[data-delete-action]').forEach((button) => {
        button.addEventListener('click', () => {
            form.action = button.dataset.deleteAction;
        });
    });
}
