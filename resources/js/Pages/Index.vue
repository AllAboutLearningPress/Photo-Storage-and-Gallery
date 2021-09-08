<template>
    <div>
        <!-- <sub-header></sub-header> -->
        <photo-view
            v-if="photo"
            v-on:close="closePhotoView"
            :photo="photo"
            :info="true"
        ></photo-view>
        <div id="pig" class="gallery mb-4"></div>
        <button
            type="button"
            class="btn btn-lg btn-primary"
            v-if="this.photos && this.photos.next_page_url"
        >
            Load more
        </button>
    </div>
</template>


<script>
import MainLayout from "../Layouts/MainLayout.vue";
import SubHeader from "../Components/SubHeader.vue";
import GlobalDropTarget from "../frontend/components/GlobalDropTarget.js";
import PhotoView from "./PhotoView/PhotoView.vue";
import { Inertia } from "@inertiajs/inertia";

export default {
    components: {
        SubHeader,
        PhotoView,
    },
    layout: MainLayout,
    props: {
        photos: Object,
        jetstream: Object,
        user: Object,
        errorBags: Array,
        errors: Object,
        imageData: [],
    },
    data() {
        return {
            showPhoto: false,
            photo: null,
        };
    },

    mounted() {
        // const allowedMimeTypes = [
        //     "image/jpeg",
        //     "image/png",
        //     "image/gif",
        //     "image/tiff",
        //     "image/vnd.adobe.photoshop",
        // ];
        if (this.photos) {
            this.initPig();
        } else {
            this.$inertia.reload({ only: ["photos"] }).then((resp) => {
                this.initPig();
            });
        }
    },
    methods: {
        initPig() {
            const options = {
                fetchMoreUrl: this.genFetMoreUrl(),
                urlForSize: function (filename, size) {
                    return `/storage/full_size/${filename}`;
                },
                onClickHandler: this.photoOnClick,
                figureTagName: "a",
            };
            // crating the Pig instance
            console.log("creating pig");
            this.pig = new Pig(this.photos.data, options);
            this.pig.clear();
            // console.log("creating pig ", this.pig);
            this.pig.enable();

            /** listening to inertia:navigate event to disable pig when
             * navigating away
             */
            //this.navigationListener = this.$inertia.on("navigate", this.pigDisable);
            // WARNING: this is required to fix `pigjs` bug, use after each `Pig` initialisation
            window.dispatchEvent(new Event("resize"));
        },
        closePhotoView() {
            this.pig.removePhoto(this.photo.id);
            this.photo = null;
        },
        /**Executed when a photo in the gallery is clicked */
        photoOnClick(filename, id, slug) {
            console.log(id);
            this.photo = { id };
            // Current URL: https://my-website.com/page_a
            console.log(slug);
            const nextURL = route("photo.show", { id: id, slug: slug });
            const nextTitle = "My new page title";
            const nextState = {
                additionalInformation: "Updated the URL with JS",
            };

            // This will create a new entry in the browser's history, without reloading
            window.history.pushState(nextState, nextTitle, nextURL);
            axios
                .post(route("photo.get_info"), { id: this.photo.id })
                .then((resp) => {
                    console.log(resp);
                    this.photo = resp.data;
                });

            // this.$inertia.visit(
            //     route("photo.show", {
            //         id: id,
            //         slug: slug,
            //     })
            // );
        },
        /** Disables Pig when navigating away from gallery */
        pigDisable() {
            if (route().current() === "home") {
                //navigated to home route. So we dont need to disable pig
                return;
            }
            console.log(this.pig);
            this.pig.disable();
            this.pig = null;
            console.log("removing pig", this.navigationListener);
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
    unmounted() {
        this.$nextTick(() => {
            this.pig.disable();
            this.pig.clear();
            this.pig = null;
            console.log("disabled pig", this.pig);
        });
    },
};
</script>
