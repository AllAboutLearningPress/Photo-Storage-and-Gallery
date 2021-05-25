<template>
    <main-layout>
        <upload-toolbar></upload-toolbar>
        <h2 class="fw-light mb-5">
            Uploading 3 pictures, please take a moment to add details, or keep
            using a site
            <button
                v-on:click="cancelUpload"
                type="button"
                class="btn btn-outline-danger"
            >
                Cancel upload
            </button>
        </h2>
        <div class="upload-manager">
            <ul class="mb-5 upload-manager__files file-list nolist">
                <li
                    v-for="file in uploadingFiles"
                    :key="file.id"
                    class="file-list__item"
                    v-on:uploading="showUploads"
                >
                    <div class="file">
                        <div class="file__thumb">
                            <div
                                style="
                                    background-image: url('http://placekitten.com/200/200');
                                "
                                class="file__pic"
                            ></div>
                            <button
                                title="Remove file"
                                type="button"
                                class="js-file__delete file__delete btn-close btn-lg"
                                aria-label="Remove file"
                            >
                                <span class="visually-hidden">Remove file</span>
                            </button>
                        </div>
                        <div class="file__details">
                            <div class="file__progress progress">
                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: 25%"
                                    aria-valuenow="25"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                ></div>
                            </div>
                            <div class="file__header">
                                <div class="js-editable editable file__title">
                                    <h3 class="editable__content fs-4 fw-light">
                                        <span class="js-editable__val">
                                            {{ file.title }}
                                        </span>
                                        <button
                                            type="button"
                                            title="Edit"
                                            class="js-editable__trigger editable__trigger d-inline-flex btn btn-subtle btn-lg"
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
                                    <form
                                        class="js-editable__form editable__form"
                                        action="#"
                                    >
                                        <textarea
                                            disabled
                                            placeholder="Write here"
                                            class="js-editable__area editable__area fs-4 fw-light form-control form-control-lg form-control-plaintext"
                                        >
My title title title title title title title title title title title title title title title title title title title title</textarea
                                        >
                                        <button
                                            type="submit"
                                            class="js-editable__confirm btn btn-outline-secondary"
                                        >
                                            Ok
                                        </button>
                                    </form>
                                </div>
                                <div class="file__notes">
                                    <a
                                        class="file__note btn btn-outline-primary"
                                        href="#"
                                        >Choose a family?</a
                                    >
                                    <a
                                        v-if="file.hasDuplicate"
                                        class="file__note btn btn-outline-warning"
                                        href="#"
                                        >Compare duplicate</a
                                    >
                                    <a
                                        v-else
                                        class="file__note btn btn-outline-warning"
                                        href="#"
                                        >Checking for duplicate</a
                                    >
                                </div>
                            </div>
                            <div class="js-tags tags">
                                <form
                                    class="js-tags__form tags__form"
                                    onsubmit="this.elements['tag-input'].value && alert(`add tag: ${this.elements['tag-input'].value}`);return false;"
                                    action="#"
                                >
                                    <div class="input-group mt-1 mb-3">
                                        <input
                                            id="tag-input"
                                            name="tag-input"
                                            list="tag-list"
                                            class="js-tags__input form-control form-control-lg"
                                            type="text"
                                            placeholder="Specify tag"
                                            autocomplete="off"
                                        />
                                        <button
                                            title="Add tag"
                                            class="btn btn-lg btn-outline-secondary"
                                            type="submit"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </form>

                                <div class="js-tags__list tags__list">
                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        Tag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a>
                                    <!-- <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        Some tag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a>
                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        Another tag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a>
                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        Tag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a>
                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        My test tag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a>
                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        Tag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a>

                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                    >
                                        Some tag ometagomet agomet agometa etag
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                            >
                                                <span class="visually-hidden"
                                                    >Delete tag</span
                                                >
                                            </button>
                                        </object>
                                    </a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="file-list__item">
                    <div class="file">
                        <div class="file__thumb">
                            <div
                                style="
                                    background-image: url('http://placekitten.com/100/200');
                                "
                                class="file__pic"
                            ></div>
                            <button
                                title="Remove file"
                                type="button"
                                class="js-file__delete file__delete btn-close btn-lg"
                                aria-label="Remove file"
                            >
                                <span class="visually-hidden">Remove file</span>
                            </button>
                        </div>
                        <div class="file__details">
                            <div class="file__progress progress">
                                <div
                                    class="progress-bar"
                                    role="progressbar"
                                    style="width: 25%"
                                    aria-valuenow="25"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                ></div>
                            </div>
                            <div class="file__header">
                                <div class="js-editable editable">
                                    <h3 class="editable__content fs-4 fw-light">
                                        <span class="js-editable__val">
                                            My title
                                        </span>
                                        <button
                                            type="button"
                                            title="Edit"
                                            class="js-editable__trigger editable__trigger d-inline-flex btn btn-subtle btn-lg"
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
                                    <form
                                        class="js-editable__form editable__form"
                                        action="#"
                                    >
                                        <textarea
                                            disabled
                                            placeholder="Write here"
                                            class="js-editable__area editable__area fs-4 fw-light form-control form-control-lg form-control-plaintext"
                                        >
My title</textarea
                                        >
                                        <button
                                            type="submit"
                                            class="js-editable__confirm btn btn-outline-secondary"
                                        >
                                            Ok
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="js-tags tags">
                                <div
                                    class="js-tags__form tags__form"
                                    onsubmit="this.elements['tag-input1'].value && alert(`add tag: ${this.elements['tag-input1'].value}`);return false;"
                                    action="#"
                                >
                                    <div class="input-group mt-1 mb-3">
                                        <input
                                            id="tag-input1"
                                            name="tag-input"
                                            list="tag-list"
                                            class="js-tags__input form-control form-control-lg"
                                            type="text"
                                            placeholder="Specify tag"
                                            autocomplete="off"
                                        />
                                        <button
                                            title="Add tag"
                                            class="btn btn-lg btn-outline-secondary"
                                            type="submit"
                                            v-on:click="addTag"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <div class="js-tags__list tags__list"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- <li class="file-list__item">
                        <div class="file">
                            <div class="file__thumb">
                                <div
                                    style="
                                        background-image: url('http://placekitten.com/200/100');
                                    "
                                    class="file__pic"
                                ></div>
                                <button
                                    title="Remove file"
                                    type="button"
                                    class="js-file__delete file__delete btn-close btn-lg"
                                    aria-label="Remove file"
                                >
                                    <span class="visually-hidden"
                                        >Remove file</span
                                    >
                                </button>
                            </div>
                            <div class="file__details">
                                <div class="file__progress progress">
                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: 25%"
                                        aria-valuenow="25"
                                        aria-valuemin="0"
                                        aria-valuemax="100"
                                    ></div>
                                </div>
                                <div class="file__header">
                                    <div class="js-editable editable">
                                        <h3
                                            class="editable__content fs-4 fw-light"
                                        >
                                            <span class="js-editable__val">
                                                My title
                                            </span>
                                            <button
                                                type="button"
                                                title="Edit"
                                                class="js-editable__trigger editable__trigger d-inline-flex btn btn-subtle btn-lg"
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
                                        <form
                                            class="js-editable__form editable__form"
                                            action="#"
                                        >
                                            <textarea
                                                disabled
                                                placeholder="Write here"
                                                class="js-editable__area editable__area fs-4 fw-light form-control form-control-lg form-control-plaintext"
                                            >
My title</textarea
                                            >
                                            <button
                                                type="submit"
                                                class="js-editable__confirm btn btn-outline-secondary"
                                            >
                                                Ok
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="js-tags tags">
                                    <form
                                        class="js-tags__form tags__form"
                                        onsubmit="this.elements['tag-input2'].value && alert(`add tag: ${this.elements['tag-input2'].value}`);return false;"
                                        action="#"
                                    >
                                        <div class="input-group mt-1 mb-3">
                                            <input
                                                id="tag-input2"
                                                name="tag-input"
                                                list="tag-list"
                                                class="js-tags__input form-control form-control-lg"
                                                type="text"
                                                placeholder="Specify tag"
                                                autocomplete="off"
                                            />
                                            <button
                                                title="Add tag"
                                                class="btn btn-lg btn-outline-secondary"
                                                type="submit"
                                            >
                                                Add
                                            </button>
                                        </div>
                                    </form>

                                    <div class="js-tags__list tags__list"></div>
                                </div>
                            </div>
                        </div>
                    </li> -->
            </ul>
        </div>
    </main-layout>

    <datalist id="tag-list">
        <option v-for="tag in tags" :key="tag.id" :value="tag.name"></option>
    </datalist>
</template>

<style lang='scss'>
@import "../../../sass/main.scss";
</style>
<script>
import MainLayout from "../../Layouts/MainLayout.vue";
import UploadToolbar from "./Components/UploadToolbar.vue";
import { usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
export default {
    components: { MainLayout, UploadToolbar },
    props: ["tags"],
    created() {
        // this event is dispatched by HeaderUpload.vue
        // containing the filesArray to show the user
        // for editing
        // this event listener will be moved to a specific componenet later
        document.addEventListener("uploading-files", this.showUploads);
        document.addEventListener("file-uploaded", this.fileUploaded);
        // letting the parent window know that upload
        // view is ready for data
        let event = new Event("upload-view-created");
        document.dispatchEvent(event);
        // console.log(this.tags);
        // Inertia.reload({ only: ["tags"] }).then(() => {
        //     this.$forceUpdate();
        //     console.log(this.tags);
        // });

        // console.log(this.tags);

        // fetch tags lazily from server
        this.fetchTags(route("tags.get_tags"));
    },
    data() {
        return {
            uploadingFiles: [],
            tags: [],
        };
    },
    mounted() {},
    methods: {
        /*
        Fetches the tags from server in chunks.
        Server returns 100 tags at each request then
        It is all loaded to the datalist element.
        We need to replace the logic if the tags list is
        Very long. That would create memory issues.
        */
        fetchTags(url) {
            axios
                .get(url)
                .then((resp) => {
                    this.tags.push(...resp.data.data);

                    // checking if there is more tags to fetch
                    // if there are more tags then server will
                    // return the next url to fetch data
                    if (resp.data.next_page_url) {
                        this.fetchTags(resp.data.next_page_url);
                    } else {
                        console.log(this.tags.length);
                        console.log("All tags fetched");
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        showUploads(e) {
            console.log("received");
            this.uploadingFiles = e.detail.filesArray;
            //this.tags = e.detail.tags;
        },
        addTag(e) {
            console.log("tag added");
            console.log(e);
            const tagInput = e.target.parentElement.querySelector("input");
            console.log(tagInput.value);
        },
        cancelUpload() {
            console.log(this.tags);
        },

        // When a file is uploaded by HeaderUpload
        // It will dispatch an event. And this componenet
        // Will listen for that event to update data in
        // This page
        fileUploaded(e) {
            console.log(e);
        },
    },
};
</script>
