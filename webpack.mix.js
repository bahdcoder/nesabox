let mix = require('laravel-mix')

const purgecss = require('@fullhuman/postcss-purgecss')({
    content: [
        './resources/js/**/*.vue',
        './resources/views/**/*.php',
        './resources/css/**/*.css'
    ],
    defaultExtractor: content => content.match(/[\w-/.:]+(?<!:)/g) || []
})

mix.js('resources/js/main.js', 'public/js/main.js')
    .postCss('resources/css/app.css', 'public/css/app.css', [
        require('tailwindcss'),
        process.env.NODE_ENV === 'production' ? purgecss : undefined
    ].filter(Boolean))
    .webpackConfig({
        output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
        resolve: {
            alias: {
                '@': path.resolve('resources/js')
            }
        }
    })
    .version()
