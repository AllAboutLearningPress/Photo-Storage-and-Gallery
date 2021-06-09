<template>
    <div>
        <!-- <upload-toolbar></upload-toolbar> -->
        <h2 v-if="filesArray.length" class="fw-light mb-5">
            Uploading {{ filesArray.len }} pictures, please take a moment to add
            details, or keep using a site
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
                    v-for="file in filesArray"
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
                            >
                                <svg
                                    v-if="file.uploadCompleted"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="file__pic__tick bi bi-check"
                                    fill="currentColor"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"
                                    />
                                </svg>
                            </div>

                            <button
                                v-on:click="cancelSingleUpload($event, file.id)"
                                title="Remove file"
                                type="button"
                                class="
                                    js-file__delete
                                    file__delete
                                    btn-close btn-lg
                                "
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
                                    :style="'width: ' + file.width"
                                    aria-valuenow="0"
                                    aria-valuemin="0"
                                    aria-valuemax="100"
                                ></div>
                            </div>
                            <div class="file__header">
                                <file-title
                                    v-bind:title="file.title"
                                    v-on:title-change="
                                        file.title = $event;
                                        saveTitle(file.title, file.id);
                                    "
                                ></file-title>
                                <file-notes
                                    :hasDuplicate="file.hasDuplicateddssss"
                                ></file-notes>
                            </div>
                            <file-tag
                                :tags="file.tags"
                                v-on:add-tag="
                                    file.tags.push(
                                        checkUniqueTag(file.tags, $event)
                                    )
                                "
                            >
                            </file-tag>
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
import MainLayout from "@/Layouts/MainLayout.vue";
import UploadToolbar from "./Components/UploadToolbar.vue";
import { notify } from "@/util.js";
import { addEventListener } from "@/frontend/util/utils.js";
import { inject } from "@vue/runtime-core";
import FileNotes from "./Components/ FileNotes.vue";
import FileTitle from "./Components/FileTitle.vue";
import FileTag from "./Components/FileTag.vue";
export default {
    components: { UploadToolbar, FileNotes, FileTitle, FileTag },
    layout: MainLayout,
    setup() {
        const pushToFilesArray = inject("pushToFilesArray");
        const filesArray = inject("filesArray");
        return {
            pushToFilesArray,
            filesArray,
        };
    },
    data() {
        return {
            // filesArray: [],
            tags: [],
            spinner: "/images/spinner.svg",
            rmlisteners: [],
            // editableContainerKlass: "js-editable",
            // editingKlass: "is-editing",
            confirmKlass: "js-editable__confirm",
            textareaKlass: "js-editable__area",
        };
    },
    created() {
        // fetch tags lazily from server
        this.fetchTags(route("tags.get_tags"));
        this.filesArray.push(
            {
                id: 0,
                title: "test file",
                tags: [],
                //thumbnail_link: "http://placekitten.com/200/100",
            }
            // {
            //     id: 1,
            //     title: "test file",
            //     tags: [],
            //     //thumbnail_link: "http://placekitten.com/200/100",
            // }
        );
        // setInterval(() => {
        //     console.log(this.filesArray[0].title);
        // }, 1000);
    },
    mounted() {
        // event listener for updating individual progress bar
        this.rmlisteners.push(
            addEventListener(
                document,
                "update-progress-bar",
                this.animateUploadBar
            )
        );
    },

    methods: {
        animateUploadBar(e) {
            console.log(e);
            window.requestAnimationFrame(() => this.updateUploadBar(e));
        },
        checkUniqueTag(tags, newTag) {
            tags.forEach((tag) => {
                if (Tag.name == newTag) {
                    return;
                }
            });
            return newTag;
        },
        saveTitle(title, fileId) {
            console.log(title);
            console.log(fileId);

            // send axios request to save title
            this.sendPhotoDetailsReq({ title: title, id: fileId });
        },
        sendPhotoDetailsReq(data) {
            axios
                .post(route("uploads.update-details"), data)
                .then((resp) => {
                    notify("Photo Updated");
                })
                .catch((err) => {
                    notify("Something went Wrong", "danger");
                });
        },

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

        /**
         * Gets called when add button is pressed on tag
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

                // adding the tag to the filesArray Array
                // its a O(n) solution.
                for (let k = 0; k < this.filesArray.length; k++) {
                    if (this.filesArray[k].id == fileId) {
                        for (
                            let i = 0;
                            i < this.filesArray[k].tags.length;
                            i++
                        ) {
                            if (
                                this.filesArray[k].tags[i].name ==
                                tagInput.value
                            ) {
                                // user already added this tag once
                                return;
                            }
                        }

                        // adding new tag to the image
                        this.filesArray[k].tags.push({
                            name: tagInput.value,
                            id: tagId,
                        });
                        this.sendAddTagReq(this.filesArray[k].id, tagId);
                        console.log(this.filesArray[k].tags);
                        break;
                    }
                }
            }
        },
        /** Sends "add tag" request to server
         * @param {Number} fileId - The id of the file where tag should be added
         * @param {Number} tagId - The if of the tag to add
         */
        sendAddTagReq(fileId, tagId) {
            axios
                .post(route("uploads.add_tag"), {
                    fileId: fileId,
                    tagId: tagId,
                })
                .then((resp) => {
                    if (resp.status == 201) {
                        // Tag created because user typed a new tag string
                        notify("Tag created and added to photo");
                        return;
                    }
                    notify("Tag Added successfully");
                });
        },
        /** Sends "delete tag" request to server
         * @param {Number} - The id of the file to remove tag from
         * @param {Number} - The id of tag to remove
         */
        sendDeleteTagReq(fileId, tagId) {
            axios
                .post(route("uploads.remove_tag"), {
                    fileId: fileId,
                    tagId: tagId,
                })
                .then((reso) => {
                    notify("Tag Removed");
                })
                .catch((err) => {
                    notify("Something went wrong. Please try again");
                    console.log(err);
                });
        },
        /** Removes tag from uploading image
        @param {Int} tagIndex - The index of tag in the this.filesArray[i].tags
        @param {Int} fileId - the if of the file that the tag is assigned to. this id is local
        @returns {null}
        */
        removeTag(tagIndex, fileId) {
            for (let i = 0; i < this.filesArray.length; i++) {
                if (this.filesArray[i].id == fileId) {
                    console.log(this.filesArray[i]);
                    this.sendDeleteTagReq(
                        this.filesArray[i].id,
                        this.filesArray[i].tags[tagIndex].id
                    );
                    this.filesArray[i].tags.splice(tagIndex, 1);
                }
            }
        },
        /**Cancels the full upload */
        cancelUpload() {
            console.log("upload cancelled");
            let ids = [];
            for (let i = 0; i < this.filesArray.length; i++) {
                ids.push(this.filesArray[i].id);
            }
            this.sendCancelUploadReq(ids, "Upload Cancelled");
            this.$inertia.get(route("home"));
            this.filesArray = [];
        },

        cancelSingleUpload(e, fileId) {
            for (let i = 0; i < this.filesArray.length; i++) {
                if (this.filesArray[i].id == fileId) {
                    // file found
                    // cancelling the ongoing upload
                    this.filesArray[i].cancelToken.cancel();
                    let title = this.filesArray[i].title;

                    // removing the file from filesArray
                    this.filesArray.splice(i, 1);
                    this.sendCancelUploadReq([fileId], "File removed");
                    // removing the entry from filesArray.
                    //this.filesArray.splice(i, 1);
                }
            }
        },
        sendCancelUploadReq(ids, notificationText) {
            // sending request to remove the file entry
            axios
                .post(route("uploads.cancel_upload"), ids)
                .then((resp) => {
                    // let user know that this file is cancelled

                    notify(notificationText);
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
    beforeUnmount() {
        // removing the event listener for this page
        console.log(this.rmlisteners);
        this.rmlisteners.forEach((func) => func());
    },
};
</script>
