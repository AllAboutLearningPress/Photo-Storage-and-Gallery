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
/** Compiles and renders associated code */

mix.js('resources/js/vendor/pig.js', 'public/js/vendor')
    //.js('resources/js/frontend/index.js', 'public/js/frontend/bundle.js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        // require('tailwindcss'),
    ])
    .sass('resources/sass/main.scss', 'public/css/bundle.css')
    .webpackConfig(require('./webpack.config'));

/** Compiles and extrats vue app files */
mix.js('resources/js/app.js', 'public/js/').vue()
    //.extract('js/vendor.js')
    .extract()
    //.extract(['@vue', 'vue-loader', 'loadash'], 'js/vendor.js')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}
