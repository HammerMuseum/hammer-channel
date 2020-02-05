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

// mix.options({
//   hmrOptions: {
//     host: 'localhost',
//     port: 8222,
//   },
// });

mix
  .js('resources/js/app.js', 'public/js')
  .postCss('resources/css/app.pcss', 'public/css')
  .postCssConfig()
  .webpackConfig({
    // add any webpack dev server config here
    devServer: {
      disableHostCheck: true
    },
    plugins: [
      new StyleLintPlugin({
        files: '**/*.pcss',
        context: 'resources/css',
        quiet: true,
      }),
    ],
  });
