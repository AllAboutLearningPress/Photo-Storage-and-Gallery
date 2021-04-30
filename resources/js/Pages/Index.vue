<template>
    <h1>photos</h1>
    <inertia-link href="/upload">upload files</inertia-link>
</template>

<script>
export default {
    props: {
        photos: Array,
    },
};
</script>
