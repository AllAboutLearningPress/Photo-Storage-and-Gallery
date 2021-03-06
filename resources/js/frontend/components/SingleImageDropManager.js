import { addEventListener, isKeyDown } from '../util/utils';
import allowedMimeTypes from '../util/allowedMimeTypes';

const managerKlass = 'js-drop-manager';
const singleImageTransferKlass = 'is-single-image';
const uploadDecisionKlass = 'is-upload-preferred';
const decisionKey = 'Shift'; // should be a valid `KeyboardEvent.key` value: https://developer.mozilla.org/en-US/docs/Web/API/KeyboardEvent/key

/**
 * Find out if a single file is being transferred.
 * "Single file" is defined as a single item in the `dataTransferItemList` with allowed MIME type present
 * (MIME type won't be present if it is a directory or a file without an extension).
 * @param dataTransfer - a `DataTransfer` object for a drag-n-drop (https://developer.mozilla.org/en-US/docs/Web/API/DataTransfer)
 * @returns {boolean}
 */
function isSingleAllowedImageBeingTransferred(dataTransfer) {
    return (
        dataTransfer.items.length === 1 &&
        dataTransfer.items[0].type.length > 0 &&
        allowedMimeTypes.includes(dataTransfer.items[0].type)
    );
}

let instance = null;

/**
 * A singleton. Manage a drop of an individual image file:
 * - handle UI for modifying the resulting drop action (search or upload)
 * - provide details for the latest single image file drop event
 */
class SingleImageDropManager {
    constructor() {
        if (instance) {
            return instance;
        }

        this.stats = {
            search: false,
            upload: true,
        };

        const that = this;

        let deciderHandlers = [];
        let windowFocusHandlers = [];

        function toggleSearchAbility(flag) {
            const dropManager = document.querySelector(`.${managerKlass}`);

            if (dropManager) {
                dropManager.classList.toggle(uploadDecisionKlass, flag);
                that.stats.search = flag;
                that.stats.upload = !flag;
            }
        }

        function resetToDefault() {
            const dropManager = document.querySelector(`.${managerKlass}`);

            if (dropManager) {
                dropManager.classList.remove(singleImageTransferKlass);
                dropManager.classList.remove(uploadDecisionKlass);
                deciderHandlers.forEach((unbind) => unbind());
                windowFocusHandlers.forEach((unbind) => unbind());
            }
        }

        function processDraggedFiles(dataTransfer) {
            const isAllowed = isSingleAllowedImageBeingTransferred(dataTransfer);
            const isBrowserFocused = document.hasFocus();
            const dropManager = document.querySelector(`.${managerKlass}`);

            if (dropManager && isAllowed) {
                if (isBrowserFocused) {
                    dropManager.classList.add(singleImageTransferKlass);
                } else {
                    windowFocusHandlers = [
                        addEventListener(window, 'focus', (e) => {
                            dropManager.classList.add(singleImageTransferKlass);
                        }),
                        addEventListener(window, 'blur', (e) => {
                            dropManager.classList.remove(singleImageTransferKlass);
                        }),
                    ];
                }

                if (isKeyDown(decisionKey)) {
                    toggleSearchAbility(true);
                }

                deciderHandlers = [
                    addEventListener(document, 'keydown', (e) => {
                        if (e.key === decisionKey) {
                            toggleSearchAbility(true);
                        }
                    }),
                    addEventListener(document, 'keyup', (e) => {
                        if (e.key === decisionKey) {
                            toggleSearchAbility(false);
                        }
                    }),
                ];
            }
        }

        this.handlers = [
            addEventListener(document, 'drop-target-active', (e) => {
                processDraggedFiles(e.detail.dataTransfer);
            }),
            addEventListener(document, 'drop-target-inactive', (e) => {
                resetToDefault();
            }),
            addEventListener(document, 'items-dropped', (e) => {
                resetToDefault();
            }),
        ];

        this.isInited = true;
    }
    getLatestDropStats() {
        return this.stats;
    }
}

export default SingleImageDropManager;