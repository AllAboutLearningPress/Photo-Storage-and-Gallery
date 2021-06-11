<template>
    <div>
        <sub-header></sub-header>
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
        photos: Array,
        photo: Object,
        jetstream: Object,
        user: Object,
        errorBags: Array,
        errors: Object,
        imageData: [],
    },
    data() {
        return {
            offset: 5,
        };
    },
    mounted() {
        const allowedMimeTypes = [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/tiff",
            "image/vnd.adobe.photoshop",
        ];

        const globalDropTarget = new GlobalDropTarget(allowedMimeTypes);
        // this.scroll();
        // const imageData = [
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "100", aspectRatio: 1.777 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        //     { filename: "150", aspectRatio: 1.777 },
        //     { filename: "500", aspectRatio: 1 },
        //     { filename: "400", aspectRatio: 2.4 },
        //     { filename: "200", aspectRatio: 1.5 },
        //     { filename: "400", aspectRatio: 1.777 },
        // ];

        const options = {
            urlForSize: function (filename, size) {
                return `/storage/full_size/${filename}`;
            },
            onClickHandler: (filename) => {
                //alert(`select ${filename}`);
                this.$inertia.visit(route("photo.index"));
            },
            figureTagName: "a",
        };
        let imageData = this.photos.map((photo) => {
            photo.aspectRatio = photo.width / photo.height;
            photo.filename = photo.file_name;
            return photo;
        });
        const pig = new Pig(imageData, options).enable();
        // WARNING: this is required to fix `pigjs` bug, use after each `Pig` initialisation
        window.dispatchEvent(new Event("resize"));
    },
    methods: {
        scroll() {
            window.onscroll = () => {
                let bottomOfWindow =
                    Math.max(
                        window.pageYOffset,
                        document.documentElement.scrollTop,
                        document.body.scrollTop
                    ) +
                        window.innerHeight ===
                    document.documentElement.offsetHeight;

                if (bottomOfWindow) {
                    console.log("scrolled to bottom. should load more");
                    this.fetchMorePhotos();
                }
            };
        },

        fetchMorePhotos() {
            const formData = new FormData();
            formData.append("offset", this.offset);
            axios
                .post(route("index.load_more"), formData)
                .then((resp) => {
                    console.log(resp);
                    if (resp.data.length == 0) {
                        console.log("No more photos");
                        return;
                    }
                    this.photos.push(...resp.data);
                    this.offset += 5;
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
};
</script>
<!-- <inertia-link
            v-for="photo in photos"
            :key="photo.id"
            :href="route('photos.show', [photo.name])"
        >
            <img
                :src="photo.url"
                :alt="photo.name"
                style="height: 300px; width: 300px"
            />
        </inertia-link> -->
