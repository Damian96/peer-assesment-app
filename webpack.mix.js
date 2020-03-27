const mix = require('laravel-mix');
// const webpack = require('webpack');

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

mix.js('resources/js/app.js', 'public/js')
    .extract(['jquery', 'papaparse', 'popper.js'])
    .sourceMaps();

mix.sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
} else {
    mix.browserSync({
        proxy: process.env.APP_URL,
        https: false,
        logFileChanges: true,
        // tunnel: true,
        // online: true,
        files: [
            "public/css/app.css",
            "public/js/*.js",
        ],
        watchEvents: [
            'add', 'unlink'
        ],
    });
}
