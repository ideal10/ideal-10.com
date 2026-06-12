/**
 * Eleventy configuration.
 *
 * The site is a plain Eleventy build — no SSR, no client-side framework.
 *
 *   build  → Eleventy emits to _site; Tailwind CLI compiles main.css.
 *   serve  → `eleventy --serve` + `tailwindcss --watch` run in parallel via
 *            npm-run-all (see package.json `serve` script).
 *
 * Note on the CSS pipeline: `assets/css` is intentionally NOT a passthrough.
 * The Tailwind CLI writes `_site/assets/css/main.css` directly, so passing
 * the raw source through would let Eleventy overwrite the compiled output on
 * every rebuild. The source file is added as a watch target instead and the
 * dev server watches the compiled output, so saving either templates or CSS
 * triggers a browser reload as soon as Tailwind finishes the rebuild.
 */
const markdownIt = require("markdown-it");
const yaml = require("js-yaml");

module.exports = function (eleventyConfig) {
    // YAML support for `_data/*.yml` files.
    eleventyConfig.addDataExtension("yaml,yml", (contents) =>
        yaml.load(contents),
    );

    // Returns the first element of an array or string. Used by templates that
    // need to unwrap a single-item list or check a leading character.
    eleventyConfig.addFilter("first", (value) => (value ? value[0] : ""));

    // Static asset passthrough — images, files, JS modules, favicon.
    eleventyConfig.addPassthroughCopy({ "assets/img": "assets/img" });
    eleventyConfig.addPassthroughCopy({ "assets/files": "assets/files" });
    eleventyConfig.addPassthroughCopy({ "assets/js": "assets/js" });
    eleventyConfig.addPassthroughCopy("favicon.ico");

    // Watch JS and CSS sources so template-side edits trigger a rebuild.
    eleventyConfig.addWatchTarget("./assets/js/");
    eleventyConfig.addWatchTarget("./assets/css/");

    // Reload the browser when Tailwind rewrites the compiled stylesheet.
    eleventyConfig.setServerOptions({
        watch: ["_site/assets/css/main.css"],
    });

    // Globals.
    eleventyConfig.addGlobalData("currentYear", () => new Date().getFullYear());

    // Markdown with inline HTML support (used by `componentes/*.md`).
    const md = markdownIt({ html: true, linkify: true, typographer: true });
    eleventyConfig.setLibrary("md", md);

    return {
        dir: {
            input: ".",
            output: "_site",
            includes: "_includes",
            layouts: "_layouts",
            data: "_data",
        },
        templateFormats: ["html", "njk", "md"],
        htmlTemplateEngine: "njk",
        markdownTemplateEngine: "njk",
    };
};
