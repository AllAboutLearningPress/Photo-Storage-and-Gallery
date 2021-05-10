import { addEventListener } from '../util/utils';
import Notificator from './Notificator';

const dropTargetAvailableEventName = 'drop-target-active';
const dropTargetUnavailableEventName = 'drop-target-inactive';

const activeDropTargetKlass = 'is-active';

function setImmediate(callback, ...args) {
  let cancelled = false;
  Promise.resolve().then(() => cancelled || callback(...args));
  return () => {
    cancelled = true;
  };
}

// https://stackoverflow.com/a/53058574/718630 :

/**
 * Drop handler function to get all files entries
 * @param dataTransferItemList{DataTransferItemList} - a list of files from a drop: https://developer.mozilla.org/en-US/docs/Web/API/DataTransferItemList
 * @returns {Promise<[FileSystemFileEntry]>} - a promise resolving to an array of file entries: https://developer.mozilla.org/en-US/docs/Web/API/FileSystemFileEntry
 */
async function getAllFileEntries(dataTransferItemList) {
  let fileEntries = [];
  // Use BFS to traverse entire directory/file structure
  let queue = [];
  // Unfortunately dataTransferItemList is not iterable i.e. no forEach
  for (let i = 0; i < dataTransferItemList.length; i++) {
    queue.push(dataTransferItemList[i].webkitGetAsEntry());
  }
  while (queue.length > 0) {
    let entry = queue.shift();
    if (entry.isFile) {
      fileEntries.push(entry);
    } else if (entry.isDirectory) {
      queue.push(...(await readAllDirectoryEntries(entry.createReader())));
    }
  }
  return fileEntries;
}

// Get all the entries (files or sub-directories) in a directory
// by calling readEntries until it returns empty array
async function readAllDirectoryEntries(directoryReader) {
  let entries = [];
  let readEntries = await readEntriesPromise(directoryReader);
  while (readEntries.length > 0) {
    entries.push(...readEntries);
    readEntries = await readEntriesPromise(directoryReader);
  }
  return entries;
}

// Wrap readEntries in a promise to make working with readEntries easier
// readEntries will return only some of the entries in a directory
// e.g. Chrome returns at most 100 entries at a time
async function readEntriesPromise(directoryReader) {
  try {
    return await new Promise((resolve, reject) => {
      directoryReader.readEntries(resolve, reject);
    });
  } catch (err) {
    console.log(err);
  }
}

// get a file from FileEntry, promisified
async function getFile(fileEntry) {
  try {
    return await new Promise((resolve, reject) => fileEntry.file(resolve, reject));
  } catch (err) {
    console.log(err);
  }
}

let instance = null;

/**
 * Global drop target and a Singleton.
 * Processes drag and drop, dispatches custom event with an array of dropped File objects (see below).
 *
 * Custom events (which bubble) are triggered at the drop target DOM element:
 * - `drop-target-active`: the drop target is shown and ready for drop, the event passes along the `dataTransfer` object for the `dragenter` event
 * - `drop-target-inactive`: the drop target is hidden
 * - `items-dropped`: the items were the dropped at the target, the event passes along the array of File objects
 *
 * For constructor, pass an optional second argument — an array of strings, representing allowed MIME types for the drop,
 * by default it allows all types using a single `'*'` string in the array.
 */

class GlobalDropTarget {
  constructor(allowedMimeTypes = ['*']) {
    if (instance) {
      return instance;
    }

    this.dropTarget = document.querySelector('.js-drop-target');

    if (!this.dropTarget) {
      return false;
    }

    this.allowedMimeTypes = allowedMimeTypes;

    const that = this;

    this.dragging = false;

    let count = 0;
    let cancelImmediate = () => {};

    this.notificator = new Notificator();

    /**
     * Uploading directories requires using DataTransferItemList (instead of FileList) which supports `webkitGetAsEntry()` on items.
     * Using `DataTransferItemList` means that instead of an actual file we get FileSystemFileEntry object: https://developer.mozilla.org/en-US/docs/Web/API/FileSystemFileEntry
     * This object needs to be converted to a file using an async `.file()` method.
     * Because of that, `processDroppedFiles` outputs an array of promises.
     */
    async function processDroppedFiles(dataTransferItemList) {
      const allItems = await getAllFileEntries(dataTransferItemList);

      // convert file entry to File object, filter allowed files by a MIME type
      const settledFilePromises = await Promise.allSettled(
        allItems.map(async (entry) => await getFile(entry))
      );
      const allowedFiles = settledFilePromises
        // we're expecting no errors
        .map((result) => result.value)
        .filter(
          (item) => that.allowedMimeTypes.includes('*') || that.allowedMimeTypes.includes(item.type)
        );

      that.dropTarget.dispatchEvent(
        new CustomEvent('items-dropped', {
          bubbles: true,
          detail: {
            fileArray: allowedFiles,
          },
        })
      );

      // notify if unsupported files were dropped
      if (allItems.length !== allowedFiles.length) {
        that.notificator.show('.js-invalid-drop-note');
      }
    }

    function isNotDraggedString(dataTransfer) {
      return !(dataTransfer.items.length === 1 && dataTransfer.items[0].kind === 'string');
    }

    this.handlers = [
      addEventListener(document, 'dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
      }),
      addEventListener(document, 'dragenter', (e) => {
        e.preventDefault();

        if (count === 0) {
          that.dragging = true;

          if (isNotDraggedString(e.dataTransfer)) {
            that.dropTarget.dispatchEvent(
              new CustomEvent(dropTargetAvailableEventName, {
                bubbles: true,
                detail: {
                  dataTransfer: e.dataTransfer,
                },
              })
            );
          }
        }

        count += 1;
      }),
      addEventListener(document, 'dragleave', (e) => {
        e.preventDefault();

        cancelImmediate = setImmediate(() => {
          count -= 1;

          if (count === 0) {
            this.dragging = false;

            if (isNotDraggedString(e.dataTransfer)) {
              this.dropTarget.dispatchEvent(
                new CustomEvent(dropTargetUnavailableEventName, {
                  bubbles: true,
                })
              );
            }
          }
        });
      }),
      addEventListener(document, 'drop', (e) => {
        e.preventDefault();

        cancelImmediate();

        if (count > 0) {
          count = 0;
          this.dragging = false;

          if (isNotDraggedString(e.dataTransfer)) {
            // uploading directories requires using DataTransferItemList (instead of FileList) which supports `webkitGetAsEntry()` on items
            processDroppedFiles(e.dataTransfer.items);
            this.dropTarget.dispatchEvent(
              new CustomEvent(dropTargetUnavailableEventName, {
                bubbles: true,
              })
            );
          }
        }
      }),
      addEventListener(this.dropTarget, dropTargetAvailableEventName, (e) => {
        e.target.classList.add(activeDropTargetKlass);
      }),
      addEventListener(this.dropTarget, dropTargetUnavailableEventName, (e) => {
        e.target.classList.remove(activeDropTargetKlass);
      }),
    ];

    this.isInited = true;
  }
}

export default GlobalDropTarget;
