<template>
    <Header></Header>
    <slot></slot>
</template>

<script>
import Header from "../Components/Header.vue";
export default {
    components: {
        Header,
    },
    props: {
        photos: Array,
    },
    methods: {},
};
</script>
