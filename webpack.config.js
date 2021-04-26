require('core-js/stable');
require('regenerator-runtime/runtime');

const path = require('path');
const fs = require('fs');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

function getFiles(directoryPath) {
  let files = [];

  if (fs.existsSync(directoryPath)) {
    try {
      files = fs.readdirSync(directoryPath);
    } catch (err) {
      console.error(`Could not list the directory: ${directoryPath}`, err);
      process.exit(1);
    }
  }

  return files;
}

const pageNames = [];
const entries = {
  main: `./js/index.js`,
  styles: `./js/styles.css.js`,
};

getFiles(path.resolve(__dirname, 'src')).forEach((file) => {
  const parsedPath = path.parse(file);

  if (parsedPath.ext === '.html') {
    pageNames.push(parsedPath.name);
  }
});

module.exports = (env, argv) => {
  const config = {
    mode: argv.mode,
    context: path.resolve(__dirname, 'src'),
    entry: entries,
    output: {
      path: path.resolve(__dirname, 'dist'),
      filename: 'js/[name].js',
      clean: true,
    },
    optimization: {
      moduleIds: 'deterministic',
      minimizer: [`...`, new CssMinimizerPlugin()],
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /(node_modules|vendor)/,
          use: ['babel-loader', 'eslint-loader'],
        },
        {
          test: /\.(css|scss)$/,
          use: [
            {
              loader: argv.mode === 'production' ? MiniCssExtractPlugin.loader : 'style-loader',
            },
            {
              loader: 'css-loader',
              options: {
                sourceMap: false,
                importLoaders: 2,
                url: false,
              },
            },
            {
              loader: 'postcss-loader',
            },
            argv.mode === 'production'
              ? {
                  loader: 'sass-loader',
                  options: {
                    sassOptions: {
                      outputStyle: 'expanded',
                    },
                  },
                }
              : {
                  loader: 'fast-sass-loader',
                  options: {
                    resolveURLs: false,
                  },
                },
          ],
        },
        // https://webpack.js.org/guides/asset-modules/
        {
          test: /\.(png|jpg|jpeg|gif|svg)$/,
          type: 'asset/resource',
        },
      ],
    },
    plugins: [
      new CopyWebpackPlugin({
        patterns: [
          {
            from: '.',
            globOptions: {
              ignore: [`**/js/**`, `**/css/**`, '**.html', '**.js', '**.scss'],
            },
          },
        ],
      }),
      ...pageNames.map(
        (name) =>
          new HtmlWebpackPlugin({
            filename: `${name}.html`,
            template: `./${name}.html`,
            minify: 'false',
          })
      ),
    ],
  };

  if (argv.mode === 'production') {
    config.plugins.push(
      new MiniCssExtractPlugin({
        filename: 'css/bundle.css',
      }),
      new RemoveEmptyScriptsPlugin({
        extensions: ['less', 'scss', 'css', 'css.js'],
      })
    );
  } else {
    config.devServer = {
      watchFiles: ['src/**/*.html'],
    };
  }

  return config;
};
