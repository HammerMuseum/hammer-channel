const mix = require('laravel-mix');
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


mix.options({
  hmrOptions: {
    host: 'localhost',
    port: 8082,
  },
});

const dev = !mix.inProduction();
mix
  .js('resources/js/app.js', 'public/js')
  .postCss('resources/css/app.pcss', 'public/css', [
    require('autoprefixer'),
    require('postcss-import'),
    require('postcss-nested'),
    require('postcss-color-function'),
    require('postcss-hexrgba'),
    require('postcss-custom-properties')({
      preserve: false,
    }),
    require('postcss-pxtorem')({
      rootValue: 16,
    }),
  ])
  .webpackConfig({
    // add any webpack dev server config here
    devServer: {
      disableHostCheck: true,
    },
    plugins: [
      new StyleLintPlugin({
        files: '**/*.pcss',
        context: 'resources/css',
        quiet: true,
      }),
    ],
    devtool: dev ? 'source-map' : false,
  })
  .sourceMaps(dev);
