import { patchFocus, debounce } from '../utils';

/**
 * Toggle sidebar
 */

const activeKlass = 'is-open';
const alwaysHideableKlass = 'sidebar_always-hideable';

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
  constructor() {
    this.sidebar = null;
    this.backdrop = null;
    this.overlayingSidebarMqTestElem = null;

    this.handlers = null;
    this.cleanupFuntions = null;

    this.isInited = false;
  }
  init(sidebarElem) {
    this.sidebar = sidebarElem;

    if (this.isInited || !this.sidebar) {
      return;
    }

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

    this.handlers = {
      // activate sidebar by clicking on specific toggler elements
      onTogglerClick(e) {
        const targetSidebar = document.querySelector(e.target.dataset && e.target.dataset.sidebar);

        if (targetSidebar === that.sidebar) {
          toggleSidebar({ toggler: e.target });
        }
      },

      // handle the click on the backdrop
      onBackdropClick() {
        toggleSidebar({ force: false, resetFocus: false });
      },

      // handle escape key
      onEscapeKey(e) {
        if (e.key === 'Escape') {
          toggleSidebar({ force: false });
        }
      },

      // window resize handlers
      resizeHandler: debounce(100, cleanup),
      orientationchangeHandler: debounce(0, cleanup),
    };

    destroyPatchedFocus = patchFocus('sidebarshow', 'sidebarhide', () => isSidebarOverlaying());

    this.cleanupFuntions.push(destroyPatchedFocus);

    document.addEventListener('sidebar-toggle-click', this.handlers.onTogglerClick);
    document.addEventListener('keydown', this.handlers.onEscapeKey);
    this.backdrop && this.backdrop.addEventListener('click', this.handlers.onBackdropClick);
    window.addEventListener('resize', this.handlers.resizeHandler);
    window.addEventListener('orientationchange', this.handlers.orientationchangeHandler);

    this.isInited = true;
  }
  destroy() {
    if (this.isInited) {
      // call any existing cleanup functions and clear the array
      this.cleanupFuntions.forEach((fn) => fn());
      this.cleanupFuntions.length = 0;

      //unbind events
      document.removeEventListener('sidebar-toggle-click', this.handlers.onTogglerClick);
      document.removeEventListener('keydown', this.handlers.onEscapeKey);
      this.backdrop && this.backdrop.removeEventListener('click', this.handlers.onBackdropClick);
      window.removeEventListener('resize', this.handlers.resizeHandler);
      window.removeEventListener('orientationchange', this.handlers.orientationchangeHandler);

      // dereference handler functions object
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
