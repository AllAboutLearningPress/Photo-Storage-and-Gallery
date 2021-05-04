import { addEventListener } from '../utils';

import SingleImageDropManager from './SingleImageDropManager';
import Modal from 'bootstrap/js/dist/modal';
import Toast from 'bootstrap/js/dist/toast';

/**
 * Upload
 */

class Upload {
  constructor(uploadElem) {
    this.upload = uploadElem;

    if (!this.upload) {
      return;
    }

    const that = this;

    this.fileInput = this.upload.querySelector('.js-upload__input');
    this.dropManager = new SingleImageDropManager(document.querySelector('.js-drop-manager'));

    function handleFileDrop(e) {
      const isUploadDrop =
        !that.dropManager.isInited || that.dropManager.getLatestDropStats().upload;

      if (isUploadDrop) {
        that.handleUpload(e.detail.fileEntriesArray);
      }
    }

    function handleFileinputChange(e) {
      that.handleUpload([...e.target.files]);
    }

    this.handlers = [
      addEventListener(this.fileInput, 'change', (e) => {
        handleFileinputChange(e);
      }),
      addEventListener(document, 'items-dropped', (e) => {
        handleFileDrop(e);
      }),
    ];

    this.isInited = true;
  }
  handleUpload(filesArray) {
    if (!filesArray.length) {
      return;
    }

    alert(`initiate upload of ${filesArray.length} files`);

    // filesArray.forEach((file) => {
    //   console.log(file.name);
    // });
  }
  destroy() {
    //unbind events
    this.handlers.forEach((fn) => fn && fn());

    // dereference handlers array
    this.handlers = null;

    // dereference DOM nodes
    this.upload = null;
    this.fileInput = null;

    if (this.dropManager) {
      this.dropManager.destroy();
      this.dropManager = null;
    }

    this.isInited = false;
  }
}

export default Upload;
