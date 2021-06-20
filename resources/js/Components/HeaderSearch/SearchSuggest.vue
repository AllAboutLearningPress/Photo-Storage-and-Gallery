<template>
    <div class="js-search__suggest search__suggest">
        <div class="search__suggest-content scrollbar">
            <div class="list-group list-group-flush">
                <a
                    v-for="(suggestion, index) in suggestions"
                    :key="suggestion.id"
                    :data-suggestion-id="index"
                    href="#"
                    tabindex="-1"
                    class="
                        js-search__suggest-item
                        search__suggest-item
                        list-group-item list-group-item-action
                    "
                >
                    <img
                        class="search__suggest-item-thumb"
                        :src="suggestion.url"
                        alt=""
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
};
</script>
