const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    theme: {
        extend: {
            fontFamily: {
                mono: ['monospace'],
                sans: ['Graphik', ...defaultTheme.fontFamily.sans],
                serif: ['DM Serif Display', ...defaultTheme.fontFamily.serif]
            },
            lineHeight: {
                normal: '1.6',
                loose: '1.75'
            },
            maxWidth: {
                none: 'none',
                '7xl': '80rem',
                '8xl': '88rem'
            },
            spacing: {
                '7': '1.75rem',
                '9': '2.25rem'
            },
            boxShadow: {
                lg:
                    '0 -1px 27px 0 rgba(0, 0, 0, 0.04), 0 4px 15px 0 rgba(0, 0, 0, 0.08)'
            },
            colors: {
                ...defaultTheme.colors,
                yellow: '#f5c06f',
                'sky-blue': '#f3f8ff',
                hero: '#1b1742',
                'primary-green': '#47B881',
                'svg-green': '#56cad8',
                'svg-green-light': '#8bdde4',
                'hero-background': '#F8FAFF',
                'sha-green': {
                    100: '#E9F8F3',
                    200: '#C7EDE1',
                    300: '#A6E3CE',
                    400: '#63CDAA',
                    500: '#20B885',
                    600: '#1DA678',
                    700: '#136E50',
                    800: '#0E533C',
                    900: '#0A3728'
                }
            }
        },
        fontSize: {
            ...defaultTheme.fontSize,
            xxs: '.625rem',
            xs: '.8rem',
            sm: '.925rem',
            base: '1rem',
            lg: '1.125rem',
            xl: '1.25rem',
            '2xl': '1.5rem',
            '3xl': '1.75rem',
            '4xl': '2.125rem',
            '3rem': '3rem',
            '5xl': '2.625rem'
        }
    },
    variants: {
        borderRadius: ['responsive', 'focus'],
        borderWidth: ['responsive', 'active', 'focus'],
        width: ['responsive', 'focus']
    },
    plugins: [
        function({ addUtilities }) {
            const newUtilities = {
                '.transition-fast': {
                    transition: 'all .2s ease-out'
                },
                '.transition': {
                    transition: 'all .5s ease-out'
                }
            }
            addUtilities(newUtilities)
        },
        require('@tailwindcss/ui')
    ]
}
