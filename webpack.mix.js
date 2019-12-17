const mix = require('laravel-mix');
require('laravel-mix-postcss-config');
const StyleLintPlugin = require('stylelint-webpack-plugin');

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
  plugins: [
    new StyleLintPlugin({
      files: '**/*.css',
      context: 'resources/css',
      quiet: true
    }),
  ],
});

// mix.options({
//   postCss: [
//     require("stylelint")(),
//   ]
// })

mix
  .js('resources/js/app.js', 'public/js');

mix
  .postCss('resources/css/app.css', 'public/css')
  .postCssConfig();
