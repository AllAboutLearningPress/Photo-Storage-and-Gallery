<template>
    <inertia-link href="/">home</inertia-link>
    <inertia-link href="/upload">upload files</inertia-link>
    <header-search></header-search>
    <header-upload></header-upload>
</template>

<script>
import HeaderSearch from "./HeaderSearch.vue";
import HeaderUpload from "./HeaderUpload.vue";

export default {
    components: { HeaderUpload, HeaderSearch },
};
</script>
