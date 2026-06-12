/**
 * Infinite-scrolling clients ticker.
 *
 * The DOM structure expected is:
 *   <div class="clients-ticker" data-clients-ticker>
 *     <ul class="clients-track" data-clients-ticker-track>
 *       <li>...</li>
 *       ...
 *     </ul>
 *   </div>
 *
 * On init the script clones the items inline so the track contains the
 * sequence twice, measures the offset of the first clone, and sets the CSS
 * variable `--clients-shift` to that distance. The CSS animation
 * `clients-scroll` (defined in main.css) translates by exactly that amount,
 * so the loop is seamless.
 *
 * Activation is signalled by `data-animated="true"` on the track. The CSS
 * pauses the animation on hover via the same attribute selector.
 */
export function initClientsTicker(root = document) {
    const tickers = root.querySelectorAll("[data-clients-ticker]");
    tickers.forEach((ticker) => {
        const track = ticker.querySelector("[data-clients-ticker-track]");
        if (!track) return;

        const originals = Array.from(track.children);
        if (originals.length === 0) return;

        // Duplicate the sequence so we always have content past the wrap point.
        originals.forEach((node) => {
            const clone = node.cloneNode(true);
            clone.setAttribute("aria-hidden", "true");
            track.appendChild(clone);
        });

        // Measure how far the first clone sits past the original sequence —
        // that's the exact translation needed for a seamless loop.
        const firstClone = track.children[originals.length];
        if (!firstClone) return;

        const shift = Math.round(
            firstClone.getBoundingClientRect().left -
                track.getBoundingClientRect().left,
        );
        if (shift <= 0) return;

        track.style.setProperty("--clients-shift", `${shift}px`);
        track.dataset.animated = "true";
    });
}
