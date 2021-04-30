<template>
    <inertia-link href="/">home</inertia-link>
    <div>
        <input type="file" @change="previewFiles" multiple />
    </div>

    <button class="btn" v-on:click="storeFiles">upload</button>
</template>

<script>
export default {
    props: {
        photos: Array,
        jetstream: Object,
        user: Object,
        errorBags: Array,
        errors: Object,
    },
    data: function () {
        return { filesArray: Array };
    },
    methods: {
        previewFiles(event) {
            console.log(event);
            this.filesArray = Array.from(event.target.files);

            console.log(this.filesArray);
        },
        uploadFiles() {
            if (this.v_errors.length == 0) {
                const formData = new FormData();
                // adding the file
                // this.filesArray.forEach((file) => {
                //     formData.append("file", file);
                // });
                formData.append("file", this.filesArray[0]);
                formData.append("name", this.filesArray[0].file.name);

                axios
                    .post("/store", formData)
                    .then(function (response) {
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        },
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
