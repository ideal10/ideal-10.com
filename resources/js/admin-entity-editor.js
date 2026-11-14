/**
 * Alpine component behind the entity editor (admin/entities/form).
 *
 * The entity itself and each sub-entity row share the same "logo target"
 * shape, so one logo picker serves all of them:
 *
 *   uploaded   URL of an image previously uploaded for it (or null)
 *   clientId   id of the client whose logo it reuses (or null)
 *   preview    object URL of a file picked in this session (or null)
 *   removeLogo true when the previously uploaded image must be dropped
 *
 * Rows are rendered with `x-for` keyed by a stable `uid`, so moving a row
 * moves its DOM node and a file already picked in its <input type="file">
 * survives reordering. Input names use the row's current index, so the
 * server receives the links in the order shown.
 */

const STOP_WORDS = ['de', 'del', 'la', 'el', 'los', 'las', 'y', 'e.s.p', 'esp', 's.a', 'alcaldia'];

function normalize(value) {
    return (value || '').normalize('NFD').replace(/[̀-ͯ]/g, '').trim().toLowerCase();
}

function words(value) {
    return normalize(value)
        .split(/[^a-z0-9.]+/)
        .filter((word) => word.length > 1 && !STOP_WORDS.includes(word));
}

export function initials(name) {
    const parts = (name || '')
        .trim()
        .split(/\s+/)
        .filter((word) => word && !['de', 'del', 'la', 'el', 'los', 'las', 'y'].includes(word.toLowerCase()));

    return ((parts[0]?.[0] ?? '') + (parts[1]?.[0] ?? '')).toUpperCase() || '?';
}

let nextUid = 0;

function makeTarget(state = {}) {
    return {
        uid: ++nextUid,
        uploaded: state.uploaded ?? null,
        clientId: state.clientId ?? null,
        preview: null,
        removeLogo: false,
    };
}

function makeRow(row = {}) {
    return {
        ...makeTarget(row),
        id: row.id ?? null,
        name: row.name ?? '',
        href: row.href ?? '',
        errors: row.errors ?? [],
    };
}

export function entityEditor({ name, identity, rows, errors, clients, suggestions }) {
    return {
        name,
        identity: makeTarget(identity),
        rows: rows.map((row, index) => makeRow({ ...row, errors: errors[index] ?? [] })),
        clients,
        clientsById: Object.fromEntries(clients.map((client) => [client.id, client])),
        suggestions,

        picker: { open: false, target: null, query: '', label: '' },

        initials,

        // ---- Entity -----------------------------------------------------

        get availableSuggestions() {
            const used = this.rows.map((row) => normalize(row.name));

            return this.suggestions.filter((suggestion) => !used.includes(normalize(suggestion)));
        },

        // ---- Logos ------------------------------------------------------

        logoSrc(target) {
            if (target.preview) {
                return target.preview;
            }

            if (target.clientId) {
                return this.clientsById[target.clientId]?.img ?? null;
            }

            return target.removeLogo ? null : target.uploaded;
        },

        logoSource(target) {
            if (target.preview) {
                return 'Imagen nueva (se sube al guardar)';
            }

            if (target.clientId) {
                return `Logo de ${this.clientsById[target.clientId]?.name ?? 'cliente'}`;
            }

            return target.uploaded && !target.removeLogo ? 'Imagen subida' : '';
        },

        fileInput(target) {
            return this.$root.querySelector(`[data-logo-input="${target.uid}"]`);
        },

        resetPreview(target) {
            if (target.preview) {
                URL.revokeObjectURL(target.preview);
            }

            target.preview = null;
        },

        chooseClient(client) {
            const target = this.picker.target;

            this.resetPreview(target);
            this.fileInput(target).value = '';
            target.clientId = client.id;
            target.removeLogo = target.uploaded !== null;
            this.closePicker();
        },

        chooseUpload() {
            this.fileInput(this.picker.target).click();
            this.closePicker();
        },

        pickFile(target, event) {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            this.resetPreview(target);
            target.preview = URL.createObjectURL(file);
            target.clientId = null;
            target.removeLogo = false;
        },

        clearLogo(target) {
            this.resetPreview(target);
            this.fileInput(target).value = '';
            target.clientId = null;
            target.removeLogo = target.uploaded !== null;
        },

        // ---- Picker -----------------------------------------------------

        openPicker(target, label) {
            this.picker = { open: true, target, query: '', label };
            this.$nextTick(() => this.$refs.pickerSearch?.focus());
        },

        closePicker() {
            this.picker.open = false;
        },

        /**
         * Clients matching the search box, best matches for the target first:
         * a client scores one point per word it shares with the target's own
         * name plus the entity's name (so "Concejo" in "Alcaldía de Cubarral"
         * puts "Concejo de Cubarral" on top).
         */
        get pickerClients() {
            const query = normalize(this.picker.query);
            const target = this.picker.target;
            const context = new Set(words(`${target && target !== this.identity ? target.name : ''} ${this.name}`));
            const ownWords = new Set(words(target && target !== this.identity ? target.name : this.name));

            return this.clients
                .filter((client) => !query || normalize(client.name).includes(query))
                .map((client) => {
                    const clientWords = words(client.name);
                    const shared = clientWords.filter((word) => context.has(word)).length;
                    const own = clientWords.filter((word) => ownWords.has(word)).length;

                    return { ...client, score: shared * 2 + own };
                })
                .sort((a, b) => b.score - a.score || a.name.localeCompare(b.name));
        },

        // ---- Rows -------------------------------------------------------

        addRow(name = '') {
            this.rows.push(makeRow({ name }));

            this.$nextTick(() => {
                const inputs = this.$root.querySelectorAll('[data-row-name]');
                inputs[inputs.length - 1]?.focus();
            });
        },

        removeRow(index) {
            const [row] = this.rows.splice(index, 1);
            this.resetPreview(row);
        },

        moveRow(index, step) {
            const target = index + step;

            if (target < 0 || target >= this.rows.length) {
                return;
            }

            const [row] = this.rows.splice(index, 1);
            this.rows.splice(target, 0, row);
        },
    };
}
