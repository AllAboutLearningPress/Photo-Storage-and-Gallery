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
                class="
                    js-sidebar-toggle
                    sidebar__close
                    btn-subtle btn-lg btn-close
                "
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
                    style="font-size: 1.1rem"
                >
                    <!-- <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="1.4rem"
                        height="1.4rem"
                        fill="currentColor"
                        class="bi bi-card-image"
                        viewBox="0 0 16 16"
                        style="vertical-align: text-bottom; margin-right: 1rem"
                    >
                        <path
                            d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"
                        />
                        <path
                            d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54A.505.505 0 0 1 1 12.5v-9a.5.5 0 0 1 .5-.5h13z"
                        />
                    </svg> -->

                    {{ item.name }}
                </inertia-link>
                <form
                    action="logout"
                    method="POST"
                    v-on:submit.prevent="logout"
                >
                    <button
                        type="submit"
                        class="
                            navigation__item
                            list-group-item list-group-item-action
                        "
                        style="font-size: 1.1rem"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>
    <div class="sidebar-backdrop"></div>
</template>

<style lang="sass">
.nav-btn-icon
    vertical-align: text-bottom
</style>
<script>
import SidebarHelper from "../frontend/components/Sidebar";
import { usePage } from "@inertiajs/inertia-vue3";
export default {
    setup(props) {
        // console.log(usePage().props.value.notification_count);
        let notificationsName = "Notifications";
        if (usePage().props.value.notification_count) {
            notificationsName += ` (${
                usePage().props.value.notification_count
            })`;
        }
        const menuItems = [
            {
                name: "Photos",
                route: "home",
            },
            {
                name: notificationsName,
                route: "notifications.index",
            },
            {
                name: "Popular Tags",
                route: "tags.index",
            },
            {
                name: "Trash",
                route: "trash",
            },
            {
                name: "Invitations",
                route: "invitations.index",
            },
        ];
        return { menuItems };
    },
    data() {
        return {
            classes: "navigation__item list-group-item",

            currentRoute: route().current(),
        };
    },
    mounted() {
        // console.log("noti count ", this.$page.props.notification_count);
        document.addEventListener("click", (e) => {
            const toggler = e.target.closest(".js-sidebar-toggle");

            toggler &&
                toggler.dispatchEvent(
                    new CustomEvent("sidebar-toggle-click", {
                        bubbles: true,
                    })
                );
        });
        // const contentSidebar = new Sidebar(
        //     document.querySelector(".js-content-sidebar")
        // );
        const contentSidebar = new SidebarHelper(
            document.querySelector(".js-content-sidebar")
        );
    },
    created() {
        // when inertia visits a new route it dispatches
        // navigate event. So we are listening for it
        // in order to change the active menu item
        this.$inertia.on("navigate", (event) => {
            this.currentRoute = route().current();
            this.menuItems[1].name = `Notifications (${this.$page.props.notification_count})`;
        });
    },
    methods: {
        /**Logout function */
        logout() {
            // sending the post request for logging out this session
            axios.post(route("logout")).then((resp) => {
                // visiting the new url
                location.replace(route("login"));
            });
        },
    },
};
</script>
