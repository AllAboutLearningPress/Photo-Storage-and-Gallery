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

  if (!sidebar) {
    return;
  }

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

  if (!header) {
    return;
  }

  const search = header.querySelector('.js-search');
  const field = search.querySelector('.js-search__field');
  const input = search.querySelector('.js-search__input');
  const suggester = search.querySelector('.js-search__suggest');
  const mqTestElem = document.querySelector('.search-dumb-mq-tester');

  const activeKlass = 'is-searchable';
  const suggestingKlass = 'is-suggesting';
  const highlightedSuggestionKlass = 'is-highlighted';

  function submitSearch(e) {
    e.preventDefault();
  }

  function unhighlightSuggestion() {
    const highlighted = suggester.querySelector(`.${highlightedSuggestionKlass}`);
    highlighted && highlighted.classList.remove(highlightedSuggestionKlass);
  }

  function selectSuggestion(suggestionElem) {
    alert(`suggesting: ${suggestionElem.textContent.trim()}`);
  }

  function toggleSuggester(inputElem) {
    const isToggled = inputElem.form.classList.toggle(suggestingKlass, inputElem.value.length);

    if (!isToggled) {
      unhighlightSuggestion();
    }
  }

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
  search.addEventListener('submit', submitSearch);
  input.addEventListener('input', (e) => toggleSuggester(e.target));
  field.addEventListener('focusout', (e) => {
    if (!field.contains(e.relatedTarget)) {
      toggleSearchfield({
        force: false,
        resetFocus: true,
      });
    }
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      toggleSearchfield({
        force: false,
        resetFocus: true,
      });
    }
  });

  // handle clicking suggestions
  suggester.addEventListener('click', (e) => {
    const item = e.target.closest('.js-search__suggest-item');

    if (item) {
      unhighlightSuggestion();
      selectSuggestion(item);
    }
  });

  // handle up/down arrows to highlight suggestions
  input.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
      e.preventDefault();

      const suggesterItems = [].slice.call(suggester.querySelectorAll('.js-search__suggest-item'));
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
  });

  window.addEventListener('resize', debounce(50, cleanup));
  window.addEventListener('orientationchange', debounce(0, cleanup));
})();

/**
 * Upload
 */
(function () {
  document.addEventListener('change', (e) => {
    const uploader = e.target.closest('.js-upload__input');

    if (uploader) {
      alert(`selected ${uploader.files.length} files.`);
    }
  });
})();

/**
 * Gallery
 */
(function () {})();

/**
 * Tagging
 */
(function () {
  const input = document.querySelector('input[name="detailed-tags"]');
  const tagify = new Tagify(input, {
    whitelist: [
      'A# .NET',
      'A# (Axiom)',
      'A-0 System',
      'A+',
      'A++',
      'ABAP',
      'ABC',
      'ABC ALGOL',
      'ABSET',
      'ABSYS',
      'ACC',
      'Accent',
      'Ace DASL',
      'ACL2',
      'Avicsoft',
      'ACT-III',
      'Action!',
      'ActionScript',
      'Ada',
      'Adenine',
      'Agda',
      'Agilent VEE',
      'Agora',
      'AIMMS',
      'Alef',
      'ALF',
      'ALGOL 58',
      'ALGOL 60',
      'ALGOL 68',
      'ALGOL W',
      'Alice',
      'Alma-0',
      'AmbientTalk',
      'Amiga E',
      'AMOS',
      'AMPL',
      'Apex (Salesforce.com)',
      'APL',
      'AppleScript',
      'Arc',
      'ARexx',
      'Argus',
      'AspectJ',
      'Assembly language',
      'ATS',
      'Ateji PX',
      'AutoHotkey',
      'Autocoder',
      'AutoIt',
      'AutoLISP / Visual LISP',
      'Averest',
      'AWK',
      'Axum',
      'Active Server Pages',
      'ASP.NET',
      'B',
      'Babbage',
      'Bash',
      'BASIC',
      'bc',
      'BCPL',
      'BeanShell',
      'Batch (Windows/Dos)',
      'Bertrand',
      'BETA',
      'Bigwig',
      'Bistro',
      'BitC',
      'BLISS',
      'Blockly',
      'BlooP',
      'Blue',
      'Boo',
      'Boomerang',
      'Bourne shell (including bash and ksh)',
      'BREW',
      'BPEL',
      'B',
      'C--',
      'C++ – ISO/IEC 14882',
      'C# – ISO/IEC 23270',
      'C/AL',
      'Caché ObjectScript',
      'C Shell',
      'Caml',
      'Cayenne',
      'CDuce',
      'Cecil',
      'Cesil',
      'Céu',
      'Ceylon',
      'CFEngine',
      'CFML',
      'Cg',
      'Ch',
      'Chapel',
      'Charity',
      'Charm',
      'Chef',
      'CHILL',
      'CHIP-8',
      'chomski',
      'ChucK',
      'CICS',
      'Cilk',
      'Citrine (programming language)',
      'CL (IBM)',
      'Claire',
      'Clarion',
      'Clean',
      'Clipper',
      'CLIPS',
      'CLIST',
      'Clojure',
      'CLU',
      'CMS-2',
      'COBOL – ISO/IEC 1989',
      'CobolScript – COBOL Scripting language',
      'Cobra',
      'CODE',
      'CoffeeScript',
      'ColdFusion',
      'COMAL',
      'Combined Programming Language (CPL)',
      'COMIT',
      'Common Intermediate Language (CIL)',
      'Common Lisp (also known as CL)',
      'COMPASS',
      'Component Pascal',
      'Constraint Handling Rules (CHR)',
      'COMTRAN',
      'Converge',
      'Cool',
      'Coq',
      'Coral 66',
      'Corn',
      'CorVision',
      'COWSEL',
      'CPL',
      'CPL',
      'Cryptol',
      'csh',
      'Csound',
      'CSP',
      'CUDA',
      'Curl',
      'Curry',
      'Cybil',
      'Cyclone',
      'Cython',
      'Java',
      'Javascript',
      'M2001',
      'M4',
      'M#',
      'Machine code',
      'MAD (Michigan Algorithm Decoder)',
      'MAD/I',
      'Magik',
      'Magma',
      'make',
      'Maple',
      'MAPPER now part of BIS',
      'MARK-IV now VISION:BUILDER',
      'Mary',
      'MASM Microsoft Assembly x86',
      'MATH-MATIC',
      'Mathematica',
      'MATLAB',
      'Maxima (see also Macsyma)',
      'Max (Max Msp – Graphical Programming Environment)',
      'Maya (MEL)',
      'MDL',
      'Mercury',
      'Mesa',
      'Metafont',
      'Microcode',
      'MicroScript',
      'MIIS',
      'Milk (programming language)',
      'MIMIC',
      'Mirah',
      'Miranda',
      'MIVA Script',
      'ML',
      'Model 204',
      'Modelica',
      'Modula',
      'Modula-2',
      'Modula-3',
      'Mohol',
      'MOO',
      'Mortran',
      'Mouse',
      'MPD',
      'Mathcad',
      'MSIL – deprecated name for CIL',
      'MSL',
      'MUMPS',
      'Mystic Programming L',
    ],
    maxTags: 10,
    dropdown: {
      maxItems: 20, // <- mixumum allowed rendered suggestions
      classname: 'tags-look', // <- custom classname for this dropdown, so it could be targeted
      enabled: 0, // <- show suggestions on focus
      closeOnSelect: false, // <- do not hide the suggestions dropdown once an item has been selected
    },
  });
})();
