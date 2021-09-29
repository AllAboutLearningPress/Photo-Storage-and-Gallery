<template>
    <h2>Notifications</h2>

    <div class="row">
        <div class="right">
            <div class="box shadow-sm rounded mb-3">
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="box-body p-0 text-decoration-none text-white"
                    :src="notification.src"
                    v-on:click="
                        openNotification(notification.route, notification.id)
                    "
                >
                    <div class="p-1 d-flex justify-content-between noti-row">
                        <div
                            class="d-flex align-items-center osahan-post-header"
                        >
                            <div class="dropdown-list-image me-2">
                                <img
                                    class="rounded"
                                    :src="notification.src"
                                    alt=""
                                />
                            </div>
                            <div class="font-weight-bold me-3">
                                <div class="text-truncate">
                                    {{ notification.data.title }}
                                </div>
                                <div class="small">
                                    {{ notification.data.body }}
                                </div>
                            </div>
                        </div>
                        <span class="ml-auto mb-auto">
                            <!-- <delete-button class="btn btn-sm rounded"> -->
                            <!-- </delete-button> -->
                            <button
                                type="button"
                                class="btn btn-sm rounded p-0"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    class="bi bi-trash"
                                    viewBox="0 0 16 16"
                                    width="24"
                                    height="24"
                                >
                                    <path
                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                                    ></path>
                                    <path
                                        fill-rule="evenodd"
                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                                    ></path>
                                </svg>
                            </button>

                            <br />
                            <div class="text-right text-muted pt-1">3d</div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* body {
    margin-top: 20px;
    background-color: #f0f2f5;
} */
.box > div:first-child .noti-row {
    border: none !important;
}
.noti-row {
    border-top: 1px solid rgba(255, 255, 255, 0.3);
}
.box-body {
    cursor: pointer;
}
.dropdown-list-image {
    position: relative;
    height: 2.5rem;
    width: 2.5rem;
}
.dropdown-list-image img {
    height: 2.5rem;
    width: 2.5rem;
}
.btn-light {
    color: #2cdd9b;
    background-color: #e5f7f0;
    border-color: #d8f7eb;
}
</style>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import axios from "axios";
import { notify } from "@/util.js";
import DeleteButton from "../PhotoView/Componenets/DeleteButton.vue";
export default {
    components: { DeleteButton },
    layout: MainLayout,
    props: {
        notifications: Array,
        jetstream: Object,
        user: Object,
        errorBags: Array,
        errors: Object,
        flash: Object,
        notification_count: Number,
    },
    data() {
        return {
            // notifications: [
            //     {
            //         id: 1,
            //         thumbSrc: "//placekitten.com/g/200/300",
            //         text: "Found a duplicate",
            //         url: "/",
            //     },
            //     {
            //         id: 2,
            //         thumbSrc: "//placekitten.com/g/200/300",
            //         text: "Found a duplicate",
            //     },
            //     {
            //         id: 3,
            //         thumbSrc: "//placekitten.com/g/200/300",
            //         text: "Found a duplicate",
            //     },
            //     {
            //         id: 4,
            //         thumbSrc: "//placekitten.com/g/200/300",
            //         text: "Found a duplicate",
            //     },
            // ],
        };
    },
    created() {
        this.notifications.forEach((notification) => {
            notification.data = JSON.parse(notification.data);
        });
    },
    mounted() {
        console.log(this.notifications[0]);
    },
    methods: {
        openNotification(route_config, id) {
            let decoded_route = JSON.parse(route_config);
            console.log(decoded_route);

            this.$inertia.visit(
                route(decoded_route.name, decoded_route.options)
            );
            axios.post(route("notifications.seen"), { id: id }).catch((err) => {
                console.error(err);
                notify("Something went wrong in notifications.");
            });
        },
        genRowClassList(seen) {
            let classes = "notification-row";
            if (!seen) {
                classes += " notification-unread";
            }
            return classes;
        },
    },
};
</script>
