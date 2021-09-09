<template>
    <datalist v-if="tags" id="tag-list">
        <option
            v-for="tag in tags"
            :key="tag.id"
            :value="tag.name"
            :data-id="tag.id"
        ></option>
    </datalist>
</template>

<script>
import { inject } from "@vue/runtime-core";
export default {
    // setup() {
    //     const tags = inject("tags");
    //     return { tags };
    // },
    data() {
        return {
            tags: null,
        };
    },
    beforeMount() {
        if (this.$page.props.user) {
            this.tags = inject("tags");
        }
    },
    // created() {
    //     //
    // },
};
</script>
