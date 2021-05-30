<template>
    <inertia-link v-if="total" href="#" class="js-upload-bar upload-bar">
        Uploading 9243 photos (1h 53m remaining)
        <div class="upload-bar__progress progress">
            <div
                class="progress-bar"
                role="progressbar"
                id="master-progress-bar"
                :style="'width: ' + progressValue"
                :aria-valuenow="progressValue"
                aria-valuemin="0"
                aria-valuemax="100"
            ></div>
        </div>
    </inertia-link>
</template>
<script>
export default {
    data: () => {
        return {
            progressValue: 0,
            uploadBar: null,
            start: null,
            total: 0,
            loaded: 0,
        };
    },
    created() {
        console.log("created progress bar");
        document.addEventListener(
            "update-progress-total",
            this.updateProgressTotal
        );
        document.addEventListener("update-progress-bar", this.updateUploadBar);
    },

    methods: {
        updateProgressTotal(e) {
            console.log(e);
            this.total = e.detail.total;
            console.log("total: ", this.total);
        },
        updateUploadBar(e) {
            window.requestAnimationFrame(() => {
                //this.uploadBar = document.querySelector("#master-progress-bar");
                //this.uploadBar.style.width =
                this.loaded = this.loaded + e.detail.loaded;
                this.progressValue = (this.loaded / this.total) * 100 + "%";
                console.log(this.progressValue);
            });
        },
    },
};
</script>
