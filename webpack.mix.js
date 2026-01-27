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


mix.setResourceRoot('resources')
    .setPublicPath('public')
    .sourceMaps(true, 'source-map')
    .copyDirectory('resources/img', 'public/img')
    .sass('resources/scss/widgets.scss', 'public/css/widgets.css')
    .js('resources/js/widgets.js', 'public/js/widgets.js')
    .copy('resources/js/modernizr.min.js', 'public/js/modernizr.min.js')
    .copy('resources/js/utility.js', 'public/js/utility.js')
    .options({
        processCssUrls: false
    })
    .minify('public/js/widgets.js', 'public/js/widgets.js')
    .version();
