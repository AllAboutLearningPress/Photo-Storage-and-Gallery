<template>
    <a href="#" class="js-upload-bar upload-bar">
        Uploading 9243 photos (1h 53m remaining)
        <div class="upload-bar__progress progress">
            <div
                class="progress-bar"
                role="progressbar"
                style="width: {progressValue}%"
                :aria-valuenow="progressValue"
                aria-valuemin="0"
                aria-valuemax="100"
            ></div>
        </div>
    </a>
</template>
<script>
export default {
    data: () => {
        return {
            progressValue: 0,
            uploadBar: null,
            start: null,
        };
    },
    mounted: function () {
        this.uploadBar = document.querySelector(
            ".upload-bar__progress .progress-bar"
        );
        // window.requestAnimationFrame(this.updateUploadBar);
    },
    methods: {
        updateUploadBar(timestamp) {
            if (this.start === null) this.start = timestamp;
            const elapsed = timestamp - this.start;

            this.uploadBar.style.width = Math.min(0.05 * elapsed, 100) + "%";

            if (elapsed < 2000) {
                // Stop the animation after 2 seconds
                window.requestAnimationFrame(this.updateUploadBar);
            } else {
                // show upload complete notification
                // hide the upload bar
                console.log("finished uploading");
            }
        },
    },
};
</script>
