/**
 * Alpine component for the admin entity directory: a searchable grid of
 * entity cards; clicking one opens a side panel with its sub-entities.
 *
 * Search text is precomputed server-side (lowercase, no accents) and passed
 * in per entity as `[entityText, [linkText, ...]]`, keyed by entity id.
 */
function normalize(value) {
    return (value || '').normalize('NFD').replace(/[̀-ͯ]/g, '').trim().toLowerCase();
}

export function entityDirectory(index) {
    return {
        query: '',
        selected: null,
        index,

        get q() {
            return normalize(this.query);
        },

        entityVisible(id) {
            const [entityText, linkTexts] = this.index[id];

            return !this.q || entityText.includes(this.q) || linkTexts.some((text) => text.includes(this.q));
        },

        /** Sub-entities that match when only they (not the entity) match. */
        matchingLinks(id) {
            const [entityText, linkTexts] = this.index[id];

            if (!this.q || entityText.includes(this.q)) {
                return [];
            }

            return linkTexts.map((text, i) => (text.includes(this.q) ? i : null)).filter((i) => i !== null);
        },

        get visibleCount() {
            return Object.keys(this.index).filter((id) => this.entityVisible(id)).length;
        },

        open(id) {
            this.selected = id;
            document.body.classList.add('overflow-hidden');
            this.$nextTick(() => this.$refs.panelClose?.focus());
        },

        close() {
            this.selected = null;
            document.body.classList.remove('overflow-hidden');
        },
    };
}
