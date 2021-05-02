/**
 * Make tabbing trapped within a "popup" container (i.e. modal)
 * @param showEvent{String} - The event which is triggered when the container shows
 * @param hideEvent{String} - The event which is triggered when the container hides
 * @param testIfShouldTrap{Function} - tester function to determine if focus should be kept being trapped, or not
 */
export function patchFocus(showEvent, hideEvent, testIfShouldTrap = () => true) {
  // https://github.com/twbs/bootstrap/issues/28481#issuecomment-763017750
  const TAB_KEY = 9;
  const focusableSelectors = [
    'a[href]:not([tabindex^="-"])',
    'area[href]:not([tabindex^="-"])',
    'input:not([type="hidden"]):not([type="radio"]):not([disabled]):not([tabindex^="-"])',
    'input[type="radio"]:not([disabled]):not([tabindex^="-"]):checked',
    'select:not([disabled]):not([tabindex^="-"])',
    'textarea:not([disabled]):not([tabindex^="-"])',
    'button:not([disabled]):not([tabindex^="-"])',
    'iframe:not([tabindex^="-"])',
    'audio[controls]:not([tabindex^="-"])',
    'video[controls]:not([tabindex^="-"])',
    '[contenteditable]:not([tabindex^="-"])',
    '[tabindex]:not([tabindex^="-"])',
  ];

  function getFocusableChildren(node) {
    return [].filter.call(
      node.querySelectorAll(focusableSelectors.join(',')),
      (child) => !!(child.offsetWidth || child.offsetHeight || child.getClientRects().length)
    );
  }

  function trapTabKey(node, event) {
    const focusableChildren = getFocusableChildren(node);
    const focusedItemIndex = focusableChildren.indexOf(document.activeElement);

    if (event.shiftKey && focusedItemIndex === 0) {
      focusableChildren[focusableChildren.length - 1].focus();
      event.preventDefault();
    } else if (!event.shiftKey && focusedItemIndex === focusableChildren.length - 1) {
      focusableChildren[0].focus();
      event.preventDefault();
    }
  }

  function bindKeypress(event, container) {
    if (container && event.which === TAB_KEY && testIfShouldTrap()) {
      trapTabKey(container, event);
    }
  }

  const handler = {
    container: null,
    handleEvent(e) {
      bindKeypress(e, this.container);
    },
  };

  function onShow(evt) {
    handler.container = evt.target;
    document.addEventListener('keydown', handler);
  }
  function onHide(evt) {
    handler.container = null;
    document.removeEventListener('keydown', handler);
  }

  document.addEventListener(showEvent, onShow);
  document.addEventListener(hideEvent, onHide);

  function destroy() {
    document.removeEventListener(showEvent, onShow);
    document.removeEventListener(hideEvent, onHide);
  }

  return destroy;
}

/**
 * Returns a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds. If `immediate` is passed, trigger the function on the
 * leading edge, instead of the trailing.
 * @param wait
 * @param func
 * @param immediate
 * @returns {function(...[*]=)}
 */
export function debounce(wait, func, immediate) {
  let timeout;
  return function () {
    const context = this,
      args = arguments;
    const later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}
