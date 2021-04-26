// https://eslint.org/docs/user-guide/configuring
const path = require('path');

module.exports = {
  extends: ['airbnb-base', 'prettier'],
  // https://survivejs.com/webpack/building/code-splitting/#configuring-babel
  parser: 'babel-eslint',
  parserOptions: {
    allowImportExportEverywhere: true,
  },
  env: {
    browser: true,
    node: true,
  },
  rules: {
    'linebreak-style': 0,
    'prettier/prettier': [
      'error',
      {
        endOfLine: 'auto',
      },
    ],

    // https://github.com/prettier/eslint-config-prettier#special-rules
    // https://github.com/airbnb/javascript/tree/master/packages/eslint-config-airbnb-base/rules
    curly: ['error', 'all'],
    'no-tabs': 'error',
    'no-unexpected-multiline': 'error',
    'no-param-reassign': 0,
  },
  plugins: ['prettier'],
  settings: {
    // https://github.com/benmosher/eslint-plugin-import/issues/1178
    'import/resolver': {
      node: {}, // https://github.com/benmosher/eslint-plugin-import/issues/1396#issuecomment-511007063
      webpack: {
        config: path.join(__dirname, './webpack.config.js'),
      },
    },
  },
};
