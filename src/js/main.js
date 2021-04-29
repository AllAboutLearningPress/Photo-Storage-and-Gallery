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

  document.addEventListener(showEvent, (evt) => {
    handler.container = evt.target;
    document.addEventListener('keydown', handler);
  });
  document.addEventListener(hideEvent, (evt) => {
    handler.container = null;
    document.removeEventListener('keydown', handler);
  });
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
  var timeout;
  return function () {
    var context = this,
      args = arguments;
    var later = function () {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

/**
 * Toggle sidebar
 */
(function () {
  const sidebar = document.querySelector('.sidebar');
  const backdrop = document.querySelector('.sidebar-backdrop');
  const activeKlass = 'is-open';
  // elem is `display: block` on `md` bootstrap media query;
  const mqTestElem = document.querySelector('.sidebar-dumb-mq-tester');

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

  // patch sidebar focus
  patchFocus('sidebarshow', 'sidebarhide');

  document.querySelectorAll('.js-sidebar-toggle').forEach((elem) => {
    elem.addEventListener('click', () => toggleSidebar(elem));
  });

  // close sidebar on escape keypress
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      toggleSidebar(undefined, false);
    }
  });
  // close sidebar on backdrop click
  backdrop.addEventListener('click', () => toggleSidebar(undefined, false, false));

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

  window.addEventListener('resize', debounce(50, cleanup));
  window.addEventListener('orientationchange', debounce(0, cleanup));
})();

/**
 * Search field
 */
(function () {
  const header = document.querySelector('.js-search-header');
  const search = header.querySelector('.js-search');
  const field = search.querySelector('.js-search__field');
  const input = search.querySelector('.js-search__input');
  const suggester = search.querySelector('.js-search__suggest');
  const mqTestElem = document.querySelector('.search-dumb-mq-tester');

  const activeKlass = 'is-searchable';
  const suggestingKlass = 'is-suggesting';
  const selectedSuggestionKlass = 'is-selected';

  function toggleSearchfield({ force, resetFocus = false, togglerElem } = {}) {
    let isToggled = header.classList.toggle(activeKlass, force);

    if (isToggled) {
      input.focus();
    } else {
      input.value = '';
      search.classList.remove(suggestingKlass);
    }

    setTimeout(() => {
      if (resetFocus && !isToggled && header._trigger) {
        header._trigger.focus();
      }

      header._trigger = togglerElem;
    }, 0);
  }

  function cleanup() {
    if (!header.classList.contains(activeKlass)) {
      return;
    }

    const display = window.getComputedStyle(mqTestElem, null).display;

    if (display === 'block') {
      toggleSearchfield();
    }
  }

  header.addEventListener('click', (e) => {
    const togglerElem = e.target.closest('.js-search__field-toggle');

    if (togglerElem) {
      toggleSearchfield({
        togglerElem,
        resetFocus: !e.detail,
      });
    }
  });

  search.addEventListener('submit', (e) => {
    e.preventDefault();
  });

  // toggle suggester visibility
  input.addEventListener('input', (e) => {
    e.target.form.classList.toggle(suggestingKlass, e.target.value.length);
  });

  // close search field on focusout
  field.addEventListener('focusout', (e) => {
    if (!field.contains(e.relatedTarget)) {
      toggleSearchfield({
        force: false,
        resetFocus: true,
      });
    }
  });

  // handle clicking suggestions
  suggester.addEventListener('click', (e) => {
    const item = e.target.closest('.js-search__suggest-item');
    let selected;

    if (item) {
      selected = suggester.querySelector(`.${selectedSuggestionKlass}`);
      selected && selected.classList.remove(selectedSuggestionKlass);
      input.value = item.textContent.trim();
    }
  });

  // handle up/down arrows to select suggestions
  input.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
      e.preventDefault();

      const suggesterItems = [].slice.call(suggester.querySelectorAll('.js-search__suggest-item'));
      let selectedIndex = suggesterItems.findIndex((el) =>
        el.classList.contains(selectedSuggestionKlass)
      );
      const increment = e.key === 'ArrowDown' ? 1 : -1;
      const selected = suggesterItems[selectedIndex];
      const nextIndex = selectedIndex + increment;
      const next = suggesterItems[nextIndex < -1 ? suggesterItems.length - 1 : nextIndex];

      selected && selected.classList.remove(selectedSuggestionKlass);
      next && next.classList.add(selectedSuggestionKlass);
    }

    if (e.key === 'Enter') {
      const selectedItem = suggester.querySelector(`.${selectedSuggestionKlass}`);

      if (selectedItem) {
        selectedItem.click();
      } else {
        search.submit();
      }
    }
  });

  // close search field on escape keypress
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      toggleSearchfield({
        force: false,
        resetFocus: true,
      });
    }
  });

  window.addEventListener('resize', debounce(50, cleanup));
  window.addEventListener('orientationchange', debounce(0, cleanup));
})();

/**
 * Upload
 */
;(function() {
  document.addEventListener('change', (e) => {
    const uploader = e.target.closest('.js-upload__input');

    if (uploader) {
      alert(`selected ${uploader.files.length} files.`)
    }
  });
}());


/**
 * Gallery
 */
;(function() {
  const imageData = [
    {filename: 'blue.jpg', aspectRatio: 1.777},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'yellow.jpg', aspectRatio: 1},
    {filename: 'purple.jpg', aspectRatio: 2.4},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'yellow.jpg', aspectRatio: 1},
    {filename: 'purple.jpg', aspectRatio: 2.4},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'blue.jpg', aspectRatio: 1.777},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'yellow.jpg', aspectRatio: 1},
    {filename: 'purple.jpg', aspectRatio: 2.4},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'yellow.jpg', aspectRatio: 1},
    {filename: 'purple.jpg', aspectRatio: 2.4},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'blue.jpg', aspectRatio: 1.777},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'green.jpg', aspectRatio: 1.777},
    {filename: 'orange.jpg', aspectRatio: 1.777},
    {filename: 'yellow.jpg', aspectRatio: 1},
    {filename: 'purple.jpg', aspectRatio: 2.4},
    {filename: 'red.jpg', aspectRatio: 1.5},
    {filename: 'green.jpg', aspectRatio: 1.777},



  ];

  const options = {
    urlForSize: function(filename, size) {
      return '/img/' + size + '/' + filename;
    },
    onClickHandler(filename) {
      alert(`select ${filename}`);
    }
  };

  const pig = new Pig(imageData, options).enable();
}());
