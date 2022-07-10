const mix = require('laravel-mix');
require("dotenv").config();
const tailwindcss = require('tailwindcss');
const autoprefixer = require("autoprefixer");

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

let assetDir = 'assets';

mix.options({
    fileLoaderDirs: {
        images: `${assetDir}/img`,
        fonts: `${assetDir}/fonts`,
    },
    runtimeChunkPath: `${assetDir}/js/control`,
    postCss: [tailwindcss('./tailwind.config.js')],
});

mix.setPublicPath('public')
    .setResourceRoot('/');

mix.js('resources/assets/js/control/app.js', `public/${assetDir}/js/control`)
    .extract(['pikaday', 'EasyMDE'],`public/${assetDir}/js/control/vendor.js`)
    .sass('resources/assets/scss/control/app.scss', `public/${assetDir}/css/control`)
    .sourceMaps();

if (mix.inProduction()) {
    mix
        .version()
}
