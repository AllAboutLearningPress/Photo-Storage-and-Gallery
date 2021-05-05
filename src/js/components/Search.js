import { debounce, addEventListener } from '../util/utils';

import SingleImageDropManager from './SingleImageDropManager';

/**
 * Search bar
 */
const activeKlass = 'is-searchable';
const suggestingKlass = 'is-suggesting';
const highlightedSuggestionKlass = 'is-highlighted';
const noTransitionKlass = 'has-no-transition';

class Search {
  constructor(searchElem) {
    this.search = searchElem;

    if (!this.search) {
      return;
    }

    const that = this;

    this.dropManager = new SingleImageDropManager(document.querySelector('.js-drop-manager'));

    this.header = this.search.closest('.js-search-header');
    this.field = this.search.querySelector('.js-search__field');
    this.input = this.search.querySelector('.js-search__input');
    this.searchImageInput = this.search.querySelector('.js-search__image-input');
    this.suggester = this.search.querySelector('.js-search__suggest');
    this.mqTestElem = this.search.querySelector('.search-dumb-mq-tester');

    this.dropManager = new SingleImageDropManager(document.querySelector('.js-drop-manager'));

    function handleFileDrop(e) {
      const isSearchDrop =
        !that.dropManager.isInited || that.dropManager.getLatestDropStats().search;

      if (isSearchDrop) {
        that.handleImageSearch(e.detail.fileArray[0]);
      }
    }

    this.handlers = [
      addEventListener(this.header, 'click', (e) => {
        const togglerElem = e.target.closest('.js-search__toggle');

        if (togglerElem) {
          this.toggleSearchfield({
            togglerElem,
            resetFocus: !e.detail,
          });
        }
      }),
      addEventListener(this.search, 'submit', (e) => {
        e.preventDefault();
      }),
      addEventListener(this.input, 'input', (e) => {
        this.toggleSuggester({
          inputElem: e.target,
        });
      }),
      addEventListener(this.searchImageInput, 'focusin', () => {
        this.isSearchImageFileinputActivated = false;
      }),
      addEventListener(this.searchImageInput, 'click', () => {
        this.isSearchImageFileinputActivated = true;
      }),
      // inactivate search field on "loosing focus" within (an actual logic is a bit more complex)
      addEventListener(this.field, 'focusout', (e) => {
        if (
          (e.relatedTarget || !this.isSearchImageFileinputActivated) &&
          !this.field.contains(e.relatedTarget)
        ) {
          const initialTrigger = this.header._trigger;

          if (initialTrigger) {
            initialTrigger.classList.add(noTransitionKlass);
            setTimeout(() => {
              initialTrigger.classList.remove(noTransitionKlass);
            }, 0);
          }

          this.toggleSearchfield({
            force: false,
            resetFocus: true,
          });
        }
      }),
      addEventListener(document, 'keydown', (e) => {
        if (e.key === 'Escape') {
          this.toggleSearchfield({
            force: false,
            resetFocus: true,
          });
        }
      }),
      addEventListener(this.suggester, 'click', (e) => {
        const item = e.target.closest('.js-search__suggest-item');

        if (item) {
          this.unhighlightSuggestion();
          this.selectSuggestion(item.dataset.suggestionId);
        }
      }),
      // handle up/down arrows to highlight suggestions
      addEventListener(this.input, 'keydown', (e) => {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
          e.preventDefault();

          const suggesterItems = [].slice.call(
            this.suggester.querySelectorAll('.js-search__suggest-item')
          );
          let highlightedIndex = suggesterItems.findIndex((el) =>
            el.classList.contains(highlightedSuggestionKlass)
          );
          const increment = e.key === 'ArrowDown' ? 1 : -1;
          const nextIndex = highlightedIndex + increment;
          const next = suggesterItems[nextIndex < -1 ? suggesterItems.length - 1 : nextIndex];

          this.unhighlightSuggestion();
          next && this.highlightSuggestion(next);
        }

        if (e.key === 'Enter') {
          const highlightedItem = this.suggester.querySelector(`.${highlightedSuggestionKlass}`);

          if (highlightedItem) {
            this.selectSuggestion(highlightedItem.dataset.suggestionId);
          } else {
            this.search.submit();
          }
        }
      }),
      addEventListener(document, 'items-dropped', (e) => {
        handleFileDrop(e);
      }),
      addEventListener(
        window,
        'resize',
        debounce(50, () => this.cleanup)
      ),
      addEventListener(
        window,
        'orientationchange',
        debounce(0, () => this.cleanup)
      ),
    ];

    this.isInited = true;
  }


  /**
   * Handle a search by provided image.
   *
   * We can get either a promise (coming from `processDroppedFiles`, see `js/components/DropTarget.js` for details),
   * or a File object (coming from a `<input type="file">`).
   *
   * To account for that, always use `Promise.resolve(file)` on the argument, to make sure it is always a promise.
   * So to get a file from an arguments , `await Promise.resolve(file)` can be used.
   * @param file{File|Promise} - a File object or a promise resolving to it
   * @returns {Promise<void>}
   */
  async handleImageSearch(file) {
    console.log(await Promise.resolve(file));
  }

  /**
   * Add "highlighted" state for any highlighted suggestion item
   * @param elem{HTMLElement} - required suggestion DOM element to highlight
   */
  highlightSuggestion(elem) {
    elem.classList.add(highlightedSuggestionKlass);
    elem.scrollIntoView();
  }

  /**
   * Remove "highlighted" state for any highlighted suggestion item
   */
  unhighlightSuggestion() {
    const highlighted = this.suggester.querySelector(`.${highlightedSuggestionKlass}`);
    highlighted && highlighted.classList.remove(highlightedSuggestionKlass);
  }

  /**
   * The action upon suggestion item is "selected"
   * @param suggestionId{String} - the value of a `data-suggestion-id` attribute of a required suggestion item
   */
  selectSuggestion(suggestionId) {
    alert(
      `selected: ${this.suggester
        .querySelector(`[data-suggestion-id="${suggestionId}"]`)
        .textContent.trim()}`
    );
  }

  /**
   * Toggle "activated" state for the suggestions box
   * @param force{Boolean} - optional parameter to forcefully designate a toggled state
   * @param inputElem{HTMLElement} - optional parameter for the `<input>` for which suggestions box is being toggled
   */
  toggleSuggester({ force, inputElem = this.input } = {}) {
    const isToggled = inputElem.form.classList.toggle(
      suggestingKlass,
      typeof force === 'undefined' ? inputElem.value.length : force
    );

    if (!isToggled) {
      this.unhighlightSuggestion();
    }
  }

  /**
   * Toggle the "activated" state of a search field (when it is hidden by default on narrow screen)
   * @param force{Boolean} - optional activated state
   * @param resetFocus{Boolean} - optional flag to reset a focus to the element which was focused before toggling happened
   * @param togglerElem{HTMLElement} - optional DOM element by which the action was performed
   */
  toggleSearchfield({ force, resetFocus = false, togglerElem } = {}) {
    let isToggled = this.header.classList.toggle(activeKlass, force);

    if (isToggled) {
      this.input.focus();
    } else {
      this.input.value = '';
      this.search.classList.remove(suggestingKlass);
      this.unhighlightSuggestion();
    }

    setTimeout(() => {
      if (resetFocus && !isToggled && this.header._trigger) {
        const currentTrigger = this.header._trigger;
        currentTrigger.classList.add(noTransitionKlass);
        currentTrigger.focus();
        setTimeout(() => {
          currentTrigger.classList.remove(noTransitionKlass);
        }, 0);
      }

      this.header._trigger = togglerElem;
    }, 0);
  }

  // deactivate search field if needed, based bootstrap `md` media-query
  cleanup() {
    if (!this.header.classList.contains(activeKlass)) {
      return;
    }

    const display = window.getComputedStyle(this.mqTestElem, null).display;

    if (display === 'block') {
      this.toggleSearchfield();
    }
  }
  destroy() {
    if (this.isInited) {
      //unbind events
      this.handlers.forEach((fn) => fn && fn());

      // dereference handlers array
      this.handlers = null;

      // dereference DOM nodes
      this.header = null;
      this.search = null;
      this.field = null;
      this.input = null;
      this.searchImageInput = null;
      this.suggester = null;
      this.mqTestElem = null;

      this.dropManager.destroy();
      this.dropManager = null;

      this.isInited = false;
    }
  }
}

export default Search;
