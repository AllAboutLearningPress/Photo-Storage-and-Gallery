<template>
    <div class="header__slot_search header__slot">
        <div ref="js-search" action="#" class="js-search search">
            <div class="search__image-progress progress">
                <div
                    class="js-search__image-progress-bar progress-bar"
                    role="progressbar"
                    style="width: 0"
                    aria-valuenow="0"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>
            </div>
            <div class="js-search__field search__field">
                <button
                    title="Hide search"
                    type="button"
                    class="
                        js-search__toggle
                        search__infield-action search__infield-action_hide
                        btn btn-subtle btn-lg
                    "
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="26"
                        height="26"
                        fill="currentColor"
                        class="bi bi-arrow-left-short"
                        viewBox="0 0 16 16"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"
                        ></path>
                    </svg>
                    <span class="visually-hidden">Go back</span>
                </button>
                <input
                    class="
                        js-search__input
                        search__input
                        form-control form-control-lg
                    "
                    type="text"
                    placeholder="Search..."
                    autocomplete="off"
                    inputmode="search"
                    v-on:input="initSearch"
                    v-on:click="openSuggestions"
                />
                <label
                    tabindex="-1"
                    title="Search by image"
                    class="
                        search__infield-action
                        search__infield-action_image-search
                        button-file
                        btn btn-subtle btn-lg
                    "
                >
                    <camera-button></camera-button>
                    <span class="visually-hidden">Search by image</span>
                    <input
                        class="js-search__image-input button-file__input"
                        type="file"
                        accept="image/jpeg, image/png, image/gif, image/tiff, image/vnd.adobe.photoshop, .jpg, .jpeg, .png, .gif, .tif, .tiff, .psd"
                    />
                </label>
                <search-suggest :suggestions="suggestions"></search-suggest>
            </div>
            <show-search-button></show-search-button>
        </div>
    </div>
</template>

<script>
//import Search from "../../frontend/components/Search.js";
import SingleImageDropManager from "../../frontend/components/SingleImageDropManager.js";
import SearchSuggest from "./SearchSuggest.vue";
import CameraButton from "@/Buttons/CameraButton.vue";
import ShowSearchButton from "./ShowSearchButton.vue";
export default {
    components: { SearchSuggest, CameraButton, ShowSearchButton },
    data() {
        return {
            dropManager: null,
            val: "sdfsd",
            suggestions: [],
        };
    },
    created() {
        this.dropManager = new SingleImageDropManager();
    },
    mounted() {
        // this will be fully moved to vue
        //const search = new Search(document.querySelector(".js-search"));

        // listen to items-dropped event
        document.addEventListener("items-dropped", this.handleFileDrop);
        let field = document.querySelector(".js-search__field");
        field.addEventListener("focusout", () => {
            if (this.$refs["js-search"].classList.contains("is-suggesting")) {
                this.$refs["js-search"].classList.toggle("is-suggesting");
            }
        });
        // addEventListener(this.input, "keydown", (e) => {
        //     if (e.key === "ArrowUp" || e.key === "ArrowDown") {
        //         e.preventDefault();

        //         const suggesterItems = [].slice.call(
        //             this.suggester.querySelectorAll(".js-search__suggest-item")
        //         );
        //         let highlightedIndex = suggesterItems.findIndex((el) =>
        //             el.classList.contains(highlightedSuggestionKlass)
        //         );
        //         const increment = e.key === "ArrowDown" ? 1 : -1;
        //         const nextIndex = highlightedIndex + increment;
        //         const next =
        //             suggesterItems[
        //                 nextIndex < -1 ? suggesterItems.length - 1 : nextIndex
        //             ];

        //         this.unhighlightSuggestion();
        //         next && this.highlightSuggestion(next);
        //     }

        //     if (e.key === "Enter") {
        //         const highlightedItem = this.suggester.querySelector(
        //             `.${highlightedSuggestionKlass}`
        //         );

        //         if (highlightedItem) {
        //             this.selectSuggestion(highlightedItem.dataset.suggestionId);
        //         } else {
        //             this.search.submit();
        //         }
        //     }
        // });
    },
    methods: {
        handleFileDrop(e) {
            console.log(e);
            const isSearchDrop =
                !this.dropManager.isInited ||
                this.dropManager.getLatestDropStats().search;

            if (isSearchDrop) {
                //that.handleImageSearch(e.detail.fileArray[0]);
                alert("got files for search");
            }
        },
        openSuggestions(e) {
            if (this.suggestions.length) {
                this.$refs["js-search"].classList.add("is-suggesting");
            }
        },
        initSearch(e) {
            // showing the suggestion list
            //this.$refs["js-search"].classList.add("is-suggesting");

            // adding suggestion to the list
            this.suggestions.push(
                {
                    id: 1,
                    title: "cat picture demo",
                    url: "//placekitten.com/47/47",
                },
                {
                    id: 2,
                    title: "cat picture demo",
                    url: "//placekitten.com/47/47",
                },
                {
                    id: 3,
                    title: "cat picture demo",
                    url: "//placekitten.com/47/47",
                },
                {
                    id: 4,
                    title: "cat picture demo",
                    url: "//placekitten.com/47/47",
                }
            );
            this.openSuggestions();
            console.log(this.suggestions);
        },
    },
};
</script>
