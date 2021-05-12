/*
 * A list of media-queries used in CSS (mostly values from bootstrap). Change these values
 * if you change them in CSS (i.e. when changing breakpoints values in `customised-bootstrap.scss`).
 *
 * The mobile-first approach is used (`min-width` and up), which should be a preferred way to write media-queries
 * */
const mq = {
  xs: '(min-width: 0)',
  sm: '(min-width: 576px)',
  md: '(min-width: 768px)',
  lg: '(min-width: 992px)',
  xl: '(min-width: 1200px)',
  xxl: '(min-width: 1400px)',
};

export default mq;
