import 'focus-visible';

import { hideUrlBarOnLoad, enableIOSActive } from '../services/mobile-boilerplate';

const App = {
  addModule() {},
};
App.addModule('page', () => {
  //-----------------------------------------------------------
  // Private
  //-----------------------------------------------------------

  // const namespace = '.module.page';

  function initCommonUiInside() {}

  //-----------------------------------------------------------
  // Public
  //-----------------------------------------------------------

  return {
    messages: ['fontsactive', 'dataavailable', 'dataunavailable'],
    onmessage(name) {
      switch (name) {
        case 'fontsactive':
          if (process.env.NODE_ENV === 'development') {
            console.log('fonts active');
          }
          break;
        case 'dataavailable':
          if (process.env.NODE_ENV === 'development') {
            console.log('data available');
          }
          break;
        case 'dataunavailable':
          if (process.env.NODE_ENV === 'development') {
            console.log('data unavailable');
          }
          break;
        default:
          break;
      }
    },
    init() {
      initCommonUiInside(document.body);

      // allow event bubbling in iOS: http://www.quirksmode.org/blog/archives/2014/02/mouse_event_bub.html
      document.body.addEventListener('click', () => {});

      hideUrlBarOnLoad();
      enableIOSActive();
    },
    destroy() {},
  };
});
