const Dotenv = require('dotenv-webpack');
const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
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
    new ImageminPlugin({ test: /\.(jpe?g|png)$/i })
  ],
  devtool: false,
  resolve: {
    alias: {vue: 'vue/dist/vue.js'}
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
              sourceMap: false,
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
        exclude: /(node_modules|js\/dist)/,
        use: [
          {
            loader: 'babel-loader',
            options: {
              presets: ['@babel/preset-env'],
              sourceMaps: false,
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
