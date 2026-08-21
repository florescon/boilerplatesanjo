const mix = require('laravel-mix');


/*
 |--------------------------------------------------------------------------
 | Apexcharts
 |--------------------------------------------------------------------------
 |
 */

const path = require('path');

mix.js('resources/js/backend/app.js', 'public/js')
    .webpackConfig({
        resolve: {
            alias: {
                'apexcharts/core': path.resolve(
                    __dirname,
                    'node_modules/apexcharts/dist/core.esm.js'
                ),

                'apexcharts/unit': path.resolve(
                    __dirname,
                    'node_modules/apexcharts/dist/unit.esm.js'
                ),

                'apexcharts/unit-shapes': path.resolve(
                    __dirname,
                    'node_modules/apexcharts/dist/unit-shapes.esm.js'
                )
            }
        }
    });


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

mix.setPublicPath('public')
    .setResourceRoot('../') // Turns assets paths in css relative to css file
    .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
    .sass('resources/sass/backend/app.scss', 'css/backend.css')
    .js('resources/js/frontend/app.js', 'js/frontend.js')
    .js('resources/js/backend/app.js', 'js/backend.js')
    .extract([
        'alpinejs',
        'jquery',
        'bootstrap',
        'popper.js',
        'axios',
        'sweetalert2',
        'lodash'
    ])
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
} else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}
