import Toast from 'bootstrap/js/dist/toast';

/**
 * A singleton. Handle initialising and showing toasts in a centralised way.
 * Requires a `.js-notification-container` HTML element to be present.
 */

let instance = null;

class Notificator {
  constructor() {
    if (instance) {
      return instance;
    }

    this.container = document.querySelector('.js-notification-container');

    if (!this.container) {
      return;
    }

    const that = this;

    this.toasts = [];
    this.handlers = [];

    this.isInited = true;
  }
  getElemAndInstanceBySelector(selector) {
    const elem = document.querySelector(selector);
    let instance;

    if (elem) {
      instance = Toast.getInstance(elem);

      if (!instance) {
        instance = new Toast(elem, {});
        this.toasts.push(instance);
      }
    } else {
      return {};
    }

    return {
      elem,
      instance,
    };
  }
  show(toastSelector) {
    const { elem, instance } = this.getElemAndInstanceBySelector(toastSelector);

    if (elem) {
      // always stack the newest toast at the beginning
      this.container.insertAdjacentElement('afterbegin', elem);
      instance.show();
    }
  }
  hide(toastSelector) {
    const { instance } = this.getElemAndInstanceBySelector(toastSelector);

    instance && instance.hide();
  }
  destroy() {
    //unbind events
    this.handlers.forEach((fn) => fn && fn());

    // dereference handlers array
    this.handlers = null;

    // dereference DOM nodes
    this.upload = null;
    this.container = null;

    // dispose toasts
    this.toasts.forEach((toast) => toast.dispose());
    this.toasts = null;

    instance = null;

    this.isInited = false;
  }
}

export default Notificator;
