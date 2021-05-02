<template>
    <h1>{{ photo.name }}</h1>
    <img
        :src="'/' + photo.name"
        :alt="photo.name"
        style="height: 300px; width: 300px"
    />
</template>

<script>
export default {
    props: {
        photo: Object,
        jetstream: Object,
        user: Object,
        errorBags: Array,
        errors: Object,
    },
    methods: {},
};
</script>
