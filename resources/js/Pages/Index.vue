<template>
    <div>
        <!-- <sub-header></sub-header> -->
        <div id="pig" class="gallery mb-4"></div>
        <button type="button" class="btn btn-lg btn-primary">Load more</button>
    </div>
</template>


<script>
import MainLayout from "../Layouts/MainLayout.vue";
import SubHeader from "../Components/SubHeader.vue";
import GlobalDropTarget from "../frontend/components/GlobalDropTarget.js";

export default {
    components: {
        SubHeader,
    },
    layout: MainLayout,
    props: {
        photos: Object,
        photo: Object,
        jetstream: Object,
        user: Object,
        errorBags: Array,
        errors: Object,
        imageData: [],
    },

    mounted() {
        // const allowedMimeTypes = [
        //     "image/jpeg",
        //     "image/png",
        //     "image/gif",
        //     "image/tiff",
        //     "image/vnd.adobe.photoshop",
        // ];

        const options = {
            fetchMoreUrl: this.genFetMoreUrl(),
            urlForSize: function (filename, size) {
                return `/storage/full_size/${filename}`;
            },
            onClickHandler: this.photoOnClick,
            figureTagName: "a",
        };
        // crating the Pig instance
        this.pig = new Pig(this.photos.data, options);
        this.pig.enable();

        /** listening to inertia:navigate event to disable pig when
         * navigating away
         */
        this.navigationListener = this.$inertia.on("navigate", this.pigDisable);
        // WARNING: this is required to fix `pigjs` bug, use after each `Pig` initialisation
        window.dispatchEvent(new Event("resize"));
    },
    methods: {
        /**Executed when a photo in the gallery is clicked */
        photoOnClick(filename, id, slug) {
            this.$inertia.visit(
                route("photo.show", {
                    id: id,
                    slug: slug,
                })
            );
        },
        /** Disables Pig when navigating away from gallery */
        pigDisable() {
            if (route().current() === "home") {
                //navigated to home route. So we dont need to disable pig
                return;
            }
            this.pig.disable();
            console.log(this.navigationListener);
            this.navigationListener();
        },
        /** Generates url for fetching more photos when user scrolls */
        genFetMoreUrl() {
            if (!this.photos.next_page_url) {
                return this.photos.next_page_url;
            }
            return (
                route("index.fetch_more") +
                new URL(this.photos.next_page_url).search
            );
        },
    },
};
</script>
