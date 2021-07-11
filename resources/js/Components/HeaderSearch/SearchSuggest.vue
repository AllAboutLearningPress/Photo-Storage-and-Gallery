<template>
    <div class="js-search__suggest search__suggest">
        <div class="search__suggest-content scrollbar">
            <div class="list-group list-group-flush">
                <a
                    v-for="(suggestion, index) in suggestions"
                    :key="suggestion.id"
                    :data-suggestion-id="index"
                    href="#"
                    v-on:click="
                        showPhoto($event, suggestion.id, suggestion.slug)
                    "
                    tabindex="-1"
                    class="
                        js-search__suggest-item
                        search__suggest-item
                        list-group-item list-group-item-action
                    "
                >
                    <img
                        class="search__suggest-item-thumb"
                        :src="suggestion.src"
                        alt=""
                        style="max-width: 4rem; max-height: 4rem"
                    />
                    <span class="search__suggest-item-thumb-txt">
                        {{ suggestion.title }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["suggestions"],
    methods: {
        showPhoto(e, id, slug) {
            console.log("open");
            e.preventDefault();
            this.$inertia.visit(
                route("photo.show", {
                    id: id,
                    slug: slug,
                })
            );
        },
    },
};
</script>
