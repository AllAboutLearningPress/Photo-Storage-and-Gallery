<template>
    <h1>photos</h1>
    <inertia-link href="/upload">upload files</inertia-link>
    <inertia-link
        v-for="photo in photos"
        :key="photo.id"
        :href="route('photos.show', [photo.id, photo.name])"
    >
        <img
            :src="photo.name"
            :alt="photo.name"
            style="height: 300px; width: 300px"
        />
    </inertia-link>
</template>

<script>
export default {
    props: {
        photos: Array,
    },
    methods: {},
};
</script>
