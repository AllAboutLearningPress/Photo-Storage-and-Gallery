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
                <span
                    class="active navigation__item list-group-item"
                    aria-current="true"
                >
                    My photos
                </span>
                <a
                    href="#"
                    class="navigation__item list-group-item list-group-item-action"
                >
                    Popular tags
                </a>
                <a
                    href="#"
                    class="navigation__item list-group-item list-group-item-action"
                >
                    Trash
                </a>
                <a
                    href="/accounts/logout"
                    class="navigation__item list-group-item list-group-item-action"
                >
                    Logout
                </a>
            </div>
        </div>
    </aside>
    <div class="sidebar-backdrop"></div>
</template>

<script>
import Sidebar from "../frontend/components/Sidebar.js";
export default {
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
        console.log(contentSidebar);
    },
};
</script>
