const mix = require('laravel-mix');
const StyleLintPlugin = require('stylelint-webpack-plugin');
require('laravel-mix-transpile-node-modules');

const dev = !mix.inProduction();

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.copy('resources/images/static', 'public/images', false);
mix.copy('resources/images/favicons', 'public/icons', false);

mix.webpackConfig({
  resolve: {
    fallback: { fs: false }
  },
  plugins: [
    new StyleLintPlugin({
      files: '**/*.pcss',
      context: 'resources/css',
      configFile: '.stylelintrc',
      formatter: require('stylelint').formatters.verbose,
      lintDirtyModulesOnly: true,
      quiet: true,
    }),
  ],
});

mix.js('resources/js/app.js', 'public/js/app.js').vue({ version: 2 });
mix.js('resources/js/embed.js', 'public/js/embed.js').vue({ version: 2 });
mix.postCss('resources/css/app.pcss', 'public/css', [
  require('autoprefixer'),
  require('postcss-import'),
  require('postcss-nested'),
  require('postcss-color-function'),
  require('postcss-hexrgba'),
  require('postcss-custom-properties'),
  require('postcss-pxtorem')({
    rootValue: 16,
  }),
]);

if (dev) {
  mix.sourceMaps();
  mix.options({
    hmrOptions: {
      host: 'localhost',
      port: 8082,
      compress: true,
    },
  });
}

if (!dev) {
  mix.transpileNodeModules(['bootstrap-vue', 'vue-flickity', 'quick-score', 'vuetensils']);
  // Breaks tranpilation for IE11 for some reason...
  mix.version();
}

if (process.env.NODE_ENV !== 'test') {
  mix.extract();
}
