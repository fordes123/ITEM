const purgecss = require('@fullhuman/postcss-purgecss');

module.exports = () => ({
    plugins: [
        require('autoprefixer'),
        purgecss({
            content: [
                './php/**/*.php',
                './src/**/main.js'
            ],
            safelist: {
                standard: [
                    'show',
                    'showing',
                    'hiding',
                    'collapsing',
                    'collapse-horizontal',
                    'modal-backdrop',
                    'offcanvas-backdrop',
                    /^fa-/,
                    /^fa(s|r|b)$/,
                    /^(bg|text|border)-(success|warning|danger)$/,
                ]
            }
        })
    ]
});
