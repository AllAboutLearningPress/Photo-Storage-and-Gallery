# Photo-Storage-and-Gallery frontend

## Getting started

Download repository, install project dependencies running `npm i` in the command line inside the root folder. Then you can: 
- `npm run watch` to start in dev mode; 
- `npm run bundle` to bundle js and css; 

## Notes

See code (html/js), and comments

## "Uploading" state

When uploading is happening, `<body>` should have `is-uploading` classname

### JS components initialisation regarding client-side page renders

Singletons, which should be instantiated once for the app lifecycle and kept across page renders:

- `GlobalDropTarget.js`: the `.js-drop-target` DOM element needs to stay same across page renders.
- `Notificator.js`: the `.js-notification-container` DOM element needs to stay same across page renders.
- `Upload.js`: DOM can be changed in any way across page renders.
- `SingleImageDropManager.js`: this module isn't directly instantiated by the app, but by its submodules (Search and Upload). DOM can be changed in any way across page renders.

Following components should be destroyed (`.destroy()`)/reinstantiated as needed:

- `Search.js`: `.js-search` doesn't always renders across pages (i.e. on image view).
- `Sidebar.js`: sidebar can be different across pages and may be absent

Following files are mostly for demo purposes and should be later replaced/incorporated with vue, keeping the same functionality (I can help with that):

- `editable.js`
- `tags.js`

### Notifications

You can use `Notificator.js` for toast notifications. For that:

- always keep required notification markup at any page (see `.notification-area` in pages markup for details)
- add required markup for a specific toast inside `.notification-area` (see pages markup for details)
- add unique class name to the toast, i.e. `.js-invalid-drop-note`
- show notification like so:
```js
import Notificator from './Notificator';

const notificator = new Notificator();

notificator.show('.js-invalid-drop-note');
```

### Drop target

Drop target receives files, filters invalid MIME types and dispatches a custom DOM event passing along the array of File objects, see `GlobalDropTarget.js`

### Drag-n-drop image search

The separate module (`SingleImageDropManager.js`) is handling a search/upload distinction if a single file with a valid MIME type was dropped. Pages containing a searchbar also include a markup for this (`.js-drop-manager`). Pages with no searchbar (like image view) shouldn't contain this markup.

### Image view sidebar

It can be required to render sidebar in the opened state on a page render.
For that, the `is-open` classname should be added to sidebar element.
The problem is that it should NOT be added if sidebar is in "overlay mode"
(this is based on CSS media-query).

3 possible scenarios are as follows (with appropriate solution):

1. Page is fully rendered server-side: we can't detect media-query server-side and `is-open` classname
shouldn't be added. The `Sidebar.js` will handle this and open sidebar as needed after JS initializes.
Also, generally `Sidebar.js` handles open/closed state, as well as saving it to localStorage. But it handles
it only after it gets initialised, and sometimes it can be unacceptable (though it actually might be acceptable
in all cases so this all warning would be useless in that case), i.e. if sidebar should be RENDERED
in the open state together with the whole page

2. Page is fully rendered client-side (i.e. going from gallery to image view): this appears to be our
main scenario, in this case the decision to render the classname should be based on a media query.
Sidebar instance exposes this as a `sidebar.isOverlaying()` method, so it goes similar to the following:
```js
const sidebar = new Sidebar('.js-image-details-sidebar');
// the sidebar instance localStorage key exposed as `sidebar.storageKey`
const savedState = JSON.parse(localStorage.getItem(sidebar.storageKey);
const shouldBeOpened = !sidebar.isOverlaying();

`<aside className="{savedState && shouldBeOpened ? 'is-open' : ''} sidebar">
  ...
</aside>`
```

### Search

Note that a searchbar has keyboard support for navigating suggestions (up/down arrows)

### Upload

Currently, upload just listens to event from `GlobalDropTarget` for dropped items (or handles files picked via file input) and renders a new page with an array of File objects available (modal could be used instead if required, see github issue). More code should be written in `Upload.js` for actual uploading

### Tags

`<datalist>` is used for a basic autocomplete functionality in a tag input (see https://github.com/AllAboutLearningPress/Photo-Storage-and-Gallery/issues/6#issuecomment-834731964). Dynamically populating a `<datalist>` with a fetched tags on input change is causing problems in iOS (at least), so that it might not be possible to do it reliably, and preloaded `<datalist>` might be the only option here. This requires further investigation
