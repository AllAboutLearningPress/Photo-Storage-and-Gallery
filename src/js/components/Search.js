import { debounce } from '../utils';

/**
 * Search bar
 */
const activeKlass = 'is-searchable';
const suggestingKlass = 'is-suggesting';
const highlightedSuggestionKlass = 'is-highlighted';

class Search {
  constructor() {
    this.handlers = null;
    this.header = null;
    this.search = null;
    this.field = null;
    this.input = null;
    this.searchImageInput = null;
    this.suggester = null;
    this.mqTestElem = null;

    this.isInited = false;
    this.isSearchImageFileinputActivated = false;
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
   * Toggle the "activated" state of a search field (i.e. when it is hidden by default on narrow screen)
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
        this.header._trigger.focus();
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
  init(searchElem) {
    this.search = searchElem;

    if (this.isInited || !this.search) {
      return;
    }

    const that = this;

    this.header = this.search.closest('.js-search-header');
    this.field = this.search.querySelector('.js-search__field');
    this.input = this.search.querySelector('.js-search__input');
    this.searchImageInput = this.search.querySelector('.js-search__image-input');
    this.suggester = this.search.querySelector('.js-search__suggest');
    this.mqTestElem = this.search.querySelector('.search-dumb-mq-tester');

    this.handlers = {
      // handle search form `submit` event
      onSearchSubmit(e) {
        e.preventDefault();
      },
      // handle `input` event inside search input
      onSearchInput(e) {
        that.toggleSuggester({
          inputElem: e.target,
        });
      },
      onSearchImageFocusin() {
        that.isSearchImageFileinputActivated = false;
      },
      onSearchImageClick() {
        that.isSearchImageFileinputActivated = true;
      },
      // toggle search field by clicking on specific toggler elements
      onFieldTogglerClick(e) {
        const togglerElem = e.target.closest('.js-search__toggle');

        if (togglerElem) {
          that.toggleSearchfield({
            togglerElem,
            resetFocus: !e.detail,
          });
        }
      },
      // inactivate search field on "loosing focus" within (an actual logic is a bit more complex)
      onFieldFocusout(e) {
        if (
          (e.relatedTarget || !that.isSearchImageFileinputActivated) &&
          !that.field.contains(e.relatedTarget)
        ) {
          that.toggleSearchfield({
            force: false,
            resetFocus: true,
          });
        }
      },
      // close search field on escape
      onEscapeKey(e) {
        if (e.key === 'Escape') {
          that.toggleSearchfield({
            force: false,
            resetFocus: true,
          });
        }
      },
      // handle suggestion item selection
      onSuggestionItemClick(e) {
        const item = e.target.closest('.js-search__suggest-item');

        if (item) {
          that.unhighlightSuggestion();
          that.selectSuggestion(item.dataset.suggestionId);
        }
      },
      // handle up/down arrows to highlight suggestions
      onSearchKeydown(e) {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
          e.preventDefault();

          const suggesterItems = [].slice.call(
            that.suggester.querySelectorAll('.js-search__suggest-item')
          );
          let highlightedIndex = suggesterItems.findIndex((el) =>
            el.classList.contains(highlightedSuggestionKlass)
          );
          const increment = e.key === 'ArrowDown' ? 1 : -1;
          const nextIndex = highlightedIndex + increment;
          const next = suggesterItems[nextIndex < -1 ? suggesterItems.length - 1 : nextIndex];

          that.unhighlightSuggestion();
          next && that.highlightSuggestion(next);
        }

        if (e.key === 'Enter') {
          const highlightedItem = that.suggester.querySelector(`.${highlightedSuggestionKlass}`);

          if (highlightedItem) {
            that.selectSuggestion(highlightedItem.dataset.suggestionId);
          } else {
            that.search.submit();
          }
        }
      },

      // window resize handlers
      resizeHandler: debounce(50, () => that.cleanup),
      orientationchangeHandler: debounce(0, () => that.cleanup),
    };

    this.header.addEventListener('click', this.handlers.onFieldTogglerClick);
    this.search.addEventListener('submit', this.handlers.onSearchSubmit);
    this.input.addEventListener('input', this.handlers.onSearchInput);
    this.searchImageInput.addEventListener('focusin', this.handlers.onSearchImageFocusin);
    this.searchImageInput.addEventListener('click', this.handlers.onSearchImageClick);
    this.field.addEventListener('focusout', this.handlers.onFieldFocusout);
    document.addEventListener('keydown', this.handlers.onEscapeKey);
    this.suggester.addEventListener('click', this.handlers.onSuggestionItemClick);
    this.input.addEventListener('keydown', this.handlers.onSearchKeydown);
    window.addEventListener('resize', this.handlers.resizeHandler);
    window.addEventListener('orientationchange', this.handlers.orientationchangeHandler);

    this.isInited = true;
  }
  destroy() {
    if (this.isInited) {
      //unbind events
      this.header.removeEventListener('click', this.handlers.onFieldTogglerClick);
      this.search.removeEventListener('submit', this.handlers.onSearchSubmit);
      this.input.removeEventListener('input', this.handlers.onSearchInput);
      this.searchImageInput.removeEventListener('focusin', this.handlers.onSearchImageFocusin);
      this.searchImageInput.removeEventListener('click', this.handlers.onSearchImageClick);
      this.field.removeEventListener('focusout', this.handlers.onFieldFocusout);
      document.removeEventListener('keydown', this.handlers.onEscapeKey);
      this.suggester.removeEventListener('click', this.handlers.onSuggestionItemClick);
      this.input.removeEventListener('keydown', this.handlers.onSearchKeydown);
      window.removeEventListener('resize', this.handlers.resizeHandler);
      window.removeEventListener('orientationchange', this.handlers.orientationchangeHandler);

      // dereference handler functions object
      this.handlers = null;

      // dereference DOM nodes
      this.header = null;
      this.search = null;
      this.field = null;
      this.input = null;
      this.searchImageInput = null;
      this.suggester = null;
      this.mqTestElem = null;

      this.isInited = false;
    }
  }
}

export default Search;
