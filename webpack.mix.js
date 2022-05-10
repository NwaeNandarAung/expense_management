const mix = require("laravel-mix");

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

mix.webpackConfig({
    output: {
        publicPath: "/",
        chunkFilename: "js/[name].js",
    },
});

mix.options({
    processCssUrls: false,
});

mix.js("resources/js/app.js", "public/js")
    .js(
        [
            "resources/assets/js/app.js",
            "resources/assets/js/jquery.min.js",
            "resources/assets/js/jquery.core.js",
            "resources/assets/js/bootstrap.bundle.min.js",
        ],
        "public/js/all.js"
    )
    .sass("resources/sass/app.scss", "public/css")
    .styles(
        [
            "resources/assets/css/bootstrap.min.css",
            "resources/assets/css/icons.css",
            "resources/assets/css/style.min.css",
        ],
        "public/css/all.css"
    );
