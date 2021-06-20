<template>
    <header class="js-search-header header">
        <div class="header__slot_logo header__slot">
            <button
                title="Open menu"
                type="button"
                class="
                    js-sidebar-toggle
                    header__open-sidebar
                    btn-subtle btn-menu btn-lg btn
                "
                data-sidebar="#content-sidebar"
                aria-controls="sidebar"
            >
                <span class="visually-hidden">Open menu</span>
            </button>
            <inertia-link class="logo btn-lg btn" :href="route('home')"
                >Photos</inertia-link
            >
        </div>
        <header-search></header-search>
        <header-upload></header-upload>
    </header>
</template>

<script>
import HeaderSearch from "./HeaderSearch/HeaderSearch.vue";
import HeaderUpload from "./HeaderUpload.vue";

export default {
    components: { HeaderUpload, HeaderSearch },
    created() {
        //console.log("created header");
    },
};
</script>
