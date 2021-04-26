export default {
  set(key, value) {
    if (key && value !== undefined) {
      localStorage[key] = JSON.stringify(value);
    }
  },
  // eslint-disable-next-line consistent-return
  get(key) {
    const value = localStorage[key];

    if (value !== undefined) {
      return JSON.parse(localStorage[key]);
    }
  },
  remove(key) {
    if (Object.prototype.hasOwnProperty.call(localStorage, key)) {
      delete localStorage[key];
    }
  },
  clear() {
    localStorage.clear();
  },
};
