import { addEventListener } from '../util/utils';
import allowedMimeTypes from '../util/allowedMimeTypes';

import SingleImageDropManager from './SingleImageDropManager';

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
        that.handleUpload(e.detail.fileArray);
      }
    }

    this.handlers = [
      addEventListener(this.fileInput, 'change', (e) => {
        // filter passed files by MIME type
        this.handleUpload(
          [...e.target.files].filter((file) => allowedMimeTypes.includes(file.type))
        );
      }),
      addEventListener(document, 'items-dropped', (e) => {
        handleFileDrop(e);
      }),
    ];

    this.isInited = true;
  }

  /*
   * Handle upload of passed files.
   *
   * We can get either an array of promises (coming from `processDroppedFiles`, see `js/components/DropTarget.js` for details),
   * or File objects (coming from a `<input type="file">`).
   *
   * To account for that, always use `Promise.resolve(file)` on array items, to make sure it is always a promise.
   * So to get any file from arguments array, `await Promise.resolve(file)` can be used.
   * */
  handleUpload(filesArray) {
    if (!filesArray.length) {
      return;
    }

    const files = [];

    filesArray.forEach(async (filePromise) => {
      files.push(await Promise.resolve(filePromise));
    });

    console.log(files);
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
