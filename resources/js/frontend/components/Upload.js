import { addEventListener } from '../util/utils';
import allowedMimeTypes from '../util/allowedMimeTypes';

import SingleImageDropManager from './SingleImageDropManager';

/**
 * Upload. A singleton
 */

let instance = null;

class Upload {
  constructor() {
    if (instance) {
      return instance;
    }

    const that = this;

    this.dropManager = new SingleImageDropManager();

    function handleFileDrop(e) {
      const isUploadDrop =
        !that.dropManager.isInited || that.dropManager.getLatestDropStats().upload;

      if (isUploadDrop) {
        that.handleUpload(e.detail.fileArray);
      }
    }

    this.handlers = [
      addEventListener(document, 'change', (e) => {
        const isUploadChange = e.target.matches('.js-upload__input');

        if (isUploadChange) {
          // filter passed files by MIME type
          this.handleUpload(
            [...e.target.files].filter((file) => allowedMimeTypes.includes(file.type))
          );
        }
      }),
      addEventListener(document, 'items-dropped', (e) => {
        handleFileDrop(e);
      }),
    ];

    this.isInited = true;
  }

  /*
   * Handle upload of passed files.
   * The method receives an array of File objects as a `filesArray` argument.
   * */
  handleUpload(filesArray) {
    if (!filesArray.length) {
      return;
    }

    console.log(filesArray);
    alert(`render upload view with ${filesArray.length} file${filesArray.length === 1 ? '' : 's'}`);
  }
}

export default Upload;
