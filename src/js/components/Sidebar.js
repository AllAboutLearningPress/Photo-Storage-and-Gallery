import { patchFocus, debounce, addEventListener } from '../util/utils';

/**
 * Toggle sidebar
 */

const activeKlass = 'is-open';
const alwaysHideableKlass = 'sidebar_always-hideable';
const collapsedStateLocalStorageKey = '__unique-photo-gallery-key-for-details-state';

let sidebarId = 0;

document.addEventListener('click', (e) => {
  const toggler = e.target.closest('.js-sidebar-toggle');

  toggler &&
    toggler.dispatchEvent(
      new CustomEvent('sidebar-toggle-click', {
        bubbles: true,
      })
    );
});

class Sidebar {
  constructor(
    sidebarElem,
    options = {
      saveState: false,
    }
  ) {
    this.sidebar = sidebarElem;

    if (!this.sidebar) {
      return;
    }

    this.id = sidebarId += 1;
    this.options = options;

    const that = this;
    // `patchFocus()` cleanup function
    let destroyPatchedFocus;
    this.cleanupFuntions = [];

    this.backdrop = this.sidebar.parentNode.querySelector('.sidebar-backdrop');
    // `overlayingSidebarMqTestElem` is `display: block` when sidebar is in "overlaying" state, which depends on bootstrap CSS media query
    this.overlayingSidebarMqTestElem = this.sidebar.querySelector(
      '.overlaying-sidebar-mq-test-elem'
    );

    /**
     * Toggle visibility of a sidebar (i.e. when it is hidden by default on narrow screen)
     * @param toggler{HTMLElement} - optional DOM element by which the action was performed
     * @param force{Boolean} - optional visibility state
     * @param resetFocus{Boolean} - optional flag to reset a focus to the element which was focused before toggling happened
     */
    function toggleSidebar({ toggler, force, resetFocus = true } = {}) {
      const wasOpened = that.sidebar.classList.contains(activeKlass);

      if (wasOpened === force) {
        return;
      }

      that.sidebar.classList.toggle(activeKlass, force);
      that.sidebar.dispatchEvent(
        new CustomEvent(wasOpened ? 'sidebarhide' : 'sidebarshow', { bubbles: true })
      );
      setTimeout(() => {
        if (resetFocus) {
          const elementToFocus = wasOpened ? that.sidebar._trigger : that.sidebar;
          elementToFocus && elementToFocus.focus();
        }

        that.sidebar._trigger = toggler;
      }, 50);
    }

    function getCollapsedState() {
      return that.sidebar.classList.contains(activeKlass);
    }

    function saveCollapsedState() {
      localStorage.setItem(
        `${collapsedStateLocalStorageKey}_${that.id}`,
        JSON.stringify(getCollapsedState())
      );
    }

    function isSidebarOverlaying() {
      return window.getComputedStyle(that.overlayingSidebarMqTestElem, null).display === 'none';
    }

    // hide sidebar which wasn't hidden and then bootstrap `md` mq fired up
    function cleanup() {
      if (!that.sidebar.classList.contains(activeKlass)) {
        return;
      }

      if (that.sidebar.classList.contains(alwaysHideableKlass) && isSidebarOverlaying()) {
        that.sidebar.focus();
      } else if (!that.sidebar.classList.contains(alwaysHideableKlass)) {
        toggleSidebar({ force: false });
      }
    }

    this.handlers = [
      addEventListener(document, 'sidebar-toggle-click', (e) => {
        const targetSidebar = document.querySelector(e.target.dataset && e.target.dataset.sidebar);

        if (targetSidebar === that.sidebar) {
          toggleSidebar({ toggler: e.target });
        }
      }),
      addEventListener(document, 'keydown', (e) => {
        if (e.key === 'Escape') {
          toggleSidebar({ force: false });
        }
      }),
      this.backdrop &&
        addEventListener(this.backdrop, 'click', () => {
          toggleSidebar({ force: false, resetFocus: false });
        }),
      addEventListener(window, 'beforeunload', () => {
        if (that.options.saveState) {
          saveCollapsedState();
        }
      }),
      addEventListener(window, 'resize', debounce(100, cleanup)),
      addEventListener(window, 'orientationchange', debounce(0, cleanup)),
    ];

    destroyPatchedFocus = patchFocus('sidebarshow', 'sidebarhide', () => isSidebarOverlaying());

    this.cleanupFuntions.push(destroyPatchedFocus);

    if (this.options.saveState && !isSidebarOverlaying()) {
      const shouldBeShown = JSON.parse(
        localStorage.getItem(`${collapsedStateLocalStorageKey}_${this.id}`)
      );

      if (shouldBeShown) {
        toggleSidebar();
      }
    }

    this.isInited = true;
  }
  toggle(force) {}
  destroy() {
    if (this.isInited) {
      // call any existing cleanup functions and dereference the array
      this.cleanupFuntions.forEach((fn) => fn());
      this.cleanupFuntions = null;

      //unbind events
      this.handlers.forEach((fn) => fn && fn());

      // dereference handlers array
      this.handlers = null;

      // dereference DOM nodes
      this.sidebar = null;
      this.backdrop = null;
      this.overlayingSidebarMqTestElem = null;

      this.isInited = false;
    }
  }
}

export default Sidebar;
