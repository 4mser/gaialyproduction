const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]);

if (mix.inProduction()) {
    mix.version();
}

// Configuración para forzar el uso de HTTPS en los enlaces generados por Mix
mix.webpackConfig({
    output: {
        publicPath: '/',
    },
});
