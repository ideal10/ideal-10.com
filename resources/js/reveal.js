/**
 * Reveal-on-scroll.
 *
 * Adds a `data-reveal` attribute to a curated set of elements (cards,
 * section headings, images, CTAs) and observes them with IntersectionObserver.
 * When an element enters the viewport, `is-visible` is toggled and CSS handles
 * the fade + slide transition.
 *
 * Elements already tagged with `data-reveal` in markup are respected. Skips
 * anything under `[data-no-reveal]` and honors `prefers-reduced-motion`.
 */
const REVEAL_SELECTORS = [
    ".card",
    ".card-interactive",
    ".card-soft",
    ".card-on-brand",
    ".kicker",
    ".kicker-on-brand",
    ".heading-section",
    ".heading-page",
    ".lead",
    ".lead-lg",
    ".btn-primary",
    ".btn-secondary",
];

export function initReveal() {
    if (typeof window === "undefined") return;

    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    if (prefersReducedMotion) return;

    const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
    const candidates = document.querySelectorAll(REVEAL_SELECTORS.join(","));

    for (const el of candidates) {
        if (el.hasAttribute("data-reveal")) continue;
        if (el.closest("[data-no-reveal]")) continue;

        // Skip elements that are already on screen — animating them would
        // cause a visible flash since JS runs after the first paint.
        const rect = el.getBoundingClientRect();
        if (rect.top < viewportHeight && rect.bottom > 0) continue;

        el.setAttribute("data-reveal", "");
    }

    const staggerContainers = document.querySelectorAll("[data-reveal-stagger]");
    for (const container of staggerContainers) {
        const items = container.querySelectorAll("[data-reveal]");
        items.forEach((el, i) => {
            if (!el.hasAttribute("data-reveal-delay")) {
                el.setAttribute("data-reveal-delay", String(Math.min(i + 1, 5)));
            }
        });
    }

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    observer.unobserve(entry.target);
                }
            }
        },
        { threshold: 0.12, rootMargin: "0px 0px -40px 0px" },
    );

    for (const el of document.querySelectorAll("[data-reveal]")) {
        observer.observe(el);
    }
}
