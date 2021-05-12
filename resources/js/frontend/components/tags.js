/*
 * A dumb file for handling tags, feel free to write all you want here
 * */

// WARNING: this code should exist in one form or another
// (a click event handler with an exact same code as written, `alert` can be deleted)
document.addEventListener('click', (e) => {
  const tagDeleter = e.target.closest('.js-tag-delete');

  if (tagDeleter) {
    e.preventDefault();
    alert('delete tag');
  }
});


// dumb demo code
document.addEventListener('input', (e) => {
  const tagInput = e.target.closest('.js-tags__input');

  if (tagInput) {
    setTimeout(() => {
      document.querySelector('#tag-list').innerHTML = '<option value="Zoo">';

      if (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
        tagInput.blur();
        tagInput.focus();
      }
    }, 2000);
  }
});
