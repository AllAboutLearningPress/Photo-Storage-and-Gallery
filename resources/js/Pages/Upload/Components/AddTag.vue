<template>
    <div class="js-tags__form tags__form" action="#">
        <div class="input-group mt-1 mb-3">
            <input
                name="tag-input"
                list="tag-list"
                class="js-tags__input form-control form-control-lg"
                type="text"
                placeholder="Specify tag"
                autocomplete="off"
                ref="tag-input"
            />
            <button
                title="Add tag"
                class="btn btn-lg btn-outline-secondary"
                type="submit"
                v-on:click="addTag"
            >
                Add
            </button>
        </div>
    </div>
</template>
<script>
export default {
    props() {
        return {
            fileId: BigInt,
        };
    },
    methods: {
        addTag(event) {
            this.$emit("add-tag", {
                tag: this.$refs["tag-input"].value,
                fileId: this.fileId,
            });
        },
    },
};
</script>
