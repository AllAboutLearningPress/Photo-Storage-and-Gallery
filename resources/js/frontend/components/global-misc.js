/*
 * Misc app-wide global code. Can be included and forgotten about
 * */

/**
 * Allow bootstrap checkbox buttons (https://getbootstrap.com/docs/5.0/forms/checks-radios/#toggle-buttons)
 * to be activated with an "enter" key
 */
document.addEventListener('keypress', (e) => {
  if (e.target.matches('.btn-check') && e.key === 'Enter') {
    e.target.checked = !e.target.checked;
  }
});
