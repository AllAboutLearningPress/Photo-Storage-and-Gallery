<template>
    <h1>Notifications</h1>
    <table class="table">
        <!-- <thead class="table-dark">
            <tr>
                <h1>Notifications</h1>
            </tr>
        </thead> -->
        <tbody>
            <tr
                v-on:click="openNotification(notification.route)"
                v-for="notification in notifications"
                :key="notification.id"
                class="notification-row"
            >
                <th scope="row">
                    <img :src="notification.src" alt="image" />
                </th>
                <td class="notification-text" style="width: 95%">
                    {{ notification.text }}
                </td>
            </tr>
        </tbody>
    </table>
</template>
<style>
.notification-row {
    cursor: pointer;
}
.notification-row th {
    display: flex;
    align-items: center;
    justify-content: center;
}
.notification-row img {
    max-width: 5.5rem;
    max-height: 3.5rem;
}
.notification-text {
    vertical-align: middle;
}
</style>

<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
    props: ["notifications"],
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
    mounted() {
        console.log(this.notifications[0]);
    },
    methods: {
        openNotification(route_config) {
            let decoded_route = JSON.parse(route_config);
            console.log(decoded_route);

            this.$inertia.visit(
                route(decoded_route.name, decoded_route.options)
            );
        },
    },
};
</script>
