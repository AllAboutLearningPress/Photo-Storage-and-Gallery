<template>
    <div>
        <input type="file" @change="previewFiles" multiple />
    </div>
    <div>
        <div v-for="fileEntry in filesArray" :key="fileEntry.file.name">
            {{ fileEntry.file.name }}
        </div>
    </div>

    <button class="btn" v-on:click="uploadFiles">upload</button>
</template>

<script>
export default {
    data: function () {
        return {
            filesArray: [],
            maxUploadCount: 4,
            uploadCount: 0,
            fileCount: 0,
        };
    },
    methods: {
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

            while (
                this.uploadCount < this.maxUploadCount &&
                this.fileCount < this.filesArray.length
            ) {
                console.log(this.uploadCount);
                console.log(this.fileCount);
                // selecting a file to upload that is
                // not currently uploading
                for (let i = 0; i < this.filesArray.length; i++) {
                    if (this.filesArray[i].isUploading == false) {
                        this.uploadSingleFile(i);
                        break;
                    }
                }

                this.uploadCount++;
                this.fileCount++;
            }
        },
        /**
         * Uploads a single file
         * @property {File} file - The file to upload
         * @property {boolean} retry - True if file upload is retried, Defaults to False
         */
        uploadSingleFile(filePostion, retry = false) {
            console.log("started ", this.filesArray[filePostion].file.name);

            // marking it as uploading so it wont be
            // picked up again for uploading
            this.filesArray[filePostion].isUploading = true;
            const formData = new FormData();
            formData.append("file", this.filesArray[filePostion].file);
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
                    this.uploadFiles();
                })
                .catch((error) => {
                    console.log(error);
                    if (retry == false) {
                        this.uploadSingleFile(filePostion, (retry = True));
                    } else {
                        // Show error notification to user
                        console.error(
                            "File upload failed. File name: ",
                            this.filesArray[filePostion].file.name
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
