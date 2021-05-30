<template>
    <div class="header__slot_actions header__slot">
        <label
            title="Upload"
            class="js-upload header__action-btn button-file btn btn-subtle btn-lg"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="26"
                height="26"
                fill="currentColor"
                class="bi bi-cloud-upload"
                viewBox="0 0 16 16"
            >
                <path
                    fill-rule="evenodd"
                    d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"
                ></path>
                <path
                    fill-rule="evenodd"
                    d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"
                ></path>
            </svg>
            <span class="header__action-btn__txt">Upload</span>
            <input
                class="js-upload__input button-file__input"
                type="file"
                accept="image/jpeg, image/png, image/gif, image/tiff, image/vnd.adobe.photoshop, .jpg, .jpeg, .png, .gif, .tif, .tiff, .psd"
                multiple
            />
            <span class="visually-hidden">Upload more</span>
        </label>
    </div>
</template>

<script>
import axios from "axios";
import SingleImageDropManager from "../frontend/components/SingleImageDropManager.js";
import Notificator from "../frontend/components/Notificator.js";
export default {
    data: function () {
        return {
            filesArray: [
                {
                    file: File,
                    title: String,
                    serverId: BigInt,
                    hasDuplicate: false,
                    tags: [],
                    token: String, // this token will be used to uniquely identify this file
                    id: BigInt,
                    privLoaded: BigInt,
                    notificator: null,
                },
            ],
            tags: [],
            maxUploadCount: 4,
            uploadCount: 0,
            fileCount: 0,
            fileIndex: 0,
            total: 0,
            allowedMimeTypes: [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/tiff",
                "image/vnd.adobe.photoshop",
            ],
            cancelTokens: [],
        };
    },
    created() {
        this.dropManager = new SingleImageDropManager();
        this.notificator = new Notificator();

        // fetch tags lazily from server
        // this.fetchTags(route("tags.get_tags"));
    },
    mounted() {
        // event gets triggerd when new files are dragged
        // or selected by user
        document.addEventListener("change", (e) => {
            const isUploadChange = e.target.matches(".js-upload__input");

            if (isUploadChange) {
                // filter passed files by MIME type
                this.handleUpload(
                    [...e.target.files].filter((file) =>
                        this.allowedMimeTypes.includes(file.type)
                    )
                );
            }
        });
        this.notificator.show(".js-invalid-drop-note");

        // This event is triggered by Upload.js to
        // pass files with allowed
        document.addEventListener("items-dropped", (e) =>
            this.handleFileDrop(e)
        );
    },
    methods: {
        dispatchUploadProgress(e, fileId, bytesSent) {
            console.log("bytes sent in dispatch: ", bytesSent);
            document.dispatchEvent(
                new CustomEvent("update-progress-bar", {
                    detail: {
                        fileId: fileId,
                        loaded: e.loaded,
                        total: e.total,
                        bytesSent: bytesSent,
                    },
                })
            );
        },
        fetchTags(url) {
            axios
                .get(url)
                .then((resp) => {
                    this.tags.push(...resp.data.data);

                    // checking if there is more tags to fetch
                    // if there are more tags then server will
                    // return the next url to fetch data
                    if (resp.data.next_page_url) {
                        this.fetchTags(resp.data.next_page_url);
                    } else {
                        console.log(this.tags.length);
                        console.log("All tags fetched");
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        passDataToUploadView(event) {
            console.log("upload view created received");
        },
        handleFileDrop(e) {
            const isUploadDrop =
                !this.dropManager.isInited ||
                this.dropManager.getLatestDropStats().upload;

            if (isUploadDrop) {
                this.handleUpload(e.detail.fileArray);
            }
        },
        /*
         * Handle upload of passed files.
         * The method receives an array of File objects as a `filesArray` argument.
         * */
        handleUpload(filesArray) {
            console.log("uploading");
            console.log("files available: ", filesArray.length);
            let fileCount = this.filesArray.length - 1;
            filesArray.forEach((file) => {
                this.total = this.total + file.size;
                this.filesArray[this.fileCount] = {
                    file: file,
                    title: file.name,
                    serverId: null,
                    tags: [],
                    hasDuplicate: false,
                    token: this.randHexToken(128),
                    id: this.fileIndex,
                    privLoaded: 0,
                };
            });
            this.fileIndex++;

            document.dispatchEvent(
                new CustomEvent("update-progress-total", {
                    detail: {
                        total: this.total,
                        fileCount: this.filesArray.length,
                    },
                })
            );

            // start uploading
            this.uploadFiles();

            // show the upload details
            this.$inertia.get("/upload");

            // pass the files array to /upload page
            document.addEventListener("upload-view-created", () => {
                document.dispatchEvent(
                    new CustomEvent("uploading-files", {
                        detail: {
                            filesArray: this.filesArray,
                            //tags: this.tags,
                        },
                    })
                );
            });
        },

        /**
         * this will start 4 simultaneous file upload
         * After one upload is completed this fucntion will
         * called again and it will start new uploads if there
         * is any file left
         */
        uploadFiles() {
            console.log("upload started");
            console.log(this.filesArray.length);
            for (let i = 0; i < this.filesArray.length; i++) {
                //if (this.filesArray[i].isUploading == false) {
                this.uploadSingleFile(i);
                //    break;
                //}
            }

            // while (
            //     this.uploadCount < this.maxUploadCount &&
            //     this.fileCount < this.filesArray.length
            // ) {
            //     console.log(this.uploadCount);
            //     console.log(this.fileCount);
            //     // selecting a file to upload that is
            //     // not currently uploading
            //     for (let i = 0; i < this.filesArray.length; i++) {
            //         if (this.filesArray[i].isUploading == false) {
            //             this.uploadSingleFile(i);
            //             break;
            //         }
            //     }

            //     this.uploadCount++;
            //     this.fileCount++;
            // }
        },
        /**
         * Uploads a single file
         * @property {File} file - The file to upload
         * @property {boolean} retry - True if file upload is retried, Defaults to False
         */
        uploadSingleFile(filePostion, retry = false) {
            console.log("started ", this.filesArray[filePostion].name);
            let fileId = this.filesArray[filePostion].id;
            // marking it as uploading so it wont be
            // picked up again for uploading
            this.filesArray[filePostion].isUploading = true;
            const formData = new FormData();
            formData.append("file", this.filesArray[filePostion].file);
            formData.append("token", this.filesArray[filePostion].token);

            // setting a cancel token for this upload
            // This will be used to cancel a file upload
            this.cancelTokens[fileId] = axios.CancelToken.source();
            console.log(this.cancelTokens);

            // uploading the file
            axios
                .post(route("uploads.store_file"), formData, {
                    cancelToken: this.cancelTokens[fileId].token,
                    onUploadProgress: (e) => {
                        console.log(
                            "priv loaded: ",
                            this.filesArray[filePostion].privLoaded
                        );
                        this.dispatchUploadProgress(
                            e,
                            fileId,
                            e.loaded - this.filesArray[filePostion].privLoaded
                        );
                        this.filesArray[filePostion].privLoaded = e.loaded;
                    },
                })
                .then((response) => {
                    console.log(response);
                    this.uploadCount--;

                    // server returns the stored file id
                    this.filesArray[filePostion].serverId = response.data;
                    console.log(this.filesArray);

                    // dispatching event for /upload page
                    // If the upload details page is open
                    // then it will use this data to process
                    // user updated details

                    document.dispatchEvent(
                        new CustomEvent("file-uploaded", {
                            detail: {
                                name: this.filesArray[filePostion].token,
                                serverId: response.data,
                            },
                        })
                    );

                    // upload finished. Now it will check and
                    // start a new upload
                    //this.uploadFiles();
                })
                .catch((error) => {
                    console.log(error);
                    if (retry == false) {
                        this.uploadSingleFile(filePostion, (retry = true));
                    } else {
                        // Show error notification to user
                        console.error(
                            "File upload failed. File name: ",
                            this.filesArray[filePostion].name
                        );
                    }
                });
        },

        /**
         * Get called when user clicks cross button on file list
         * Removes the file with provided name
         * @property {string} name - The name of the file to remove
         */
        removeFile(name) {
            for (let i = 0; i < this.filesArray.length; i++) {
                if (this.filesArray[i].name == name) {
                    console.log(this.filesArray[i]);
                    this.filesArray.splice(i, 1);
                }
            }
        },

        /**
         * Retuns a cryptographically random token
         * see more at https://stackoverflow.com/a/40031979
         * @property {string} length - Length of random token
         * @returns {string}
         */
        randHexToken(length = 64 / 2) {
            // token shouldn't be more than 128 characters(assuming 8 bits for a character)
            if (length >= 128 / 2) {
                length = 128 / 2;
            }
            let buffer = new Int8Array(length);
            window.crypto.getRandomValues(buffer);

            // convert array buffer to hex string
            return [...new Uint8Array(buffer)]
                .map((x) => x.toString(16).padStart(2, "0"))
                .join("");
        },
    },
};
</script>
