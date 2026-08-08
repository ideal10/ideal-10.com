/**
 * Client-side search for the "Enlaces de interés" split layout.
 *
 * Filters the already-rendered `.split-card` elements by their `data-title`
 * attribute — no network requests. Loaded only on the enlaces_de_interes page.
 */
(function () {
    const input = document.getElementById('splitSearch');
    if (!input) return;

    const countEl = document.getElementById('splitCount');
    const cards = document.querySelectorAll('a.split-card');
    const emptyP = document.getElementById('emptyPortales');
    const gridA = document.getElementById('gridArchivos');
    const headingA = document.getElementById('headingArchivos');
    const countP = document.getElementById('countPortales');

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let total = 0, visP = 0, visA = 0;

        cards.forEach(function (card) {
            const match = !q || card.dataset.title.includes(q) ||
                card.querySelector('.split-card-meta').textContent.toLowerCase().includes(q);
            card.classList.toggle('s-hidden', !match);
            if (match) {
                total++;
                if (card.classList.contains('is-file')) visA++; else visP++;
            }
        });

        countEl.textContent = q ? (total + ' resultado' + (total !== 1 ? 's' : '')) : '';
        countEl.className = 's-count' + (q && total > 0 ? ' active' : '');

        if (emptyP) emptyP.style.display = (!q || visP > 0) ? 'none' : 'block';
        if (countP) countP.textContent = visP || q ? visP : countP.textContent;
        if (gridA) gridA.style.display = visA > 0 || !q ? '' : 'none';
        if (headingA) headingA.style.display = visA > 0 || !q ? '' : 'none';
    });
})();
