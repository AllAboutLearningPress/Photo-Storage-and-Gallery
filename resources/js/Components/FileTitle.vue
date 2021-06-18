<template>
    <div ref="container">
        <dl
            v-on:click="editTitle($event, id)"
            class="editable__content dlist_0 dlist"
        >
            <dt><slot></slot></dt>
            <dd>
                <span class="js-editable__val">
                    {{ title }}
                </span>
                <edit-pen></edit-pen>
            </dd>
        </dl>

        <div class="js-editable__form editable__form" action="#">
            <textarea
                disabled
                placeholder="Write here"
                class="
                    js-editable__area
                    editable__area
                    fs-4
                    fw-light
                    form-control form-control-lg form-control-plaintext
                "
                v-model="textareaValue"
                v-on:blur="onBlur"
                ref="textarea"
            ></textarea>
            <button class="js-editable__confirm btn btn-outline-secondary">
                Ok
            </button>
        </div>
    </div>
</template>

<script>
import { notify, updatePhotoDetails } from "@/util.js";
import EditPen from "../CommonButtons/EditPen.vue";
export default {
    components: { EditPen },
    props: ["title", "id", "name"],
    model: {
        prop: "title",
        event: "change",
    },
    data() {
        return {
            editableContainerKlass: "js-editable",
            editingKlass: "is-editing",
            textareaValue: null,
        };
    },
    setup(props) {
        const textareaValue = props.title;
        return {
            textareaValue,
        };
    },
    methods: {
        onBlur(e) {
            console.log(e);
            console.log("input blurred");
            this.$refs["container"].classList.remove(this.editingKlass);
            this.$refs["textarea"].disabled = true;
            updatePhotoDetails({
                title: this.$refs["textarea"].value,
                id: this.id,
            });
            this.$emit("title-change", this.$refs["textarea"].value);
        },

        editTitle(e, id) {
            e.preventDefault();

            setTimeout(() => {
                console.log(this.$refs);
                this.$refs["container"].classList.add(this.editingKlass);
                this.$refs["textarea"].disabled = false;
                this.resize(this.$refs["textarea"]);
                this.$refs["textarea"].focus();

                // allow android chrome to lag, and then actually do select text
                setTimeout(() => this.$refs["textarea"].select(), 0);
            }, 0);
        },
        resize(tx) {
            tx.style.height = "auto";
            tx.style.height = tx.scrollHeight + "px";
        },
    },
};
</script>
