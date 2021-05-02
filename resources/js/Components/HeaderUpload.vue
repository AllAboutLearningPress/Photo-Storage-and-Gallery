<template>
    <div>
        <input type="file" @change="previewFiles" multiple />
    </div>

    <button class="btn" v-on:click="uploadFiles">upload</button>
</template>

<script>
export default {
    data: function () {
        return {
            filesArray: Array,
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
            this.filesArray = Array.from(event.target.files);

            console.log(this.filesArray);
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
                this.uploadSingleFile(this.filesArray[this.fileCount]);
                this.uploadCount++;
                this.fileCount++;
            }
        },
        /**
         * Uploads a single file
         * @property {File} file - The file to upload
         * @property {boolean} retry - True if file upload is retried, Defaults to False
         */
        uploadSingleFile(file, retry = false) {
            console.log("started ", file);
            const formData = new FormData();
            formData.append("file", file);
            formData.append("name", file.name);

            axios
                .post("/store", formData)
                .then((response) => {
                    console.log(response);
                    this.uploadCount--;
                    // upload finished. Now it will check and
                    // start a new upload
                    this.uploadFiles();
                })
                .catch((error) => {
                    console.log(error);
                    if (retry == false) {
                        this.uploadSingleFile(file, (retry = True));
                    } else {
                        // Show error notification to user
                        console.error(
                            "File upload failed. File name: ",
                            file.name
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
