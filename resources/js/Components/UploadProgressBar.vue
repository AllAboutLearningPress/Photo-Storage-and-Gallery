<template>
    <inertia-link
        v-if="total"
        :href="route('uploads.index')"
        class="js-upload-bar upload-bar"
    >
        Uploading {{ filesArray.length }} photos ({{ remaining }} remaining)
        <div class="upload-bar__progress progress">
            <div
                class="progress-bar"
                role="progressbar"
                id="master-progress-bar"
                style="background-color: --bs-green"
                :style="'width: ' + calculateProgressWidth"
                aria-valuemin="0"
                aria-valuemax="100"
            ></div>
        </div>
    </inertia-link>
</template>
<style>
.progress-bar {
    background-color: #198754;
}
</style>
<script>
import { inject } from "@vue/runtime-core";
//:aria-valuenow="progressValue"
export default {
    setup() {
        const filesArray = inject("filesArray");
        const total = inject("total");

        return {
            filesArray,
            total,
        };
    },
    data: () => {
        return {
            progressValue: 0,
            loaded: 0,
            fileCount: 0,
            remaining: "1h 23m",
        };
    },

    computed: {
        calculateProgressWidth() {
            let loaded = 0;
            for (let i = 0; i < this.filesArray.length; i++) {
                loaded += this.filesArray[i].loaded;
            }
            //

            return (loaded / this.total) * 100 + "%";
        },
    },
};
</script>
