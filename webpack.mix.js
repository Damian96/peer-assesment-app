const mix = require('laravel-mix');
const webpack = require('webpack');
// const jquery = require('jquery');
// const jqueryUI = require('webpack-jquery-ui');

// class JSPlugins {

//     /**
//      * All dependencies that should be installed by Mix.
//      *
//      * @return {Array}
//      */
//     // dependencies() {
//     //     return ['jquery', 'webpack-jquery-ui'];
//     // }

//     /**
//      * Register the component.
//      *
//      * When your component is called, all user parameters
//      * will be passed to this method.
//      *
//      * Ex: register(src, output) {}
//      * Ex: mix.yourPlugin('src/path', 'output/path');
//      *
//      * @param  {*} ...params
//      * @return {void}
//      *
//      */
//     // register() { }

//     /**
//      * Boot the component. This method is triggered after the
//      * user's webpack.mix.js file has executed.
//      */
//     // boot() { }

//     /**
//      * Override the generated webpack configuration.
//      *
//      * @param  {Object} webpackConfig
//      * @return {void}
//      */
//     // webpackConfig(webpackConfig) { }

//     /**
//      * Babel config to be merged with Mix's defaults.
//      *
//      * @return {Object}
//      */
//     // babelConfig() { }

//     /**
//      * Rules to be merged with the master webpack loaders.
//      *
//      * @return {Array|Object}
//      */
//     // webpackRules() {}

//     webpackPlugins() {
//         return [
//             new webpack.ProvidePlugin({
//                 $: 'jquery',
//                 jQuery: 'jquery'
//             })
//         ]
//     }
// }

// mix.extend(JSPlugins.name.toString().toLowerCase(), new JSPlugins());

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
        files: [
            // "resources/views/**/*.php",
            "public/css/*.css",
            "public/js/*.js",
            // "resources/sass/*.scss",
            // "resources/js/*.js"
        ],
        watchEvents: [
            'change', 'add', 'unlink'
        ],
        // logLevel: 'debug',
        // httpModule: 'http2',
        // https: false,
        // online: true,
    });
}

// .jsplugins();
