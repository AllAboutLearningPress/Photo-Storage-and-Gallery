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
                v-on:click="openNotification(notification.url)"
                v-for="notification in notifications"
                :key="notification.id"
                style="cursor: pointer"
            >
                <th style="style: 5%" scope="row">
                    <img
                        :src="notification.thumbSrc"
                        alt=""
                        style="height: 50px"
                    />
                </th>
                <td style="width: 95%">Found a duplicate</td>
            </tr>
        </tbody>
    </table>
</template>


<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    layout: MainLayout,
    data() {
        return {
            notifications: [
                {
                    id: 1,
                    thumbSrc: "//placekitten.com/g/200/300",
                    text: "Found a duplicate",
                    url: "/",
                },
                {
                    id: 2,
                    thumbSrc: "//placekitten.com/g/200/300",
                    text: "Found a duplicate",
                },
                {
                    id: 3,
                    thumbSrc: "//placekitten.com/g/200/300",
                    text: "Found a duplicate",
                },
                {
                    id: 4,
                    thumbSrc: "//placekitten.com/g/200/300",
                    text: "Found a duplicate",
                },
            ],
        };
    },
    methods: {
        openNotification(url) {
            this.$inertia.visit(url);
        },
    },
};
</script>
