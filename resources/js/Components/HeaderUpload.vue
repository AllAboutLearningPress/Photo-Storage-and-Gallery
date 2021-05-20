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
import SingleImageDropManager from "../frontend/components/SingleImageDropManager.js";
export default {
    data: function () {
        return {
            filesArray: [],
            maxUploadCount: 4,
            uploadCount: 0,
            fileCount: 0,
            allowedMimeTypes: [
                "image/jpeg",
                "image/png",
                "image/gif",
                "image/tiff",
                "image/vnd.adobe.photoshop",
            ],
        };
    },
    created() {
        this.dropManager = new SingleImageDropManager();
    },
    mounted() {
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
        document.addEventListener("items-dropped", (e) =>
            this.handleFileDrop(e)
        );
    },
    methods: {
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
            if (!filesArray.length) {
                return;
            }

            console.log(filesArray);
            console.log("uploading");

            this.$inertia.get("/upload");
            document.addEventListener("upload-view-created", () => {
                let event = new Event("uploading-files", filesArray);
                document.dispatchEvent(event);
            });
        },

        /**
         * Gets called when user selects the files
         * It previews the file list
         */
        previewFiles(event) {
            console.log(event);
            console.log(this.filesArray);
            Array.from(event.target.files).forEach((file) => {
                let fileAlreadyExist = false;
                for (let i = 0; i < this.filesArray.length; i++) {
                    if (
                        this.filesArray[i].file.name == file.name &&
                        this.filesArray[i].file.size == file.size &&
                        this.filesArray[i].file.type == file.type
                    ) {
                        // this file is already in the list and
                        console.log("file already exists");
                        return;
                    }
                }
                this.filesArray.push({
                    file: file,
                    id: null,
                    isUploading: false,
                });
            });

            console.log(this.filesArray);

            // start file uploading
            this.uploadFiles();
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

            // marking it as uploading so it wont be
            // picked up again for uploading
            this.filesArray[filePostion].isUploading = true;
            const formData = new FormData();
            formData.append("file", this.filesArray[filePostion]);
            axios
                .post(route("uploads.store_file"), formData)
                .then((response) => {
                    console.log(response);
                    this.uploadCount--;

                    // server returns the stored file id
                    this.filesArray[filePostion].id = response.data;
                    console.log(this.filesArray);
                    // upload finished. Now it will check and
                    // start a new upload
                    //this.uploadFiles();
                })
                .catch((error) => {
                    console.log(error);
                    if (retry == false) {
                        this.uploadSingleFile(filePostion, (retry = True));
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
    },
};
</script>
