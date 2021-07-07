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
// if ( ! mix.inProduction()) {
//     mix.webpackConfig({
//         devtool: 'inline-source-map'
//     })
// }

mix.sass('resources/sass/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/plugins/bootstrap-selectpicker.js', 'public/js')
    .js('resources/js/material-kit.js', 'public/js')
    .js('resources/js/landing.js', 'public/js')
    .js('resources/js/onboarding-init.js', 'public/js');

mix.webpackConfig(webpack => {
    return {
        resolve: {
            alias: {
                videojs: 'video.js',
                WaveSurfer: 'wavesurfer.js',
                RecordRTC: 'recordrtc'
            }
        },
        plugins: [
            new webpack.ProvidePlugin({
                videojs: 'video.js/dist/video.cjs.js',
                RecordRTC: 'recordrtc'
            })
        ]
    }
})