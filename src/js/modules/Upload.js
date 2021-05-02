/**
 * Upload
 */

class Upload {
  constructor() {
    this.upload = null;
    this.handlers = null;

    this.isInited = false;
  }
  init(uploadElem) {
    this.upload = uploadElem;

    if (this.isInited || !this.upload) {
      return;
    }

    const that = this;

    this.handlers = {
      // handle `change` event on the upload file input
      onUploadInputChange(e) {
        const uploader = e.target.closest('.js-upload__input');

        if (uploader) {
          alert(`selected ${uploader.files.length} files.`);
        }
      }
    };

    this.upload.addEventListener('change', this.handlers.onUploadInputChange);

    this.isInited = true;
  }
  destroy() {
    //unbind events
    this.upload.removeEventListener('change', this.handlers.onUploadInputChange);

    // dereference handler functions object
    this.handlers = null;

    // dereference DOM nodes
    this.upload = null;

    this.isInited = false;
  }
}

export default Upload;
