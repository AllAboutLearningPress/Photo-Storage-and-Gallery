<template>
    <div class="js-editable editable file__title">
        <h3
            v-on:click="editTitle($event, file.id)"
            class="editable__content fs-4 fw-light"
        >
            <span class="js-editable__val">
                {{ file.title }}
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
                v-model="file.title"
                :id="'title' + file.id"
            ></textarea>
            <button
                v-on:click="saveTitle($event, file.id)"
                class="js-editable__confirm btn btn-outline-secondary"
            >
                Ok
            </button>
        </div>
    </div>
</template>

<script>
export default {};
</script>
