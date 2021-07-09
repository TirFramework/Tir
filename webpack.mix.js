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

mix.js('resources/js/index.js', 'public/js')
    .react()
    .postCss('resources/css/tailwindcss.css', 'public/css', [
        require('tailwindcss'),
    ])
    .less('resources/less/app.less', 'public/css',
        {
            lessOptions: {
                modifyVars: {
                    'primary-color': '#00ff00',
                },
                javascriptEnabled: true,
            } ,
        });

mix.browserSync('localhost:8000');
