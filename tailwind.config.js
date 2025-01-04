module.exports = {
    content: [
        './node_modules/flowbite/**/*.js',
        './_drafts/**/*.html',
        './_drafts/**/*.liquid',
        './_drafts/**/*.md',
        './_includes/**/*.html',
        './_includes/**/*.liquid',
        './_includes/**/*.md',
        './_layouts/**/*.html',
        './_layouts/**/*.liquid',
        './_layouts/**/*.md',
        './_posts/*.html',
        './_posts/*.liquid',
        './_posts/*.md',
        './*.html',
        './*.liquid',
        './*.md',
    ],
    darkMode: 'media',
    plugins: [
        require('flowbite/plugin'),
        require('flowbite-typography')
    ],
}
