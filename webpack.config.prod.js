const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
  plugins: [
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
  ],
  devtool: false,
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
        test: /\.pcss$/,
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