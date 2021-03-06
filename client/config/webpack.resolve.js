const path = require('path');

const alias = {
  vue$: 'vue/dist/vue.common.js',
  src: path.resolve(__dirname, '../src/js'),
  node_modules: path.resolve(__dirname, '../../node_modules'),
  styles: path.resolve(__dirname, '../src/styles')
};

const extensions = ['.js', '.vue', '.json', '.less'];

module.exports = {
  forWebpack: { alias, extensions },
  forEsLint: {
    'import/resolver': {
      alias: {
        map: Object.keys(alias).map(key => [key, alias[key]]),
        extensions
      }
    }
  }
};
