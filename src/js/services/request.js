// todo: implement interaction with form validation enable/disable
// import { validToSubmit, isValidated, disableValidation, enableValidation } from './form-validity';
import { validToSubmit } from './lib/util/form-validity';
import getTimestamp from './lib/util/get-timestamp';

const targetKlass = 'js-request-target';
const processingKlass = 'is-processing';
const successKlass = 'is-successful';
const failKlass = 'is-failed';
const requestedKlass = 'is-requested';
const tightButtonKlass = 'button_tight';
const spinnerKlass = 'request-spinner';
const spinnerHTML = `<span class="${spinnerKlass}"></span>`;

function setDisabled(elem, disabled) {
  if (elem.tagName.toLowerCase() === 'button') {
    // eslint-disable-next-line no-param-reassign
    elem.disabled = disabled;
  }
}

function resetRequested(target) {
  const wasRequested = target.classList.contains(requestedKlass);

  if (target.classList.contains(processingKlass)) {
    return;
  }

  document.querySelectorAll(`[data-target-id='${target.dataset.targetId}']`).forEach((elem) => {
    elem.classList.remove(successKlass, requestedKlass);
    // eslint-disable-next-line no-param-reassign
    delete elem.dataset.targetId;

    if (elem !== target) {
      setDisabled(elem, false);
    }
  });

  // eslint-disable-next-line no-param-reassign
  delete target.dataset.feedbackTid;

  if (wasRequested) {
    target.classList.remove(targetKlass);
    target.dispatchEvent(new CustomEvent('content-update', { bubbles: true }));
  }

  // if (isValidated(target)) {
  //   enableValidation(target);
  // }
}

/**
 * Designate request state with a spinner, class names and events on target element(s)
 * @param fn {Function} - function with request code, can handle request promise (for early handling without `minimalRequestDelay`),
 * should return initial request object.
 * @param opts:
 *  target {HTMLElement} - element associated with request, which will receive class names and events regarding request state
 *  spinTarget {HTMLElement|Boolean} - optional element to append the spinner, will receive class names regarding request state. "False" for no spinner
 *  allowConcurrent {Boolean} - allow concurrent requests while there is already pending request for the same target
 *  allowSubsequent {Boolean} - always allow requests for the same target (as opposed to allowing only one successful request)
 * @returns {Promise} - initial request object
 */
function request(fn, opts) {
  const defaults = {
    target: document.documentElement,
    spinTarget() {
      return this.target.querySelector('button[type="submit"]') || this.target;
    },
    allowConcurrent() {
      return this.target.tagName.toLowerCase() !== 'form';
    },
    allowSubsequent: true,
  };
  // eslint-disable-next-line prefer-object-spread
  const options = Object.assign({}, defaults, opts);

  const { target, allowSubsequent } = options;
  const resultRecoverTimeout = 1000;
  const minimalRequestDelay = 300;
  let { spinTarget, allowConcurrent } = options;

  if (typeof spinTarget === 'function') {
    spinTarget = spinTarget.apply(options);
  }

  if (typeof allowConcurrent === 'function') {
    allowConcurrent = allowConcurrent.apply(options);
  }

  if (
    !validToSubmit(target) ||
    (!allowConcurrent && target.classList.contains(processingKlass)) ||
    (!allowSubsequent && target.classList.contains(requestedKlass))
  ) {
    return false;
  }

  const req = fn();
  let pendingSize = Number(target.dataset.pending) || 0;

  // if (!allowConcurrent && isValidated(target)) {
  //   disableValidation(target);
  // }

  if (spinTarget) {
    if (typeof spinTarget.dataset.spinner === 'undefined') {
      spinTarget.insertAdjacentHTML('beforeend', spinnerHTML);
      // eslint-disable-next-line no-param-reassign
      spinTarget.dataset.spinner = true;
    }

    if (!spinTarget.classList.contains(processingKlass)) {
      if (spinTarget.classList.contains('button')) {
        const innerElems = spinTarget.querySelectorAll('.button__icon, .button__text');
        const space = Math.abs(
          spinTarget.getBoundingClientRect().right -
            (innerElems[1] || innerElems[0]).getBoundingClientRect().right
        );
        const spinnerStyle = getComputedStyle(spinTarget.querySelector(`.${spinnerKlass}`));
        const spinnerWidth =
          parseInt(spinnerStyle.width, 10) +
          parseInt(spinnerStyle.borderLeftWidth, 10) +
          parseInt(spinnerStyle.borderRightWidth, 10);
        const spacing = 10; // empty space on the both sides of a spinner

        // console.log(space, spinnerWidth + spacing, space < spinnerWidth + spacing);

        if (space < spinnerWidth + spacing) {
          spinTarget.classList.add(tightButtonKlass);
        }
      }

      spinTarget.classList.add(processingKlass);
    }

    if (!allowConcurrent) {
      // allow button events to bubble before disabling
      setTimeout(() => setDisabled(spinTarget, true), 0);
    }
  }

  target.classList.add(processingKlass);
  pendingSize += 1;
  target.dataset.pending = pendingSize;

  if (target.dataset.feedbackTid > -1) {
    clearTimeout(target.dataset.feedbackTid);
    target.dataset.feedbackTid = -1;
  }

  function cb(isSuccess, response) {
    if (isSuccess && !allowSubsequent) {
      target.classList.add(requestedKlass);
      target.dispatchEvent(new CustomEvent('content-update', { bubbles: true }));

      if (spinTarget) {
        spinTarget.classList.add(requestedKlass);
      }

      const targetId = getTimestamp();

      if (!('targetId' in target.dataset)) {
        target.dataset.targetId = targetId;
        target.classList.add(targetKlass);

        if (spinTarget) {
          spinTarget.dataset.targetId = targetId;
        }
      } else if (spinTarget && !('targetId' in spinTarget.dataset)) {
        spinTarget.dataset.targetId = target.dataset.targetId;
      }
    }

    // if (!allowConcurrent && isValidated(target)) {
    //   enableValidation(target);
    // }

    let pending = Number(target.dataset.pending) || 0;

    if (pending < 2) {
      target.classList.remove(processingKlass);
      target.classList.add(isSuccess ? successKlass : failKlass);

      if (spinTarget) {
        spinTarget.classList.remove(processingKlass);
        spinTarget.classList.add(isSuccess ? successKlass : failKlass);

        if (!allowConcurrent) {
          setDisabled(spinTarget, false);
        }
      }

      target.dataset.feedbackTid = setTimeout(() => {
        target.classList.remove(failKlass);

        if (spinTarget) {
          spinTarget.classList.remove(failKlass, tightButtonKlass);
        }

        if (allowSubsequent) {
          target.classList.remove(successKlass);

          if (spinTarget) {
            spinTarget.classList.remove(successKlass);
          }
        }
      }, resultRecoverTimeout);
    }

    pending -= 1;
    target.dataset.pending = pending;
    target.dispatchEvent(
      new CustomEvent(`request-${isSuccess ? 'success' : 'fail'}`, {
        bubbles: true,
        detail: response,
      })
    );
  }

  function delay(ms) {
    return new Promise((resolve) => setTimeout(() => resolve(), ms));
  }

  return Promise.allSettled([req, delay(minimalRequestDelay)])
    .then(
      (results) =>
        (results[0].value && Promise.resolve(results[0].value)) || Promise.reject(results[0].reason)
    )
    .then(
      (response) => cb(true, response),
      (error) => cb(false, error)
    )
    .then(() => req);
}
//
// if (window.jQuery) {
//   jQuery(document).on('hidden.bs.modal', (e) => {
//     const requestedTarget = e.target.querySelector(`.${requestedKlass}`);
//
//     if (requestedTarget) {
//       resetRequested(requestedTarget);
//     }
//   });
// }

export { request as default, resetRequested, targetKlass };
