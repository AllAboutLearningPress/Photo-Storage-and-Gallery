import { addEventListener } from '../utils';

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

const dropTargetAvailableEventName = 'drop-target-active';
const dropTargetUnavailableEventName = 'drop-target-inactive';

const activeDropTargetKlass = 'is-active';

/**
 * Global drop target. Processes drag and drop, dispatches custom event with an array of dropped file entries (see below).
 *
 * Custom events (which bubble) are triggered at the drop target DOM element:
 * - `drop-target-active`: the drop target is shown and ready for drop, the event passes along the `dataTransfer` object for the `dragenter` event
 * - `drop-target-inactive`: the drop target is hidden
 * - `items-dropped`: the items were the drop target, the event passes along the array of items with a type of FileSystemFileEntry
 *
 * For constructor, pass an optional second argument — an array of strings, representing allowed file extensions for the drop,
 * by default it allows all extensions using a single `'*'` string in the array.
 */

class DropTarget {
  constructor(dropTargetElem, allowedExtensions = ['*']) {
    this.dropTarget = dropTargetElem;
    this.allowedExtensions = allowedExtensions;

    if (!this.dropTarget) {
      return false;
    }

    const that = this;

    this.dragging = false;

    let count = 0;
    let cancelImmediate = () => {};

    async function processDroppedFiles(dataTransferItemList) {
      const allItems = await getAllFileEntries(dataTransferItemList);

      // filter allowed files by extension (that's appears to be what imgur is doing; checking MIME type is possible,
      // but it is an async operation of getting a File object for each entry, maybe it can be not that efficient i.e. for large group of items)
      const allowedItems = allItems.filter(
        (item) =>
          that.allowedExtensions.includes('*') ||
          that.allowedExtensions.includes(item.name.split('.').pop())
      );

      that.dropTarget.dispatchEvent(
        new CustomEvent('items-dropped', {
          bubbles: true,
          detail: {
            fileEntriesArray: allowedItems,
          },
        })
      );
    }

    // ignored dragged text strings
    function isValidDragItems(dataTransfer) {
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

          if (isValidDragItems(e.dataTransfer)) {
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

            if (isValidDragItems(e.dataTransfer)) {
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

          if (isValidDragItems(e.dataTransfer)) {
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
  destroy() {
    //unbind events
    this.handlers.forEach((fn) => fn && fn());

    // dereference handlers array
    this.handlers = null;

    this.dragging = false;

    // dereference DOM nodes
    this.dropTarget = null;

    this.allowedExtensions = null;

    this.isInited = false;
  }
}

export default DropTarget;
