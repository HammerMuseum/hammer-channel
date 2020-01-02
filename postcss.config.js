module.exports = ctx => ({
    map: !ctx.env || ctx.env !== 'production' ? { inline: false } : false,
    plugins: [
      require('autoprefixer'),
      require('postcss-import'),
      require('postcss-nested'),
      require('postcss-color-function'),
      require('postcss-hexrgba'),
      require('postcss-custom-properties')({
        preserve: false, // TBD, which is better?
      }),
      require('postcss-pxtorem')({
        rootValue: 16,
      }),
    ]
  });
