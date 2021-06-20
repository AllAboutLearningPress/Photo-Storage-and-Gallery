<template>
    <button
        title="Show search"
        type="button"
        class="js-search__toggle search__field-show btn btn-subtle btn-lg"
        v-on:click="showSearch"
    >
        <span
            class="
                search__field-image-search-spinner
                spinner-border spinner-border-sm
                text-secondary
            "
            role="status"
        >
            <span class="visually-hidden">Loading...</span>
        </span>
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="22"
            height="22"
            fill="currentColor"
            class="bi bi-search"
            viewBox="0 0 16 16"
        >
            <path
                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"
            ></path>
        </svg>
        <span class="visually-hidden">Search</span>
    </button>
</template>

<script>
export default {
    methods: {
        /** Shows Search Button on click on small displays(smartphone) */
        showSearch(e) {
            document
                .querySelector(".js-search-header")
                .classList.toggle("is-searchable");
            // addEventListener(this.header, "click", (e) => {
            //     const togglerElem = e.target.closest(".js-search__toggle");
            //     if (togglerElem) {
            //         this.toggleSearchfield({
            //             togglerElem,
            //             resetFocus: !e.detail,
            //         });
            //     }
            // });
        },
    },
};
</script>
