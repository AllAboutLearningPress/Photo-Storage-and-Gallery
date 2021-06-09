<template>
    <div class="js-editable editable file__title" ref="container">
        <h3
            v-on:click="editTitle($event, id)"
            class="editable__content fs-4 fw-light"
        >
            <span class="js-editable__val">
                {{ title }}
            </span>
            <button
                type="button"
                title="Edit"
                class="
                    js-editable__trigger
                    editable__trigger
                    d-inline-flex
                    btn btn-subtle btn-lg
                "
                aria-label="Edit"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    fill="currentColor"
                    class="bi bi-pen"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"
                    />
                </svg>
            </button>
        </h3>
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
export default {
    props: ["title"],
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
    mounted() {
        this.textareaValue = this.title;
        console.log("mounted");
    },
    methods: {
        onBlur(e) {
            console.log(e);
            console.log("input blurred");
            this.$refs["container"].classList.remove(this.editingKlass);
            this.$refs["textarea"].disabled = true;

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
