/**
 * Make tabbing trapped within a "popup" container (i.e. modal)
 * @param showEvent - The event which is triggered when the container shows
 * @param hideEvent - The event which is triggered when the container hides
 */
function patchFocus(showEvent, hideEvent) {
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
    if (container && event.which === TAB_KEY) {
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
function debounce(wait, func, immediate) {
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

/**
 * Toggle sidebar
 */
const Sidebar = (function () {
  const activeKlass = 'is-open';
  const togglerKlass = 'js-sidebar-toggle';

  // DOM elements to dereference on destroy
  let sidebar = null;
  let backdrop = null;
  let mqTestElem = null;
  let isInited = false;

  // event handlers to unbind on destroy
  let onTogglersClick;
  let onBackdropClick;
  let onEscapeKey;
  let resizeHandler;
  let orientationchangeHandler;

  // `patchFocus()` cleanup function
  let destroyPatchedFocus;

  return {
    init() {
      sidebar = document.querySelector('.sidebar');

      if (isInited || !sidebar) {
        return;
      }

      isInited = true;
      backdrop = document.querySelector('.sidebar-backdrop');
      mqTestElem = document.querySelector('.sidebar-dumb-mq-tester'); // elem is `display: block` on `md` bootstrap media query;

      /**
       * Toggle visibility of a sidebar (i.e. when it is hidden by default on narrow screen)
       * @param force{Boolean} - optional visibility state
       * @param resetFocus{Boolean} - optional flag to reset a focus to the element which was focused before toggling happened
       * @param toggler{HTMLElement} - optional DOM element by which the action was performed
       */
      function toggleSidebar(toggler, force, resetFocus = true) {
        const wasOpened = sidebar.classList.contains(activeKlass);

        if (wasOpened === force) {
          return;
        }

        sidebar.classList.toggle(activeKlass, force);
        sidebar.dispatchEvent(
          new CustomEvent(wasOpened ? 'sidebarhide' : 'sidebarshow', { bubbles: true })
        );
        setTimeout(() => {
          if (resetFocus) {
            (wasOpened ? sidebar._trigger : sidebar).focus();
          }

          sidebar._trigger = toggler;
        }, 50);
      }

      // hide sidebar which wasn't hidden and then bootstrap `md` mq fired up
      function cleanup() {
        if (!sidebar.classList.contains(activeKlass)) {
          return;
        }

        const display = window.getComputedStyle(mqTestElem, null).display;

        if (display === 'block') {
          toggleSidebar(undefined, false);
        }
      }

      // activate sidebar by clicking on specific toggler elements
      onTogglersClick = (e) => {
        const toggler = e.target.closest(`.${togglerKlass}`);

        toggler && toggleSidebar(toggler);
      };

      // handle the click on the backdrop
      onBackdropClick = () => toggleSidebar(undefined, false, false);

      // handle escape key
      onEscapeKey = (e) => {
        if (e.key === 'Escape') {
          toggleSidebar(undefined, false);
        }
      };

      // window resize handlers
      resizeHandler = debounce(50, cleanup);
      orientationchangeHandler = debounce(0, cleanup);

      destroyPatchedFocus = patchFocus('sidebarshow', 'sidebarhide');
      document.addEventListener('click', onTogglersClick);
      document.addEventListener('keydown', onEscapeKey);
      backdrop.addEventListener('click', onBackdropClick);
      window.addEventListener('resize', resizeHandler);
      window.addEventListener('orientationchange', orientationchangeHandler);
    },
    destroy() {
      if (isInited) {
        destroyPatchedFocus();
        document.removeEventListener('click', onTogglersClick);
        document.removeEventListener('keydown', onEscapeKey);
        backdrop.removeEventListener('click', onBackdropClick);
        window.removeEventListener('resize', resizeHandler);
        window.removeEventListener('orientationchange', orientationchangeHandler);

        sidebar = null;
        backdrop = null;
        mqTestElem = null;
        isInited = false;
      }
    },
  };
})();

/**
 * Search field
 */
const Suggester = (function () {
  const activeKlass = 'is-searchable';
  const suggestingKlass = 'is-suggesting';
  const highlightedSuggestionKlass = 'is-highlighted';

  // DOM elements to dereference on destroy
  let header = null;
  let search = null;
  let field = null;
  let input = null;
  let suggester = null;
  let mqTestElem = null;
  let isInited = false;

  // event handlers to unbind on destroy
  let onSearchSubmit;
  let onSearchInput;
  let onFieldTogglerClick;
  let onFieldFocusout;
  let onEscapeKey;
  let onSuggestionItemClick;
  let onSearchKeydown;
  let resizeHandler;
  let orientationchangeHandler;

  return {
    init() {
      header = document.querySelector('.js-search-header');

      if (isInited || !header) {
        return;
      }

      isInited = true;

      search = header.querySelector('.js-search');
      field = search.querySelector('.js-search__field');
      input = search.querySelector('.js-search__input');
      suggester = search.querySelector('.js-search__suggest');
      mqTestElem = document.querySelector('.search-dumb-mq-tester');

      /**
       * Remove "highlighted" state for any highlighted suggestion item
       */
      function unhighlightSuggestion() {
        const highlighted = suggester.querySelector(`.${highlightedSuggestionKlass}`);
        highlighted && highlighted.classList.remove(highlightedSuggestionKlass);
      }

      /**
       * The action upon suggestion item is "selected"
       * @param suggestionElem{HTMLElement} - DOM element for selected suggestion item
       */
      function selectSuggestion(suggestionElem) {
        alert(`suggesting: ${suggestionElem.textContent.trim()}`);
      }

      /**
       * Toggle "activated" state for the suggestions box
       * @param inputElem{HTMLElement} - required parameter for the `<input>` for which suggestions box is being toggled
       */
      function toggleSuggester(inputElem) {
        const isToggled = inputElem.form.classList.toggle(suggestingKlass, inputElem.value.length);

        if (!isToggled) {
          unhighlightSuggestion();
        }
      }

      /**
       * Toggle the "activated" state of a search field (i.e. when it is hidden by default on narrow screen)
       * @param force{Boolean} - optional activated state
       * @param resetFocus{Boolean} - optional flag to reset a focus to the element which was focused before toggling happened
       * @param togglerElem{HTMLElement} - optional DOM element by which the action was performed
       */
      function toggleSearchfield({ force, resetFocus = false, togglerElem } = {}) {
        let isToggled = header.classList.toggle(activeKlass, force);

        if (isToggled) {
          input.focus();
        } else {
          input.value = '';
          search.classList.remove(suggestingKlass);
          unhighlightSuggestion();
        }

        setTimeout(() => {
          if (resetFocus && !isToggled && header._trigger) {
            header._trigger.focus();
          }

          header._trigger = togglerElem;
        }, 0);
      }

      // deactivate search field if needed, based bootstrap `md` media-query
      function cleanup() {
        if (!header.classList.contains(activeKlass)) {
          return;
        }

        const display = window.getComputedStyle(mqTestElem, null).display;

        if (display === 'block') {
          toggleSearchfield();
        }
      }

      // handle search form `submit` event
      onSearchSubmit = (e) => {
        e.preventDefault();
      };
      // handle `input` event inside search input
      onSearchInput = (e) => toggleSuggester(e.target);
      // activate search field by clicking on specific toggler elements
      onFieldTogglerClick = (e) => {
        const togglerElem = e.target.closest('.js-search__field-toggle');

        if (togglerElem) {
          toggleSearchfield({
            togglerElem,
            resetFocus: !e.detail,
          });
        }
      };
      // inactivate search field on loosing focus
      onFieldFocusout = (e) => {
        if (!field.contains(e.relatedTarget)) {
          toggleSearchfield({
            force: false,
            resetFocus: true,
          });
        }
      };
      // close search field on escape
      onEscapeKey = (e) => {
        if (e.key === 'Escape') {
          toggleSearchfield({
            force: false,
            resetFocus: true,
          });
        }
      };
      // handle suggestion item selection
      onSuggestionItemClick = (e) => {
        const item = e.target.closest('.js-search__suggest-item');

        if (item) {
          unhighlightSuggestion();
          selectSuggestion(item);
        }
      };
      // handle up/down arrows to highlight suggestions
      onSearchKeydown = (e) => {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
          e.preventDefault();

          const suggesterItems = [].slice.call(
            suggester.querySelectorAll('.js-search__suggest-item')
          );
          let highlightedIndex = suggesterItems.findIndex((el) =>
            el.classList.contains(highlightedSuggestionKlass)
          );
          const increment = e.key === 'ArrowDown' ? 1 : -1;
          const nextIndex = highlightedIndex + increment;
          const next = suggesterItems[nextIndex < -1 ? suggesterItems.length - 1 : nextIndex];

          unhighlightSuggestion();
          next && next.classList.add(highlightedSuggestionKlass);
        }

        if (e.key === 'Enter') {
          const highlightedItem = suggester.querySelector(`.${highlightedSuggestionKlass}`);

          if (highlightedItem) {
            selectSuggestion(highlightedItem);
          } else {
            search.submit();
          }
        }
      };

      // window resize handlers
      resizeHandler = debounce(50, cleanup);
      orientationchangeHandler = debounce(0, cleanup);

      header.addEventListener('click', onFieldTogglerClick);
      search.addEventListener('submit', onSearchSubmit);
      input.addEventListener('input', onSearchInput);
      field.addEventListener('focusout', onFieldFocusout);
      document.addEventListener('keydown', onEscapeKey);
      suggester.addEventListener('click', onSuggestionItemClick);
      input.addEventListener('keydown', onSearchKeydown);
      window.addEventListener('resize', resizeHandler);
      window.addEventListener('orientationchange', orientationchangeHandler);
    },
    destroy() {
      if (isInited) {
        header.removeEventListener('click', onFieldTogglerClick);
        search.removeEventListener('submit', onSearchSubmit);
        input.removeEventListener('input', onSearchInput);
        field.removeEventListener('focusout', onFieldFocusout);
        document.removeEventListener('keydown', onEscapeKey);
        suggester.removeEventListener('click', onSuggestionItemClick);
        input.removeEventListener('keydown', onSearchKeydown);
        window.removeEventListener('resize', resizeHandler);
        window.removeEventListener('orientationchange', orientationchangeHandler);

        header = null;
        search = null;
        field = null;
        input = null;
        suggester = null;
        mqTestElem = null;
        isInited = false;
      }
    },
  };
})();

/**
 * Upload
 */
const Upload = (function () {
  let isInited = false;

  let onUploadInputChange;

  return {
    init() {
      if (isInited) {
        return;
      }

      isInited = true;

      // handle `change` event on the upload file input
      onUploadInputChange = (e) => {
        const uploader = e.target.closest('.js-upload__input');

        if (uploader) {
          alert(`selected ${uploader.files.length} files.`);
        }
      };

      document.addEventListener('change', onUploadInputChange);
    },
    destroy() {
      document.removeEventListener('change', onUploadInputChange);
    },
  };
})();

// Initialize things, use `.destroy()` to clean it up when needed
Sidebar.init();
Suggester.init();
Upload.init();
