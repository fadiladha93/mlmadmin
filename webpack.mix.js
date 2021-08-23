const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/default/js/binary.viewer.js', 'public/js')
    .js('resources/default/js/app.js', 'public/js')
    .sass('resources/default/sass/binary.viewer.scss', 'public/css')
    .sass('resources/default/sass/binary.modification.scss', 'public/css');
