<template>
    <div aria-live="polite" aria-atomic="true" class="notification-area">
        <div
            class="js-notification-container notification-area__container toast-container"
        >
            <!-- Put toasts here -->

            <div
                v-for="toast in toastData"
                :key="toast.id"
                :id="'toast' + toast.id"
                class="js-invalid-drop-note notification-area__toast toast align-items-center"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
            >
                <div class="d-flex">
                    <div class="toast-body">
                        {{ toast.body }}
                    </div>
                    <button
                        type="button"
                        class="btn-close me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close"
                    ></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Toast from "bootstrap/js/dist/toast";
import { Inertia } from "@inertiajs/inertia";
export default {
    data() {
        return {
            toasts: [],
            handlers: [],
            isInited: true,
            toastData: [
                {
                    id: 1,
                    body: "Some of the file types you dropped aren't allowed",
                },
                {
                    id: 2,
                    body: "File upload completed",
                },
            ],
            toastOptions: { delay: 10000 },
        };
    },
    created() {
        document.addEventListener("notify", (e) => {
            this.show("#toast2");
        });
    },
    mounted() {
        this.container = document.querySelector(".js-notification-container");

        if (!this.container) {
            return;
        }
    },
    methods: {
        getElemAndInstanceBySelector(selector) {
            const elem = document.querySelector(selector);
            let instance;

            if (elem) {
                instance = Toast.getInstance(elem);

                if (!instance) {
                    instance = new Toast(elem, this.toastOptions);
                    this.toasts.push(instance);
                }
            } else {
                return {};
            }

            return {
                elem,
                instance,
            };
        },
        show(toastSelector) {
            const { elem, instance } = this.getElemAndInstanceBySelector(
                toastSelector
            );

            if (elem) {
                // always stack the newest toast at the beginning
                this.container.insertAdjacentElement("afterbegin", elem);
                instance.show();
            }
        },
        hide(toastSelector) {
            const { instance } = this.getElemAndInstanceBySelector(
                toastSelector
            );

            instance && instance.hide();
        },
    },
};
</script>
