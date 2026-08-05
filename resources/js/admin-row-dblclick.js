/**
 * Double-clicking an admin table row navigates to that row's edit page.
 * Ignored when the double-click lands on an interactive control inside the
 * row (edit/delete buttons, reorder arrows, the "active" toggle) so their
 * own click behavior isn't shadowed by the row navigation.
 */
export function initAdminRowDblClick() {
    document.querySelectorAll('tr[data-edit-href]').forEach((row) => {
        row.addEventListener('dblclick', (event) => {
            if (event.target.closest('a, button, form, input')) {
                return;
            }

            window.location.href = row.dataset.editHref;
        });
    });
}
