module.exports = function conf(api) {
  const presets = [['@babel/preset-env', { modules: false }]];
  const plugins = ['@babel/plugin-syntax-dynamic-import'];

  api.cache(true);

  return {
    sourceMaps: true,
    presets,
    plugins,
  };
};
