/**
 * Site entry point.
 *
 * Loaded once per page from `_layouts/default.html` as a module. Each
 * feature initializer is responsible for being a no-op when its required
 * DOM is not present, so this file does not need to know which page it is
 * running on.
 */
import { initClientsTicker } from "./clients-ticker.js";

function boot() {
    initClientsTicker();
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot, { once: true });
} else {
    boot();
}
