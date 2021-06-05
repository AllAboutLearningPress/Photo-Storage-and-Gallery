<template>
    <inertia-link
        v-if="total"
        :href="route('uploads.index')"
        class="js-upload-bar upload-bar"
    >
        Uploading {{ fileCount }} photos ({{ remaining }} remaining)
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
import { inject } from "@vue/runtime-core";
export default {
    setup() {
        const total = inject("total");
        return { total };
    },
    data: () => {
        return {
            progressValue: 0,
            loaded: 0,
            fileCount: 0,
            remaining: "1h 23m",
        };
    },
    created() {
        // This event is dispatched periodically by HeaderUpload
        // bytes are sent for individual file
        document.addEventListener("update-progress-bar", this.updateUploadBar);
    },

    methods: {
        /** Updates the filecount and total bytes to be sent */
        updateProgressTotal(e) {
            console.log(e);
            this.total = e.detail.total;
            this.fileCount = e.detail.fileCount;
        },

        /** Updates main progress bar */
        updateUploadBar(e) {
            window.requestAnimationFrame(() => {
                this.loaded = this.loaded + e.detail.bytesSent;
                this.progressValue = (this.loaded / this.total) * 100 + "%";
            });
        },
    },
};
</script>
