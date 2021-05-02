const sass = require('sass');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = {
  mode: 'development',
  entry: './src/js/index.js',
  output: {
    path: __dirname + '/src/js',
    filename: 'bundle.js',
  },
  plugins: [
    new CopyWebpackPlugin({
      patterns: [
        {
          from: './src/css/main.scss',
          to: '../css/bundle.css',
          transform (content, path) {
            const result = sass.renderSync({
              file: path
            });

            return result.css.toString();
          },
        }
      ],
    }),
  ],
}
