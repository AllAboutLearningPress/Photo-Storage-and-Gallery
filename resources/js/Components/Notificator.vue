<template>
    <div aria-live="polite" aria-atomic="true" class="notification-area">
        <div
            class="
                js-notification-container
                notification-area__container
                toast-container
            "
        >
            <!-- Put toasts here -->

            <div
                v-for="toast in toasts.slice().reverse()"
                :key="toast.ref"
                :ref="toast.ref"
                :class="
                    'notification-area__toast toast align-items-center ' +
                    toast.level
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
                        v-if="toast.button"
                        v-on:click="toast.button.onClick"
                        type="button"
                        class="btn btn-outline-light btn-sm me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close"
                    >
                        {{ toast.button.text }}
                    </button>
                    <button
                        v-else
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
export default {
    data() {
        return {
            toasts: [],
            toastOptions: { delay: 10000 },
            ref: "toast-",
            toastId: 0,
            count: 0,
            levels: {
                info: "",
                danger: "bg-danger",
                success: "bg-success",
            },
        };
    },
    created() {
        document.addEventListener("notify", this.show);
        // this.$inertia.on("navigate", this.notifyFlash);
        this.$inertia.on("success", this.notifyFlash);
    },
    mounted() {
        this.container = document.querySelector(".js-notification-container");

        //this.testEvent();
    },
    methods: {
        notifyFlash() {
            if (this.$page.props.flash) {
                for (let key in this.$page.props.flash) {
                    let msgs = this.$page.props.flash[key];
                    if (msgs) {
                        if (typeof msgs == "string") {
                            this.notifyFlashMsg(msgs, key);
                        } else {
                            console.log("msgs", msgs);
                            console.log("keys", key);
                            msgs.forEach((msg) =>
                                this.notifyFlashMsg(msg, key)
                            );
                        }
                    }
                }
            }
        },
        notifyFlashMsg(flashMsg, type) {
            let button = null;
            let body = null;
            console.log(flashMsg);
            if (flashMsg.text) {
                body = flashMsg.text;
                button = flashMsg.button;
            } else {
                body = flashMsg;
            }
            this.show({
                detail: {
                    body: body,
                    button: button,
                    level: "success",
                },
            });
        },
        testEvent() {
            console.log(this.ref + this.count);
            document.dispatchEvent(
                new CustomEvent("notify", {
                    detail: {
                        body: this.ref + this.count,
                        level: "success",
                    },
                })
            );
            if (this.count < 1) {
                this.count++;
                setTimeout(this.testEvent, 150000);
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
            let level = "info";
            if (e.detail.level) {
                level = e.detail.level;
            }
            this.toasts.push({
                ref: ref,
                body: e.detail.body,
                level: this.levels[level],
                button: e.detail.button,
            });
            this.$nextTick(() => {
                let toast = new Toast(this.$refs[ref]);
                toast.show();
            });
        },
        hide(toastSelector) {
            const { instance } =
                this.getElemAndInstanceBySelector(toastSelector);

            instance && instance.hide();
        },
    },
};
</script>
