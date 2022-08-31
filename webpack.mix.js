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

mix.js('resources/js/app.js', 'public/js').version()
.postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
])

.styles([
    /** Plugins */
    'resources/vendors/iziToast/dist/css/iziToast.css',
], 'public/css/styles.css').version()
.scripts([
    /** Plugins */
    'resources/vendors/iziToast/dist/js/iziToast.min.js',
    'resources/vendors/jquery/jquery-3.6.0.min.js',
    /** */
    'resources/js/helper.js',
], 'public/js/scripts.js').version()

/** Build Vendor Assets, CSS and JS*/

.copy('resources/vendors/notus-js/img', 'public/dist/notus-js/img').version()
.copy('resources/vendors/notus-js/vendor/@fortawesome/fontawesome-free/webfonts', 'public/dist/notus-js/webfonts').version()
.copy('resources/vendors/notus-js/vendor/js', 'public/dist/notus-js/vendor/js').version()
.styles([
    'resources/vendors/notus-js/css/*',
    'resources/vendors/notus-js/vendor/@fortawesome/fontawesome-free/css/*'
], 'public/dist/notus-js/css/styles.css').version()
.scripts([
    'resources/vendors/notus-js/js/*',
], 'public/dist/notus-js/js/app.js').version();
