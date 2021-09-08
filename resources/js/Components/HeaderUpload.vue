<template>
    <div class="header__slot_actions header__slot">
        <label
            title="Upload"
            class="
                js-upload
                header__action-btn
                button-file
                btn btn-subtle btn-lg
            "
        >
            <cloud-upload-icon></cloud-upload-icon>
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
import CloudUploadIcon from "@/Icons/CloudUploadIcon.vue";
import { notify } from "@/util.js";
import { inject } from "@vue/runtime-core";
export default {
    // inject: ["filesArray", "pushToFilesArray"],
    components: { CloudUploadIcon },
    setup() {
        const pushToFilesArray = inject("pushToFilesArray");
        const filesArray = inject("filesArray");
        const total = inject("total");
        const updateTotal = inject("updateTotal");
        const uploadedCount = inject("uploadedCount");
        const increaseUploadedCount = inject("increaseUploadedCount");
        const resetFuncs = inject("resetFuncs");
        const cancelToken = inject("cancelToken");
        const dropManager = new SingleImageDropManager();

        return {
            pushToFilesArray,
            filesArray,
            total,
            updateTotal,
            uploadedCount,
            increaseUploadedCount,
            dropManager,
            resetFuncs,
            cancelToken,
        };
    },
    data() {
        return this.initialData();
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

        // This event is triggered by Upload.js to
        // pass files with allowed
        document.addEventListener("items-dropped", (e) =>
            this.handleFileDrop(e)
        );
        // setInterval(() => {
        //     console.log("total in header upload", this.total);
        // }, 1500);

        this.resetFuncs.push(() => {
            Object.assign(this.$data, this.initialData());
            console.log("assigned initial data in headerupload");
        });
    },
    methods: {
        initialData() {
            return {
                // filesArray: [
                //     // {
                //     //     file: File,
                //     //     title: String,
                //     //     serverId: BigInt,
                //     //     hasDuplicate: false,
                //     //     tags: [],
                //     //     token: String, // this token will be used to uniquely identify this file
                //     //     id: BigInt,
                //     //     privLoaded: BigInt,
                //     //     notificator: null,
                //     // },
                // ],
                tags: [],
                maxUploadingCount: 40,
                uploadingCount: 0,
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
                completedCount: 0,
                // cancelToken: axios.CancelToken.source(), // used to cancel axios request
            };
        },
        /** This event is dispatched by axios when data is being
         * sent to server. This event is responsible for matching
         * the global progressbar and single file progress bars
         * on /upload page
         */
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

        /**Handles single file drop event */
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
            if (!filesArray.length) {
                // to handle empty events
                return;
            }
            let total = 0;
            let tempId = 0;
            let requestPhotos = [];
            // generating filesArray for handling user modification
            filesArray.forEach((file) => {
                total += file.size;
                this.pushToFilesArray({
                    file: file,
                    title: file.name,
                    tags: [], // used to store tags for this file
                    hasDuplicate: false,
                    tempId: tempId, // is used to match server id to this object
                    id: null, // id returned by server
                    total: file.size, // this total is larger than file size due the other datas
                    loaded: 0, // used to calculate progress bar in /upload page
                    isUploading: false,
                    width: "0%", // used to show progress bar
                    uploadCompleted: false,
                    cancelSource: axios.CancelToken.source(), // used to cancel axios request
                    cancelled: false,
                });
                requestPhotos.push({
                    tempId: tempId,
                    title: file.name,
                    size: file.size,
                    ext: this.getFileExt(file.name),
                });
                tempId++;
            });
            this.updateTotal(total);
            console.log("total in headerupload ", this.total);

            // sending request to server to create the rows for photos
            // we will use the server returned id for additional requests
            axios
                .post(route("uploads.store"), requestPhotos)
                .then((resp) => {
                    resp.data.forEach((photo) => {
                        for (let i = 0; i < this.filesArray.length; i++) {
                            if (this.filesArray[i].tempId == photo.tempId) {
                                // related file found
                                // adding the photo id returned by server
                                this.filesArray[i].id = photo.id;
                                this.filesArray[i].uploadUrl = photo.uploadUrl;
                                break;
                            }
                        }
                    });
                    this.uploadFiles();
                })
                .catch((err) => {
                    console.log(err);
                    notify("Upload Failed");
                });

            // go to upload details page where user can modify file details
            this.$inertia.visit("/upload");
        },
        /** Add all the file size to total */
        calculateTotalBytes() {
            this.total = 0;
            this.filesArray.forEach((file) => {
                this.total += file.file.size;
            });
        },
        /**
         * this will start 4 simultaneous file upload
         * After one upload is completed this fucntion will
         * called again and it will start new uploads if there
         * is any file left
         */
        uploadFiles() {
            if (this.uploadingCount < this.maxUploadingCount) {
                if (this.fileIndex < this.filesArray.length) {
                    this.uploadSingleFile(this.fileIndex);
                    this.fileIndex++;
                    this.uploadingCount++;
                    setTimeout(this.uploadFiles, 0);
                } else if (this.uploadedCount == this.filesArray.length) {
                    // all files uploaded
                    notify(
                        "Upload finished. Please complete the upload",
                        "success"
                    );
                    // If user is not on the /upload then visiting /upload page
                    // So they can do a final editing of file details.
                    if (route().current() != route("uploads.index")) {
                        this.$inertia.visit(route("uploads.index"));
                    }
                }
            }
        },
        /**
         * Uploads a single file
         * @property {File} file - The file to upload
         * @property {boolean} retry - True if file upload is retried, Defaults to False
         */
        uploadSingleFile(filePostion, retry = false) {
            // marking it as uploading so it wont be
            // picked up again for uploading
            this.filesArray[filePostion].isUploading = true;

            const formData = new FormData();
            formData.append("file", this.filesArray[filePostion].file);
            formData.append("id", this.filesArray[filePostion].id);

            // uploading the file
            axios
                .put(
                    this.filesArray[filePostion].uploadUrl,
                    this.filesArray[filePostion].file,
                    {
                        cancelToken:
                            this.filesArray[filePostion].cancelSource.token,
                        // cancelToken: this.cancelToken.token,
                        onUploadProgress: (e) => {
                            // bug
                            this.filesArray[filePostion].loaded = e.loaded;
                            this.filesArray[filePostion].width =
                                (e.loaded / e.total) * 100 + "%";
                        },
                    }
                )
                .then((response) => {
                    // this will be used to show the tick on individual
                    // files on /upload page
                    this.filesArray[filePostion].uploadCompleted = true;
                    // bug
                    this.sendUploadCompletedReq(
                        this.filesArray[filePostion].id
                    );
                })
                .catch((error) => {
                    // We shouldnt retry if upload is cancelled by user
                    if (!axios.isCancel(error)) {
                        console.log(error);
                        if (retry == false) {
                            this.uploadSingleFile(filePostion, (retry = true));
                        } else {
                            // Show error notification to user
                            notify(
                                "Upload Failed: " +
                                    this.filesArray[filePostion].title,
                                "danger"
                            );
                        }
                    }
                });
        },
        sendUploadCompletedReq(id) {
            axios.post(route("uploads.complete"), { id: id }).then((resp) => {
                this.increaseUploadedCount(1);

                // upload finished. Now it will check and
                // start a new upload
                this.uploadingCount--;
                this.uploadFiles();
            });
        },

        /**
         * Retuns a cryptographically random token
         * see more at https://stackoverflow.com/a/40031979
         * @property {string} length - Length of random token
         * @returns {string}
         */
        randHexToken(length = 64 / 2) {
            // token shouldn't be more than 128 charac ters(assuming 8 bits for a character)
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
        /** Not a safe way to get file extension
         * Extension will be checked on the server
         */
        getFileExt(name) {
            return name.split(".").pop();
        },
    },
};
</script>
