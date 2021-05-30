<template>
    <inertia-link v-if="total" href="#" class="js-upload-bar upload-bar">
        Uploading 9243 photos (1h 53m remaining)
        <div class="upload-bar__progress progress">
            <div
                class="progress-bar"
                role="progressbar"
                id="master-progress-bar"
                style="width: {progressValue}%"
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
            checke: 0,
        };
    },
    created() {
        //console.log("created");
    },
    mounted: function () {
        console.log("mounted progress bar");
        document.addEventListener(
            "update-progress-total",
            this.updateProgressTotal
        );
        document.addEventListener("update-progress-bar", this.updateUploadBar);
        document.dispatchEvent(new CustomEvent("upload-progressbar-mounted"));
        // window.requestAnimationFrame(this.updateUploadBar);
        //this.check();
    },
    methods: {
        check() {
            console.log("check: ", this.checke);
            this.checke++;
            setTimeout(this.check, 50);
        },
        updateProgressTotal(e) {
            console.log(e);
            this.total = e.detail.total;

            // if uploadBar is not saved then select it
            if (!this.uploadBar) {
                this.uploadBar = document.querySelector("#master-progress-bar");
            }
            console.log(this.uploadBar);
        },
        updateUploadBar(e) {
            window.requestAnimationFrame(() => {
                console.log(this);
                //this.uploadBar = document.querySelector("#master-progress-bar");
                //this.uploadBar.style.width =
                this.progressValue = (e.detail.loaded / this.total) * 100 + "%";
            });
        },
    },
};
</script>
