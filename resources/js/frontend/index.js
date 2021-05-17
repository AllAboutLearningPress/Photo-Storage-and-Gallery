import allowedMimeTypes from './util/allowedMimeTypes';

import Sidebar from './components/Sidebar';
import Search from './components/Search';
//import Upload from './components/Upload';
import GlobalDropTarget from './components/GlobalDropTarget';

// misc app-wide global code, can be included and forgotten about:
import './components/global-misc';

// misc things for demo purposes (see docs):
import './components/tags';
import './components/editable';

// generic things
const globalDropTarget = new GlobalDropTarget(allowedMimeTypes);
const upload = new Upload(document.querySelector('.js-upload'));

// main page widgets
//const contentSidebar = new Sidebar(document.querySelector('.js-content-sidebar'));
const search = new Search(document.querySelector('.js-search'));

// image view widgets
const imageDetailsSidebar = new Sidebar(document.querySelector('.js-image-details-sidebar'), {
    saveState: true,
});