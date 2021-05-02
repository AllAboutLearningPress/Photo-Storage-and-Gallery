import Sidebar from './components/Sidebar';
import Search from './components/Search';
import Upload from './components/Upload';

import './components/Tags';

const contentSidebar = new Sidebar();
const imageDetailsSidebar = new Sidebar();
const searchbar = new Search();
const upload = new Upload();

// Initialize things, use `.destroy()` to clean it up when needed
contentSidebar.init(document.querySelector('.js-content-sidebar'));
imageDetailsSidebar.init(document.querySelector('.js-image-details-sidebar'));
searchbar.init(document.querySelector('.js-search'));
upload.init(document.querySelector('.js-upload'));
