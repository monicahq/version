const path = require('path');

module.exports = {
  output: {
    chunkFilename: 'js/[name].js?id=[chunkhash]'
  },
  resolve: {
    alias: {
      vue$: path.join(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
      '@': path.resolve('resources/js'),
    },
  },
};
