<template>
    <div class="js-tags tags">
        <div
            v-if="$page.props.user"
            class="js-tags__form tags__form"
            action="#"
        >
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

        <div class="js-tags__list tags__list">
            <inertia-link
                class="tags__tag tag tag_deletable btn btn-secondary"
                :href="route('tags.show', { slug: tag.slug })"
                v-for="(tag, index) in tags"
                :key="tag.id"
            >
                {{ tag.name }}

                <button
                    v-if="$page.props.user"
                    title="Delete tag"
                    type="button"
                    class="js-tag-delete tag__delete btn-close"
                    aria-label="Delete tag"
                    v-on:click="removeTag(tag, index)"
                >
                    <span class="visually-hidden">Delete tag</span>
                </button>
            </inertia-link>
            <!-- <a
                class="tags__tag tag tag_deletable btn btn-secondary"
                href="#"
            >
                Some tag ometagomet agomet agometa etag
                <object type="no/suchtype">
                    <button
                        title="Delete tag"
                        type="button"
                        class="js-tag-delete tag__delete btn-close"
                        aria-label="Delete tag"
                    >
                        <span class="visually-hidden"
                            >Delete tag</span
                        >
                    </button>
                </object>
            </a> -->
        </div>
    </div>
</template>
<script>
import { notify } from "@/util.js";
export default {
    props: ["tags", "id"],
    methods: {
        addTag() {
            let value = this.$refs["tag-input"].value;
            // only add the tag if its not added before
            if (this.uniqueTag(value)) {
                let tagOption = document
                    .querySelector("datalist")
                    .querySelector(`[value='${value}']`);

                let tagId = null;
                if (tagOption) {
                    // unique tag typed by user
                    tagId = tagOption.getAttribute("data-id");
                }

                axios
                    .post(route("uploads.add_tag"), {
                        fileId: this.id,
                        tagId: tagId,
                        tagName: value,
                    })
                    .then((resp) => {
                        console.log(resp);
                        if (!tagId) {
                            tagId = resp.data;
                        }
                        this.$emit("add-tag", {
                            name: value,
                            id: tagId,
                        });
                        notify("Tag Added", "success");
                    });
            }
        },

        removeTag(tag, index) {
            this.$emit("remove-tag", index);
            axios
                .post(route("uploads.remove_tag"), {
                    fileId: this.id,
                    tagId: tag.id,
                })
                .then((resp) => {
                    notify("Tag removed", "success");
                })
                .catch((err) => {
                    notify("Something went wrong. Please try again", "danger");
                    // tag remove failed
                    // so adding the tag again to the taglist
                    this.$emit("add-tag", tag);
                });
        },
        uniqueTag(newTag) {
            this.tags.forEach((tag) => {
                if (tag.name == newTag) {
                    return false;
                }
            });
            return true;
        },
    },
};
</script>
