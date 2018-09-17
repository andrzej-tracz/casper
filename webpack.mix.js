let mix = require('laravel-mix');
const argv = require('yargs').argv;
const profile = argv.env.profile || 'production';

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

if (mix.inProduction()) {
  mix.version();
}

mix
  .webpackConfig({
    resolve: {
      alias: {
        config: path.resolve(__dirname, `resources/js/config/${profile}`),
      }
    }
  })
  .react('resources/js/app.js', 'public/js')
  .react('resources/js/panel/app.js', 'public/js/panel')
  .sass('resources/sass/app.scss', 'public/css');
