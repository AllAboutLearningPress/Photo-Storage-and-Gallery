<template>
    <main-layout>
        <inertia-link
            v-for="photo in photos"
            :key="photo.id"
            :href="route('photos.show', [photo.name])"
        >
            <img
                :src="photo.name"
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
    methods: {},
};
</script>
