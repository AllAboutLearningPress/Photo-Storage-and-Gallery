<template>
    <main-layout>
        <inertia-link
            v-for="photo in photos"
            :key="photo.id"
            :href="route('photos.show', [photo.name])"
        >
            <img
                :src="photo.url"
                :alt="photo.name"
                style="height: 300px; width: 300px"
            />
        </inertia-link>
    </main-layout>
</template>

<script>
import MainLayout from "../Layouts/MainLayout.vue";

export default {
    components: {
        MainLayout,
    },
    props: {
        photos: Array,
    },
    data() {
        return {
            offset: 5,
        };
    },
    mounted() {
        this.scroll();
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
