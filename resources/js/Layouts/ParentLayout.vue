<template>
    <div>asfasdf</div>
    <slot></slot>
</template>
<style lang='scss'>
@import "../../sass/main.scss";
</style>
<script>
import { provide, ref } from "@vue/runtime-core";

export default {
    setup() {
        console.log("creating setup");
        // this ref will hold the total bytes of full upload
        let total = ref(0);
        const updateTotal = (newTotal) => {
            total.value = newTotal;
        };

        // this ref will store the file objects that is being uploaded
        const filesArray = ref([]);
        const pushToFilesArray = (files) => {
            filesArray.value.push(files);
        };

        // will be used to keep track on how many files are uploaded
        const uploadedCount = ref(0);
        const increaseUploadedCount = (value) => {
            uploadedCount.value += value;
        };

        // will be used to hide header in image view page
        const showHeader = ref(true);
        const toggleHeader = (value) => {
            showHeader.value = value;
        };
        const tags = ref([]);
        /*
        Fetches the tags from server in chunks.
        Server returns 100 tags at each request then
        It is all loaded to the datalist element.
        We need to replace the logic if the tags list is
        Very long. That would create memory issues.
        */
        const fetchTags = (url = route("tags.get_tags")) => {
            axios
                .get(url)
                .then((resp) => {
                    tags.value.push(...resp.data.data);

                    // checking if there is more tags to fetch
                    // if there are more tags then server will
                    // return the next url to fetch data
                    if (resp.data.next_page_url) {
                        fetchTags(resp.data.next_page_url);
                    }
                })
                .catch((err) => {
                    // notify("Error fetching tags", "danger");
                    console.error(err);
                });
        };

        const resetFuncs = ref([]);
        const resetUpload = () => {
            filesArray.value = [];
            uploadedCount.value = 0;
            total.value = 0;
            resetFuncs.value.forEach((func) => func());
        };

        const cancelToken = ref(axios.CancelToken.source());
        // const resetCancelToken = ()=> {
        //     cancelToken.value =
        // }

        provide("total", total);
        provide("updateTotal", updateTotal);
        provide("filesArray", filesArray);
        provide("pushToFilesArray", pushToFilesArray);
        provide("uploadedCount", uploadedCount);
        provide("increaseUploadedCount", increaseUploadedCount);
        provide("showHeader", showHeader);
        provide("toggleHeader", toggleHeader);
        provide("tags", tags);
        // console.log(tags);
        // console.log("providers");
        provide("fetchTags", fetchTags);
        provide("resetUpload", resetUpload);
        provide("resetFuncs", resetFuncs);
        provide("cancelToken", cancelToken);
        console.log("parentlayout provided\n\n\n");
        //s;
        // return {
        //     total,
        //     fetchTags,
        //     showHeader,
        // };
    },
    created() {
        //this.changeTitle();
        // this will change the title when a new page is visited
        // we need this because inertia does a partial request
        // so the title is not updated from server
        this.$inertia.on("navigate", this.changeTitle);
        console.log("creted mainlayout");
    },
};
</script>
