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
//
// document.querySelector('#tag-input').addEventListener('input', (e) => {
//   setTimeout(() => {
//     document.querySelector('#tag-list').innerHTML = '<option value="San Francisco">';
//   }, 2000);
// });
