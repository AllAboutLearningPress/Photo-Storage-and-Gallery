/*
 * Quick and (very) dirty code for editable details.
 * Should be replaced/incorporated with vue later, keeping the same functionality (I can help with that)
 * */
const editingKlass = 'is-editing';

const editableContainerKlass = 'js-editable';
const editableValueKlass = 'js-editable__val';
const formKlass = 'js-editable__form';
const textareaKlass = 'js-editable__area';
const confirmKlass = 'js-editable__confirm';

function resize(tx) {
  tx.style.height = 'auto';
  tx.style.height = tx.scrollHeight + 'px';
}

document.addEventListener('mousedown', (e) => {
  const editableValue = e.target.closest('.js-editable__val');
  const editableTrigger = e.target.closest('.js-editable__trigger');
  let container;
  let textarea;
  let clickedThing = editableValue || editableTrigger;

  if (clickedThing) {
    container = clickedThing.closest(`.${editableContainerKlass}`);
    textarea = container.querySelector(`.${textareaKlass}`);

    /*
     * `<textarea>` somehow gets focused out on any mousedown,
     * so without `setTimeout` the whole thing "closes" immediately after the opening
     * */
    setTimeout(() => {
      container.classList.add(editingKlass);
      container.querySelector(`.${textareaKlass}`).disabled = false;
      resize(textarea);
      textarea.focus();

      // allow android chrome to lag, and then actually do select text
      setTimeout(() => textarea.select(), 0);
    }, 0);
  }
});

document.addEventListener('submit', (e) => {
  const editableForm = e.target.closest(`.${formKlass}`);
  let container;
  let textarea;
  let newValue;

  e.preventDefault();

  if (editableForm) {
    container = editableForm.closest(`.${editableContainerKlass}`);
    textarea = container.querySelector(`.${textareaKlass}`);

    container.classList.remove(editingKlass);
    newValue = textarea.value.trim();

    if (newValue) {
      container.querySelector(`.${editableValueKlass}`).innerHTML = newValue;
      textarea.dataset.latestValue = newValue;
    }
  }
});

document.addEventListener('input', (e) => {
  const tx = e.target.matches(`.${textareaKlass}`) && e.target;

  tx && resize(tx);
});

let isEditableConfirmMousePressed;

document.addEventListener(
  'mousedown',
  (e) => {
    const confirmBtn = e.target.closest(`.${confirmKlass}`);

    if (confirmBtn) {
      isEditableConfirmMousePressed = true;
    }
  },
  true
);
document.addEventListener(
  'mouseup',
  (e) => {
    const confirmBtn = e.target.closest(`.${confirmKlass}`);

    if (confirmBtn) {
      isEditableConfirmMousePressed = false;
    }
  },
  true
);
document.addEventListener('focusout', (e) => {
  const actionEntitiesSelector = `.${textareaKlass}, .${confirmKlass}`;
  let container;
  let textarea;

  if (
    e.target.matches(actionEntitiesSelector) &&
    (!e.relatedTarget || !e.relatedTarget.matches(actionEntitiesSelector)) &&
    !isEditableConfirmMousePressed
  ) {
    container = e.target.closest(`.${editableContainerKlass}`);
    textarea = container.querySelector(`.${textareaKlass}`);

    container.classList.remove(editingKlass);
    container.querySelector(`.${textareaKlass}`).disabled = true;
    textarea.value = textarea.dataset.latestValue || textarea.defaultValue;
  }
});
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    [].slice
      .call(document.querySelectorAll(`.${editableContainerKlass}.${editingKlass}`))
      .forEach((elem) => {
        const textarea = elem.querySelector(`.${textareaKlass}`);

        elem.classList.remove(editingKlass);
        textarea.value = textarea.dataset.latestValue || textarea.defaultValue;
      });
  }
});
