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


mix.js('resources/js/public.js', 'public/js').vue()
    //.extract('js/public_vendor.js')
    //.autoload({})
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}
