const path = require('path');
const webpack = require('webpack');

const Dotenv = require('dotenv-webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = (env, argv) => {
  const mode = argv.mode;
  return {
    plugins: [
      new Dotenv(),
      new MiniCssExtractPlugin({
        filename: '../css/main.css',
        chunkFilename: '[id].css',
      }),
      new StyleLintPlugin({
        files: '**/*.pcss',
        context: 'resources/css',
        quiet: true,
      }),
      new VueLoaderPlugin(),
      new SVGSpritemapPlugin(
        'resources/images/icons/*.svg',
        {
          output: {
            filename: '../images/sprite.svg',
          },
        },
      ),
      // Copy the images folder and optimize all the images
      new CopyWebpackPlugin([
        { from: 'resources/images/static', to: '../images' },
        { from: 'resources/fonts', to: '../fonts' }
      ]),
      // Make sure that the plugin is after any plugins that add images, example `CopyWebpackPlugin`
      new ImageminPlugin({
        bail: false, // Ignore errors on corrupted images
        name: "../images/[name].[ext]",
        test: /\.(jpe?g|png|gif|svg)$/i,
        imageminOptions: {
          plugins: [
            ["gifsicle", { interlaced: true }],
            ["jpegtran", { progressive: true }],
            ["optipng", { optimizationLevel: 5 }],
            ["svgo", {
              plugins: [{ removeViewBox: false }]
            }]
          ],
        }
      })
    ],
    devtool: mode === 'development' ? 'source-map' : false,
    resolve: {
      alias: {
        'vue': 'vue/dist/vue.js',
        'bootstrap-vue$': 'bootstrap-vue/src/index.js'
      }
    },
    entry: {
      entry: path.resolve(__dirname, 'entry.js'),
    },
    output: {
      filename: 'main.js',
      path: path.resolve(__dirname, 'public/js'),
    },
    module: {
      rules: [
        {
          test: /\.p?css$/,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
            },
            {
              loader: 'css-loader',
              options: {
                url: false,
                sourceMap: mode === 'development',
              },
            },
            {
              loader: 'postcss-loader',
            },
          ],
        },
        {
          test: /\.vue$/,
          loader: 'vue-loader',
        },
        {
          test: /\.m?js$/,
          exclude: mode === 'development' ? /node_modules\/(?!bootstrap-vue\/src\/)/ : /(node_modules\/(?!bootstrap-vue\/src\/)|js\/dist)/,
          use: [
            {
              loader: 'babel-loader',
              options: {
                presets: ['@babel/preset-env'],
                plugins: [
                  '@babel/plugin-transform-runtime',
                ],
                sourceMaps: mode === 'development',
              },
            },
            {
              loader: 'eslint-loader',
            },
          ],
        },
      ],
    },
  };
};
