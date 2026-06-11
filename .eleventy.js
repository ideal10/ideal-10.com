const markdownIt = require("markdown-it");
const yaml = require("js-yaml");

module.exports = function (eleventyConfig) {
  eleventyConfig.addDataExtension("yaml,yml", (contents) => yaml.load(contents));

  // Passthrough copy — assets (sin el CSS, que lo procesa PostCSS)
  eleventyConfig.addPassthroughCopy({ "assets/img": "assets/img" });
  eleventyConfig.addPassthroughCopy({ "assets/files": "assets/files" });
  eleventyConfig.addPassthroughCopy("favicon.ico");

  // Año actual disponible en todas las plantillas como {{ currentYear }}
  eleventyConfig.addGlobalData("currentYear", () => new Date().getFullYear());

  // LiquidJS: dynamicPartials permite {% include variable %} para SVGs dinámicos
  eleventyConfig.setLiquidOptions({
    dynamicPartials: true,
    outputEscape: false,
  });

  // Markdown-it con soporte de HTML inline
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
    templateFormats: ["html", "liquid", "md"],
    htmlTemplateEngine: "liquid",
    markdownTemplateEngine: "liquid",
  };
};
