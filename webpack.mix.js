const mix = require('laravel-mix');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');

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
  .copy('resources/images/static', 'public/images', false)
  .copy('resources/fonts', 'public/fonts', false)
  .webpackConfig({
    // add any webpack dev server config here
    devServer: {
      disableHostCheck: true,
    },
    plugins: [
      new SVGSpritemapPlugin(
        'resources/images/icons/*.svg',
        {
          output: {
            filename: 'images/sprite.svg',
          },
        },
      ),
      new StyleLintPlugin({
        files: '**/*.pcss',
        context: 'resources/css',
        quiet: true,
      }),
      // Make sure that the plugin is after any plugins that add images, example `CopyWebpackPlugin`
      new ImageminPlugin({
        bail: false, // Ignore errors on corrupted images
        name: '../images/[name].[ext]',
        test: /\.(jpe?g|png|gif|svg)$/i,
        imageminOptions: {
          plugins: [
            ['gifsicle', { interlaced: true }],
            ['jpegtran', { progressive: true }],
            ['optipng', { optimizationLevel: 5 }],
            ['svgo', {
              plugins: [{ removeViewBox: false }],
            }],
          ],
        },
      }),
    ],
    devtool: dev ? 'source-map' : false,
  })
  .sourceMaps(dev);
