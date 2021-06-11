const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()
    //.copy('resources/js/vendor/pig.min.js', 'public/js/vendor/pig.min.js')
    .copy('resources/js/vendor/pig.js', 'public/js/vendor/pig.min.js')
    .js('resources/js/frontend/index.js', 'public/js/frontend/bundle.js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .sass('resources/sass/main.scss', 'public/css/bundle.css')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}
