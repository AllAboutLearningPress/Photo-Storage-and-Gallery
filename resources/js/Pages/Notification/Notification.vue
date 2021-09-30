<template>
    <div class="d-flex flex-row justify-content-between mb-3">
        <h2 class="fw-light mb-0">Notifications</h2>
        <div>
            <button
                type="button"
                class="btn btn-outline-danger invite-button me-2"
                v-on:click="deleteSelected"
                v-if="select"
                :disabled="!selectedIds.length"
            >
                Delete
            </button>
            <button
                type="button"
                class="btn btn-outline-success invite-button"
                v-on:click="toggleSelect"
            >
                {{ select ? "Cancel" : "Select" }}
            </button>
        </div>
    </div>

    <div class="row">
        <div class="right">
            <div class="box shadow-sm rounded mb-3">
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="box-body p-0 text-decoration-none text-white"
                    :class="notification.seen ? '' : 'unread'"
                    :src="notification.src"
                    v-on:click="
                        notiOnClick($event, notification.route, notification.id)
                    "
                >
                    <div
                        v-if="!notification.hidden"
                        class="p-1 d-flex justify-content-between noti-row"
                    >
                        <div class="d-flex align-items-center">
                            <div class="me-2" :class="checkboxDisplayClass">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    :id="
                                        checkboxInputIdPrefix + notification.id
                                    "
                                />
                            </div>
                            <div class="dropdown-list-image me-3">
                                <img
                                    class="rounded"
                                    :src="notification.src"
                                    alt=""
                                />
                            </div>
                            <div class="font-weight-bold me-3">
                                <div class="small">
                                    <b> {{ notification.data.title }}: </b>
                                    {{ notification.data.body }}
                                </div>
                                <!-- <div class="small">
                                    {{ notification.data.body }}
                                </div> -->
                                <div class="small opacity-25">
                                    {{ convertTime(notification.created_at) }}
                                </div>
                            </div>
                        </div>
                        <span class="ml-auto mb-auto me-sm-3">
                            <button
                                type="button"
                                class="btn btn-sm rounded p-0 noti-delete"
                            >
                                <trash-icon height="24" width="24"></trash-icon>
                            </button>

                            <br />
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import "../../../sass/customised-bootstrap.scss";

/* body {
    margin-top: 20px;
    background-color: #f0f2f5;
} */
.box > div:first-child .noti-row {
    border: none !important;
}

.noti-row {
    @media (max-width: 575.98px) {
        border-top: 1px solid rgba(255, 255, 255, 0.3);
    }
}

.box-body {
    cursor: pointer;
}
.box-body:hover {
    background-color: rgba(65, 65, 65, 0.3);
}
@include media-breakpoint-up(md) {
    @media (hover: hover) {
        .noti-delete {
            display: none;
        }

        .box-body:hover .noti-delete {
            display: block !important;
        }
    }
}

.box-body.unread {
    background-color: rgba(255, 255, 255, 0.3);
}
.box-body.unread:hover {
    background-color: rgba(255, 255, 255, 0.35);
}
.dropdown-list-image {
    position: relative;
    height: 3.5rem;
    width: 3.5rem;
}

.dropdown-list-image img {
    height: 3.5rem;
    width: 3.5rem;
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
import { notify } from "../../util.js";
import DeleteButton from "../PhotoView/Componenets/DeleteButton.vue";
import TrashIcon from "../../Icons/TrashIcon.vue";
export default {
    components: {
        DeleteButton,
        TrashIcon,
    },
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
            select: false,
            checkboxInputIdPrefix: "input-check-",
            checkboxDisplayClass: "d-none",
            selectedIds: [],
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
        toggleSelect(e) {
            this.select = !this.select;
            this.checkboxDisplayClass = this.select ? "d-block" : "d-none";
            console.log(this.checkboxClass);
        },

        deleteSelected() {
            this.notifications.forEach((noti) => {
                if (this.selectedIds.contains(noti.id)) {
                    noti.hidden = true;
                }
            });
            axios
                .post(route("notifications.destroy"), { ids: this.selectedIds })
                .then((resp) => {
                    notify("Notifications deleted", "success");
                })
                .catch((err) => {
                    console.error(err);
                    notify("Something went wrong", "danger");
                });
        },

        /** Converts mysql time to human readbale time format
         * like 4 days ago, 5 years ago, etc
         */
        convertTime(timestamnp) {
            let time =
                (new Date().getTime() - new Date(timestamnp).getTime()) / 1000;
            //(Date.now() - new Date(timestamnp)) / 1000; // unix timestamp in second
            time = time < 1 ? 1 : time;
            let tokens = [
                {
                    sec: 31536000,
                    unit: "year",
                },
                {
                    sec: 2592000,
                    unit: "month",
                },
                {
                    sec: 604800,
                    unit: "week",
                },
                {
                    sec: 86400,
                    unit: "day",
                },
                {
                    sec: 3600,
                    unit: "hour",
                },
                {
                    sec: 60,
                    unit: "minute",
                },
                {
                    sec: 1,
                    unit: "second",
                },
            ];
            // tokens.reverse();

            for (let i = 0; i < tokens.length; i++) {
                let token = tokens[i];
                //console.log("token: ", token);
                //console.log("time: ", time);
                if (time < token.sec) continue;
                let numberOfKeys = Math.floor(time / token.sec);

                return (
                    numberOfKeys +
                    " " +
                    token.unit +
                    (numberOfKeys > 1 ? "s ago" : "")
                );
            }
        },
        notiOnClick(e, route_config, id) {
            if (this.select) {
                // select is turned on. So should check the checkbox
                let checkId = "#" + this.checkboxInputIdPrefix + id;
                let inputCheckBox = document.querySelector(checkId);

                console.log(e.target.id);
                console.log(checkId);
                let newCheckedVal;
                if (e.target.id == inputCheckBox.id) {
                    // clicked on checkbox. So checkbox is already checked
                    console.log("clicked on checkbox");
                    newCheckedVal = inputCheckBox.checked;
                } else {
                    // clicked on the notification body. So need to check checkbox
                    newCheckedVal = !inputCheckBox.checked;
                    // unchecking the input checkbox
                    inputCheckBox.checked = newCheckedVal;
                }
                console.log(e);
                console.log(inputCheckBox);
                console.log(newCheckedVal);
                // rtemoving the notification id from array
                if (newCheckedVal) {
                    // input now checked
                    this.selectedIds.push(id);
                } else {
                    let index = this.selectedIds.indexOf(id);
                    console.log("index :", index);
                    if (index != -1) {
                        this.selectedIds.splice(index, 1);
                    }
                }
                console.log(newCheckedVal);
                console.log(this.selectedIds);
                return;
            }
            let decoded_route = JSON.parse(route_config);
            console.log(decoded_route);

            this.$inertia.visit(
                route(decoded_route.name, decoded_route.options)
            );
            axios
                .post(route("notifications.seen"), {
                    id: id,
                })
                .catch((err) => {
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
