/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/index.js":
/*!*************************!*\
  !*** ./src/js/index.js ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _modules_Sidebar__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/Sidebar */ \"./src/js/modules/Sidebar.js\");\n/* harmony import */ var _modules_Search__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/Search */ \"./src/js/modules/Search.js\");\n/* harmony import */ var _modules_Upload__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/Upload */ \"./src/js/modules/Upload.js\");\n/* harmony import */ var _modules_Tags__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/Tags */ \"./src/js/modules/Tags.js\");\n/* harmony import */ var _modules_Tags__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_modules_Tags__WEBPACK_IMPORTED_MODULE_3__);\n\n\n\n\n\n\nconst contentSidebar = new _modules_Sidebar__WEBPACK_IMPORTED_MODULE_0__.default();\nconst imageDetailsSidebar = new _modules_Sidebar__WEBPACK_IMPORTED_MODULE_0__.default();\nconst searchbar = new _modules_Search__WEBPACK_IMPORTED_MODULE_1__.default();\nconst upload = new _modules_Upload__WEBPACK_IMPORTED_MODULE_2__.default();\n\n// Initialize things, use `.destroy()` to clean it up when needed\ncontentSidebar.init(document.querySelector('.js-content-sidebar'));\nimageDetailsSidebar.init(document.querySelector('.js-image-details-sidebar'));\nsearchbar.init(document.querySelector('.js-search'));\nupload.init(document.querySelector('.js-upload'));\n\n\n//# sourceURL=webpack://photo-storage-and-gallery/./src/js/index.js?");

/***/ }),

/***/ "./src/js/modules/Search.js":
/*!**********************************!*\
  !*** ./src/js/modules/Search.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ \"./src/js/utils.js\");\n\n\n/**\n * Search bar\n */\nconst activeKlass = 'is-searchable';\nconst suggestingKlass = 'is-suggesting';\nconst highlightedSuggestionKlass = 'is-highlighted';\n\nclass Search {\n  constructor() {\n    this.handlers = null;\n    this.header = null;\n    this.search = null;\n    this.field = null;\n    this.input = null;\n    this.searchImageInput = null;\n    this.suggester = null;\n    this.mqTestElem = null;\n\n    this.isInited = false;\n    this.isSearchImageFileinputActivated = false;\n  }\n\n  /**\n   * Add \"highlighted\" state for any highlighted suggestion item\n   * @param elem{HTMLElement} - required suggestion DOM element to highlight\n   */\n  highlightSuggestion(elem) {\n    elem.classList.add(highlightedSuggestionKlass);\n    elem.scrollIntoView();\n  }\n\n  /**\n   * Remove \"highlighted\" state for any highlighted suggestion item\n   */\n  unhighlightSuggestion() {\n    const highlighted = this.suggester.querySelector(`.${highlightedSuggestionKlass}`);\n    highlighted && highlighted.classList.remove(highlightedSuggestionKlass);\n  }\n\n  /**\n   * The action upon suggestion item is \"selected\"\n   * @param suggestionId{String} - the value of a `data-suggestion-id` attribute of a required suggestion item\n   */\n  selectSuggestion(suggestionId) {\n    alert(\n      `selected: ${this.suggester\n        .querySelector(`[data-suggestion-id=\"${suggestionId}\"]`)\n        .textContent.trim()}`\n    );\n  }\n\n  /**\n   * Toggle \"activated\" state for the suggestions box\n   * @param force{Boolean} - optional parameter to forcefully designate a toggled state\n   * @param inputElem{HTMLElement} - optional parameter for the `<input>` for which suggestions box is being toggled\n   */\n  toggleSuggester({ force, inputElem = this.input } = {}) {\n    const isToggled = inputElem.form.classList.toggle(\n      suggestingKlass,\n      typeof force === 'undefined' ? inputElem.value.length : force\n    );\n\n    if (!isToggled) {\n      this.unhighlightSuggestion();\n    }\n  }\n\n  /**\n   * Toggle the \"activated\" state of a search field (i.e. when it is hidden by default on narrow screen)\n   * @param force{Boolean} - optional activated state\n   * @param resetFocus{Boolean} - optional flag to reset a focus to the element which was focused before toggling happened\n   * @param togglerElem{HTMLElement} - optional DOM element by which the action was performed\n   */\n  toggleSearchfield({ force, resetFocus = false, togglerElem } = {}) {\n    let isToggled = this.header.classList.toggle(activeKlass, force);\n\n    if (isToggled) {\n      this.input.focus();\n    } else {\n      this.input.value = '';\n      this.search.classList.remove(suggestingKlass);\n      this.unhighlightSuggestion();\n    }\n\n    setTimeout(() => {\n      if (resetFocus && !isToggled && this.header._trigger) {\n        this.header._trigger.focus();\n      }\n\n      this.header._trigger = togglerElem;\n    }, 0);\n  }\n\n  // deactivate search field if needed, based bootstrap `md` media-query\n  cleanup() {\n    if (!this.header.classList.contains(activeKlass)) {\n      return;\n    }\n\n    const display = window.getComputedStyle(this.mqTestElem, null).display;\n\n    if (display === 'block') {\n      this.toggleSearchfield();\n    }\n  }\n  init(searchElem) {\n    this.search = searchElem;\n\n    if (this.isInited || !this.search) {\n      return;\n    }\n\n    const that = this;\n\n    this.header = this.search.closest('.js-search-header');\n    this.field = this.search.querySelector('.js-search__field');\n    this.input = this.search.querySelector('.js-search__input');\n    this.searchImageInput = this.search.querySelector('.js-search__image-input');\n    this.suggester = this.search.querySelector('.js-search__suggest');\n    this.mqTestElem = this.search.querySelector('.search-dumb-mq-tester');\n\n    this.handlers = {\n      // handle search form `submit` event\n      onSearchSubmit(e) {\n        e.preventDefault();\n      },\n      // handle `input` event inside search input\n      onSearchInput(e) {\n        that.toggleSuggester({\n          inputElem: e.target,\n        });\n      },\n      onSearchImageFocusin() {\n        that.isSearchImageFileinputActivated = false;\n      },\n      onSearchImageClick() {\n        that.isSearchImageFileinputActivated = true;\n      },\n      // toggle search field by clicking on specific toggler elements\n      onFieldTogglerClick(e) {\n        const togglerElem = e.target.closest('.js-search__toggle');\n\n        if (togglerElem) {\n          that.toggleSearchfield({\n            togglerElem,\n            resetFocus: !e.detail,\n          });\n        }\n      },\n      // inactivate search field on \"loosing focus\" within (an actual logic is a bit more complex)\n      onFieldFocusout(e) {\n        if (\n          (e.relatedTarget || !that.isSearchImageFileinputActivated) &&\n          !that.field.contains(e.relatedTarget)\n        ) {\n          that.toggleSearchfield({\n            force: false,\n            resetFocus: true,\n          });\n        }\n      },\n      // close search field on escape\n      onEscapeKey(e) {\n        if (e.key === 'Escape') {\n          that.toggleSearchfield({\n            force: false,\n            resetFocus: true,\n          });\n        }\n      },\n      // handle suggestion item selection\n      onSuggestionItemClick(e) {\n        const item = e.target.closest('.js-search__suggest-item');\n\n        if (item) {\n          that.unhighlightSuggestion();\n          that.selectSuggestion(item.dataset.suggestionId);\n        }\n      },\n      // handle up/down arrows to highlight suggestions\n      onSearchKeydown(e) {\n        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {\n          e.preventDefault();\n\n          const suggesterItems = [].slice.call(\n            that.suggester.querySelectorAll('.js-search__suggest-item')\n          );\n          let highlightedIndex = suggesterItems.findIndex((el) =>\n            el.classList.contains(highlightedSuggestionKlass)\n          );\n          const increment = e.key === 'ArrowDown' ? 1 : -1;\n          const nextIndex = highlightedIndex + increment;\n          const next = suggesterItems[nextIndex < -1 ? suggesterItems.length - 1 : nextIndex];\n\n          that.unhighlightSuggestion();\n          next && that.highlightSuggestion(next);\n        }\n\n        if (e.key === 'Enter') {\n          const highlightedItem = that.suggester.querySelector(`.${highlightedSuggestionKlass}`);\n\n          if (highlightedItem) {\n            that.selectSuggestion(highlightedItem.dataset.suggestionId);\n          } else {\n            that.search.submit();\n          }\n        }\n      },\n\n      // window resize handlers\n      resizeHandler: (0,_utils__WEBPACK_IMPORTED_MODULE_0__.debounce)(50, () => that.cleanup),\n      orientationchangeHandler: (0,_utils__WEBPACK_IMPORTED_MODULE_0__.debounce)(0, () => that.cleanup),\n    };\n\n    this.header.addEventListener('click', this.handlers.onFieldTogglerClick);\n    this.search.addEventListener('submit', this.handlers.onSearchSubmit);\n    this.input.addEventListener('input', this.handlers.onSearchInput);\n    this.searchImageInput.addEventListener('focusin', this.handlers.onSearchImageFocusin);\n    this.searchImageInput.addEventListener('click', this.handlers.onSearchImageClick);\n    this.field.addEventListener('focusout', this.handlers.onFieldFocusout);\n    document.addEventListener('keydown', this.handlers.onEscapeKey);\n    this.suggester.addEventListener('click', this.handlers.onSuggestionItemClick);\n    this.input.addEventListener('keydown', this.handlers.onSearchKeydown);\n    window.addEventListener('resize', this.handlers.resizeHandler);\n    window.addEventListener('orientationchange', this.handlers.orientationchangeHandler);\n\n    this.isInited = true;\n  }\n  destroy() {\n    if (this.isInited) {\n      //unbind events\n      this.header.removeEventListener('click', this.handlers.onFieldTogglerClick);\n      this.search.removeEventListener('submit', this.handlers.onSearchSubmit);\n      this.input.removeEventListener('input', this.handlers.onSearchInput);\n      this.searchImageInput.removeEventListener('focusin', this.handlers.onSearchImageFocusin);\n      this.searchImageInput.removeEventListener('click', this.handlers.onSearchImageClick);\n      this.field.removeEventListener('focusout', this.handlers.onFieldFocusout);\n      document.removeEventListener('keydown', this.handlers.onEscapeKey);\n      this.suggester.removeEventListener('click', this.handlers.onSuggestionItemClick);\n      this.input.removeEventListener('keydown', this.handlers.onSearchKeydown);\n      window.removeEventListener('resize', this.handlers.resizeHandler);\n      window.removeEventListener('orientationchange', this.handlers.orientationchangeHandler);\n\n      // dereference handler functions object\n      this.handlers = null;\n\n      // dereference DOM nodes\n      this.header = null;\n      this.search = null;\n      this.field = null;\n      this.input = null;\n      this.searchImageInput = null;\n      this.suggester = null;\n      this.mqTestElem = null;\n\n      this.isInited = false;\n    }\n  }\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Search);\n\n\n//# sourceURL=webpack://photo-storage-and-gallery/./src/js/modules/Search.js?");

/***/ }),

/***/ "./src/js/modules/Sidebar.js":
/*!***********************************!*\
  !*** ./src/js/modules/Sidebar.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils */ \"./src/js/utils.js\");\n\n\n/**\n * Toggle sidebar\n */\n\nconst activeKlass = 'is-open';\nconst alwaysHideableKlass = 'sidebar_always-hideable';\n\ndocument.addEventListener('click', (e) => {\n  const toggler = e.target.closest('.js-sidebar-toggle');\n\n  toggler &&\n    toggler.dispatchEvent(\n      new CustomEvent('sidebar-toggle-click', {\n        bubbles: true,\n      })\n    );\n});\n\nclass Sidebar {\n  constructor() {\n    this.sidebar = null;\n    this.backdrop = null;\n    this.overlayingSidebarMqTestElem = null;\n\n    this.handlers = null;\n    this.cleanupFuntions = null;\n\n    this.isInited = false;\n  }\n  init(sidebarElem) {\n    this.sidebar = sidebarElem;\n\n    if (this.isInited || !this.sidebar) {\n      return;\n    }\n\n    const that = this;\n    // `patchFocus()` cleanup function\n    let destroyPatchedFocus;\n    this.cleanupFuntions = [];\n\n    this.backdrop = this.sidebar.parentNode.querySelector('.sidebar-backdrop');\n    // `overlayingSidebarMqTestElem` is `display: block` when sidebar is in \"overlaying\" state, which depends on bootstrap CSS media query\n    this.overlayingSidebarMqTestElem = this.sidebar.querySelector(\n      '.overlaying-sidebar-mq-test-elem'\n    );\n\n    /**\n     * Toggle visibility of a sidebar (i.e. when it is hidden by default on narrow screen)\n     * @param toggler{HTMLElement} - optional DOM element by which the action was performed\n     * @param force{Boolean} - optional visibility state\n     * @param resetFocus{Boolean} - optional flag to reset a focus to the element which was focused before toggling happened\n     */\n    function toggleSidebar({ toggler, force, resetFocus = true } = {}) {\n      const wasOpened = that.sidebar.classList.contains(activeKlass);\n\n      if (wasOpened === force) {\n        return;\n      }\n\n      that.sidebar.classList.toggle(activeKlass, force);\n      that.sidebar.dispatchEvent(\n        new CustomEvent(wasOpened ? 'sidebarhide' : 'sidebarshow', { bubbles: true })\n      );\n      setTimeout(() => {\n        if (resetFocus) {\n          const elementToFocus = wasOpened ? that.sidebar._trigger : that.sidebar;\n          elementToFocus && elementToFocus.focus();\n        }\n\n        that.sidebar._trigger = toggler;\n      }, 50);\n    }\n\n    function isSidebarOverlaying() {\n      return window.getComputedStyle(that.overlayingSidebarMqTestElem, null).display === 'none';\n    }\n\n    // hide sidebar which wasn't hidden and then bootstrap `md` mq fired up\n    function cleanup() {\n      if (!that.sidebar.classList.contains(activeKlass)) {\n        return;\n      }\n\n      if (that.sidebar.classList.contains(alwaysHideableKlass) && isSidebarOverlaying()) {\n        that.sidebar.focus();\n      } else if (!that.sidebar.classList.contains(alwaysHideableKlass)) {\n        toggleSidebar({ force: false });\n      }\n    }\n\n    this.handlers = {\n      // activate sidebar by clicking on specific toggler elements\n      onTogglerClick(e) {\n        const targetSidebar = document.querySelector(e.target.dataset && e.target.dataset.sidebar);\n\n        if (targetSidebar === that.sidebar) {\n          toggleSidebar({ toggler: e.target });\n        }\n      },\n\n      // handle the click on the backdrop\n      onBackdropClick() {\n        toggleSidebar({ force: false, resetFocus: false });\n      },\n\n      // handle escape key\n      onEscapeKey(e) {\n        if (e.key === 'Escape') {\n          toggleSidebar({ force: false });\n        }\n      },\n\n      // window resize handlers\n      resizeHandler: (0,_utils__WEBPACK_IMPORTED_MODULE_0__.debounce)(100, cleanup),\n      orientationchangeHandler: (0,_utils__WEBPACK_IMPORTED_MODULE_0__.debounce)(0, cleanup),\n    };\n\n    destroyPatchedFocus = (0,_utils__WEBPACK_IMPORTED_MODULE_0__.patchFocus)('sidebarshow', 'sidebarhide', () => isSidebarOverlaying());\n\n    this.cleanupFuntions.push(destroyPatchedFocus);\n\n    document.addEventListener('sidebar-toggle-click', this.handlers.onTogglerClick);\n    document.addEventListener('keydown', this.handlers.onEscapeKey);\n    this.backdrop && this.backdrop.addEventListener('click', this.handlers.onBackdropClick);\n    window.addEventListener('resize', this.handlers.resizeHandler);\n    window.addEventListener('orientationchange', this.handlers.orientationchangeHandler);\n\n    this.isInited = true;\n  }\n  destroy() {\n    if (this.isInited) {\n      // call any existing cleanup functions and clear the array\n      this.cleanupFuntions.forEach((fn) => fn());\n      this.cleanupFuntions.length = 0;\n\n      //unbind events\n      document.removeEventListener('sidebar-toggle-click', this.handlers.onTogglerClick);\n      document.removeEventListener('keydown', this.handlers.onEscapeKey);\n      this.backdrop && this.backdrop.removeEventListener('click', this.handlers.onBackdropClick);\n      window.removeEventListener('resize', this.handlers.resizeHandler);\n      window.removeEventListener('orientationchange', this.handlers.orientationchangeHandler);\n\n      // dereference handler functions object\n      this.handlers = null;\n\n      // dereference DOM nodes\n      this.sidebar = null;\n      this.backdrop = null;\n      this.overlayingSidebarMqTestElem = null;\n\n      this.isInited = false;\n    }\n  }\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Sidebar);\n\n\n//# sourceURL=webpack://photo-storage-and-gallery/./src/js/modules/Sidebar.js?");

/***/ }),

/***/ "./src/js/modules/Tags.js":
/*!********************************!*\
  !*** ./src/js/modules/Tags.js ***!
  \********************************/
/***/ (() => {

eval("/*\n* A dumb file for handling tags, feel free to write all you want here\n* */\n\n// WARNING: this code should exist in one form or another\n// (a click event handler with an exact same code as written, `alert` can be deleted)\ndocument.addEventListener('click', (e) => {\n  const tagDeleter = e.target.closest('.js-tag-delete');\n\n  if (tagDeleter) {\n    e.preventDefault();\n    alert('delete tag');\n  }\n});\n\n\n//# sourceURL=webpack://photo-storage-and-gallery/./src/js/modules/Tags.js?");

/***/ }),

/***/ "./src/js/modules/Upload.js":
/*!**********************************!*\
  !*** ./src/js/modules/Upload.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/**\n * Upload\n */\n\nclass Upload {\n  constructor() {\n    this.upload = null;\n    this.handlers = null;\n\n    this.isInited = false;\n  }\n  init(uploadElem) {\n    this.upload = uploadElem;\n\n    if (this.isInited || !this.upload) {\n      return;\n    }\n\n    const that = this;\n\n    this.handlers = {\n      // handle `change` event on the upload file input\n      onUploadInputChange(e) {\n        const uploader = e.target.closest('.js-upload__input');\n\n        if (uploader) {\n          alert(`selected ${uploader.files.length} files.`);\n        }\n      }\n    };\n\n    this.upload.addEventListener('change', this.handlers.onUploadInputChange);\n\n    this.isInited = true;\n  }\n  destroy() {\n    //unbind events\n    this.upload.removeEventListener('change', this.handlers.onUploadInputChange);\n\n    // dereference handler functions object\n    this.handlers = null;\n\n    // dereference DOM nodes\n    this.upload = null;\n\n    this.isInited = false;\n  }\n}\n\n/* harmony default export */ __webpack_exports__[\"default\"] = (Upload);\n\n\n//# sourceURL=webpack://photo-storage-and-gallery/./src/js/modules/Upload.js?");

/***/ }),

/***/ "./src/js/utils.js":
/*!*************************!*\
  !*** ./src/js/utils.js ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"patchFocus\": () => (/* binding */ patchFocus),\n/* harmony export */   \"debounce\": () => (/* binding */ debounce)\n/* harmony export */ });\n/**\n * Make tabbing trapped within a \"popup\" container (i.e. modal)\n * @param showEvent{String} - The event which is triggered when the container shows\n * @param hideEvent{String} - The event which is triggered when the container hides\n * @param testIfShouldTrap{Function} - tester function to determine if focus should be kept being trapped, or not\n */\nfunction patchFocus(showEvent, hideEvent, testIfShouldTrap = () => true) {\n  // https://github.com/twbs/bootstrap/issues/28481#issuecomment-763017750\n  const TAB_KEY = 9;\n  const focusableSelectors = [\n    'a[href]:not([tabindex^=\"-\"])',\n    'area[href]:not([tabindex^=\"-\"])',\n    'input:not([type=\"hidden\"]):not([type=\"radio\"]):not([disabled]):not([tabindex^=\"-\"])',\n    'input[type=\"radio\"]:not([disabled]):not([tabindex^=\"-\"]):checked',\n    'select:not([disabled]):not([tabindex^=\"-\"])',\n    'textarea:not([disabled]):not([tabindex^=\"-\"])',\n    'button:not([disabled]):not([tabindex^=\"-\"])',\n    'iframe:not([tabindex^=\"-\"])',\n    'audio[controls]:not([tabindex^=\"-\"])',\n    'video[controls]:not([tabindex^=\"-\"])',\n    '[contenteditable]:not([tabindex^=\"-\"])',\n    '[tabindex]:not([tabindex^=\"-\"])',\n  ];\n\n  function getFocusableChildren(node) {\n    return [].filter.call(\n      node.querySelectorAll(focusableSelectors.join(',')),\n      (child) => !!(child.offsetWidth || child.offsetHeight || child.getClientRects().length)\n    );\n  }\n\n  function trapTabKey(node, event) {\n    const focusableChildren = getFocusableChildren(node);\n    const focusedItemIndex = focusableChildren.indexOf(document.activeElement);\n\n    if (event.shiftKey && focusedItemIndex === 0) {\n      focusableChildren[focusableChildren.length - 1].focus();\n      event.preventDefault();\n    } else if (!event.shiftKey && focusedItemIndex === focusableChildren.length - 1) {\n      focusableChildren[0].focus();\n      event.preventDefault();\n    }\n  }\n\n  function bindKeypress(event, container) {\n    if (container && event.which === TAB_KEY && testIfShouldTrap()) {\n      trapTabKey(container, event);\n    }\n  }\n\n  const handler = {\n    container: null,\n    handleEvent(e) {\n      bindKeypress(e, this.container);\n    },\n  };\n\n  function onShow(evt) {\n    handler.container = evt.target;\n    document.addEventListener('keydown', handler);\n  }\n  function onHide(evt) {\n    handler.container = null;\n    document.removeEventListener('keydown', handler);\n  }\n\n  document.addEventListener(showEvent, onShow);\n  document.addEventListener(hideEvent, onHide);\n\n  function destroy() {\n    document.removeEventListener(showEvent, onShow);\n    document.removeEventListener(hideEvent, onHide);\n  }\n\n  return destroy;\n}\n\n/**\n * Returns a function, that, as long as it continues to be invoked, will not\n * be triggered. The function will be called after it stops being called for\n * N milliseconds. If `immediate` is passed, trigger the function on the\n * leading edge, instead of the trailing.\n * @param wait\n * @param func\n * @param immediate\n * @returns {function(...[*]=)}\n */\nfunction debounce(wait, func, immediate) {\n  let timeout;\n  return function () {\n    const context = this,\n      args = arguments;\n    const later = function () {\n      timeout = null;\n      if (!immediate) func.apply(context, args);\n    };\n    const callNow = immediate && !timeout;\n    clearTimeout(timeout);\n    timeout = setTimeout(later, wait);\n    if (callNow) func.apply(context, args);\n  };\n}\n\n\n//# sourceURL=webpack://photo-storage-and-gallery/./src/js/utils.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./src/js/index.js");
/******/ 	
/******/ })()
;