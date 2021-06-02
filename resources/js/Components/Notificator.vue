<template>
    <div aria-live="polite" aria-atomic="true" class="notification-area">
        <div
            class="js-notification-container notification-area__container toast-container"
        >
            <!-- Put toasts here -->

            <div
                v-for="toast in toasts.slice().reverse()"
                :key="toast.ref"
                :ref="toast.ref"
                :class="
                    toast.ref +
                    ' notification-area__toast toast align-items-center'
                "
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
            toastOptions: { delay: 10000 },
            ref: "toast-",
            toastId: 0,
            count: 0,
        };
    },
    created() {
        document.addEventListener("notify", this.show);
    },
    mounted() {
        this.container = document.querySelector(".js-notification-container");

        //this.testEvent();
    },
    methods: {
        testEvent() {
            console.log(this.ref + this.count);
            document.dispatchEvent(
                new CustomEvent("notify", {
                    detail: {
                        body: this.ref + this.count,
                    },
                })
            );
            if (this.count < 10) {
                this.count++;
                setTimeout(this.testEvent, 1500);
            }
        },
        getElemAndInstanceBySelector(selector) {
            const elem = document.querySelector(selector);
            let instance;

            if (elem) {
                instance = Toast.getInstance(elem);

                if (!instance) {
                    instance = new Toast(elem, ...this.toastOptions);
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
        show(e) {
            let ref = this.ref + this.toastId;
            this.toastId++;
            this.toasts.push({ ref: ref, body: e.detail.body });
            this.$nextTick(() => {
                let toast = new Toast(this.$refs[ref]);
                toast.show();
            });
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
