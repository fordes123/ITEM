const purgecss = require('@fullhuman/postcss-purgecss');

module.exports = ({ env }) => ({
    plugins: [
        require('autoprefixer'),
        ...(env === 'production'
            ? [purgecss({
                content: [
                    './php/**/*.php',
                    './src/**/main.js'
                ],
                safelist: {
                    standard: [
                        /^fa-/,
                        /^bg-(success|warning|danger)$/,
                        /^text-(success|warning|danger)$/,
                        /^border-(success|warning|danger)$/
                    ]
                }
            })]
            : [])
    ]
});
