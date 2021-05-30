<template>
    <aside
        id="content-sidebar"
        class="js-content-sidebar sidebar"
        tabindex="-1"
        aria-labelledby="sidebar"
    >
        <div class="sidebar__head">
            <button
                title="Close menu"
                type="button"
                class="js-sidebar-toggle sidebar__close btn-subtle btn-lg btn-close"
                data-sidebar="#content-sidebar"
                aria-label="Close"
            >
                <span class="visually-hidden">Close menu</span>
            </button>
        </div>
        <div class="sidebar__body scrollbar">
            <div class="navigation list-group list-group-flush">
                <inertia-link
                    v-for="item in menuItems"
                    :key="item.route"
                    :href="route(item.route)"
                    :class="
                        (currentRoute === item.route ? 'active ' : '') + classes
                    "
                >
                    {{ item.name }}
                </inertia-link>
                <form @onsubmit.prevent="logout">
                    <button
                        type="submit"
                        class="navigation__item list-group-item list-group-item-action"
                        v-on:click="logout"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <div class="sidebar-backdrop"></div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";
import Sidebar from "../frontend/components/Sidebar.js";
export default {
    data() {
        return {
            classes: "navigation__item list-group-item",
            menuItems: [
                {
                    name: "Photos",
                    route: "home",
                },
                {
                    name: "Popular Tags",
                    route: "tags.index",
                },
                {
                    name: "Trash",
                    route: "tags.index",
                },
            ],
            currentRoute: route().current(),
        };
    },
    mounted: () => {
        document.addEventListener("click", (e) => {
            const toggler = e.target.closest(".js-sidebar-toggle");

            toggler &&
                toggler.dispatchEvent(
                    new CustomEvent("sidebar-toggle-click", {
                        bubbles: true,
                    })
                );
        });
        const contentSidebar = new Sidebar(
            document.querySelector(".js-content-sidebar")
        );
        //console.log(contentSidebar);
    },
    created() {
        console.log("current route: ", this.currentRoute);
        Inertia.on("navigate", (event) => {
            this.currentRoute = route().current();
            console.log("current route: ", this.currentRoute);
        });
    },
    methods: {
        logout() {
            console.log("logout");
            this.$inertia.post(route("logout"));
        },
    },
};
</script>
