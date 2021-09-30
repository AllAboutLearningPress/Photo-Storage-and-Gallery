<template>
    <button
        id="delete-modal-open-btn"
        title="Delete image"
        type="button"
        class="btn-subtle btn"
        v-on:click="openModal"
    >
        <trash-icon :height="24" :width="width"></trash-icon>
        <span class="visually-hidden">Delete</span>
    </button>
</template>

<script>
import TrashIcon from "../../../Icons/TrashIcon.vue";
export default {
    components: { TrashIcon },
    methods: {
        openModal(e) {
            console.log(e);
            this.$emit("open-modal");
        },
    },
};
</script>
