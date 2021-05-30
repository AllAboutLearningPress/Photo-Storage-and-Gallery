<template>
    <div>
        <!-- <upload-toolbar></upload-toolbar> -->
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
                >
                    <div class="file" :id="'file' + file.id">
                        <div class="file__thumb">
                            <div
                                :style="`background-image: url('${
                                    file.thumbnail_link
                                        ? file.thumbnail_link
                                        : spinner
                                }')`"
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
                                    aria-valuenow="0"
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
                                <div
                                    class="js-tags__form tags__form"
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
                                            v-on:click="addTag($event, file.id)"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>

                                <div class="js-tags__list tags__list">
                                    <a
                                        class="tags__tag tag tag_deletable btn btn-secondary"
                                        href="#"
                                        v-for="(tag, index) in file.tags"
                                        :key="tag.id"
                                    >
                                        {{ tag.name }}
                                        <object type="no/suchtype">
                                            <button
                                                title="Delete tag"
                                                type="button"
                                                class="js-tag-delete tag__delete btn-close"
                                                aria-label="Delete tag"
                                                v-on:click="
                                                    removeTag(index, file.id)
                                                "
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
            </ul>
        </div>
        <datalist id="tag-list">
            <option
                v-for="tag in tags"
                :key="tag.id"
                :value="tag.name"
                :data-id="tag.id"
            ></option>
        </datalist>
    </div>
</template>


<script>
import MainLayout from "../../Layouts/MainLayout.vue";
import UploadToolbar from "./Components/UploadToolbar.vue";

export default {
    components: { UploadToolbar },
    layout: MainLayout,
    created() {
        // this event is dispatched by HeaderUpload.vue
        // containing the filesArray to show the user
        // for editing
        // this event listener will be moved to a specific componenet later
        document.addEventListener("uploading-files", this.showUploads);
        document.addEventListener("file-uploaded", this.fileUploaded);

        // fetch tags lazily from server
        this.fetchTags(route("tags.get_tags"));
        // this.uploadingFiles = [
        //     {
        //         id: 0,
        //         title: "test file",
        //         tags: [],
        //         //thumbnail_link: "http://placekitten.com/200/100",
        //     },
        //     {
        //         id: 1,
        //         title: "test file",
        //         tags: [],
        //         //thumbnail_link: "http://placekitten.com/200/100",
        //     },
        // ];

        // // for testing file uploaded event
        // setTimeout(() => {
        //     document.dispatchEvent(
        //         new CustomEvent("file-uploaded", {
        //             detail: {
        //                 id: 0,
        //                 thumbnail_link: "https://placekitten.com/200/100",
        //                 serverId: 223,
        //             },
        //         })
        //     );
        // }, 500);
        // setTimeout(() => {
        //     document.dispatchEvent(
        //         new CustomEvent("file-uploaded", {
        //             detail: {
        //                 id: 1,
        //                 thumbnail_link: "https://placekitten.com/200/100",
        //                 serverId: 223,
        //             },
        //         })
        //     );
        // }, 1000);
        document.dispatchEvent(new CustomEvent("upload-view-created"));
    },
    mounted() {
        // event listener for updating individual progress bar
        document.addEventListener("update-progress-bar", this.animateUploadBar);
        document.dispatchEvent(new CustomEvent("update-progress-total"));
    },
    data() {
        return {
            uploadingFiles: [],
            tags: [],
            spinner: "/images/spinner.svg",
        };
    },

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
        },

        /**
         * get called when add button is pressed on tag
         * @param {Event} e - Add button click event
         * @param {Int} fileId - the id of the file that the tag is being added to
         */
        addTag(e, fileId) {
            console.log(e);
            const tagInput = e.target.parentElement.querySelector("input");
            if (tagInput.value) {
                let tagOption = document
                    .querySelector("datalist")
                    .querySelector(`[value='${tagInput.value}']`);

                // tagId will be null if user typed a new tag
                let tagId = null;
                if (tagOption) {
                    // user choosed a tag from the datalist
                    tagId = tagOption.getAttribute("data-id");
                }

                // adding the tag to the uploadingFiles Array
                // its a O(n) solution.
                for (let k = 0; k < this.uploadingFiles.length; k++) {
                    if (this.uploadingFiles[k].id == fileId) {
                        for (
                            let i = 0;
                            i < this.uploadingFiles[k].tags.length;
                            i++
                        ) {
                            if (
                                this.uploadingFiles[k].tags[i].name ==
                                tagInput.value
                            ) {
                                // user already added this tag once
                                return;
                            }
                        }

                        // adding new tag to the image
                        this.uploadingFiles[k].tags.push({
                            name: tagInput.value,
                            id: tagId,
                        });
                        console.log(this.uploadingFiles[k].tags);
                        break;
                    }
                }
            }
        },
        /** Removes tag from uploading image
        @param {Int} tagIndex - The index of tag in the this.uploadingFiles[i].tags
        @param {Int} fileId - the if of the file that the tag is assigned to. this id is local
        @returns {null}
        */
        removeTag(tagIndex, fileId) {
            for (let i = 0; i < this.uploadingFiles.length; i++) {
                if (this.uploadingFiles[i].id == fileId) {
                    console.log(this.uploadingFiles[i]);
                    this.uploadingFiles[i].tags.splice(tagIndex, 1);
                }
            }
        },
        /**Cancels the full upload */
        cancelUpload() {
            console.log("upload cancelled");
        },

        // When a file is uploaded by HeaderUpload
        // It will dispatch an event. And this componenet
        // Will listen for that event to update data in
        // This page
        fileUploaded(e) {
            for (let i = 0; i < this.uploadingFiles.length; i++) {
                if (this.uploadingFiles[i].id == e.detail.id) {
                    this.uploadingFiles[i].serverId = e.detail.serverId;
                    this.uploadingFiles[i].thumbnail_link =
                        e.detail.thumbnail_link;
                }
            }
        },
        animateUploadBar(e) {
            console.log(e);
            window.requestAnimationFrame(() => this.updateUploadBar(e));
        },
        updateUploadBar(e) {
            let progressBar = document
                .getElementById("file" + e.detail.fileId)
                .querySelector(".progress-bar");
            console.log(progressBar);
            (progressBar.style.width = (e.loaded / e.total) * 100), +"%";
        },
    },
};
</script>
