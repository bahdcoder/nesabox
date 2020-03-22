let mix = require('laravel-mix')
require('laravel-mix-purgecss')

mix.js('resources/js/main.js', 'public/js/main.js')
    .postCss('resources/css/app.css', 'public/css/app.css', [
        require('tailwindcss')
    ])
    .purgeCss({
        extensions: ['html', 'md', 'js', 'php', 'vue'],
        folders: ['source'],
        whitelistPatterns: [/language/, /hljs/, /algolia/]
    })
    .webpackConfig({
        output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
        resolve: {
            alias: {
                vue$: 'vue/dist/vue.runtime.esm.js',
                '@': path.resolve('resources/js')
            }
        }
    })
    .version()
