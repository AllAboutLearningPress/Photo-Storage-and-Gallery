<template>
    <div>
        <!-- <upload-toolbar></upload-toolbar> -->
        <h2 v-if="filesArray.length" class="fw-light mb-5">
            <div v-if="filesArray.length != uploadedCount">
                Uploading {{ filesArray.len }} pictures, please take a moment to
                add details, or keep using a site
                <button
                    v-on:click="cancelUpload"
                    type="button"
                    class="btn btn-outline-danger"
                >
                    Cancel upload
                </button>
            </div>
            <div v-else>
                Upload Finished. Please check the file details and complete
                upload.
                <button
                    v-on:click="completeUpload"
                    type="button"
                    class="btn btn-outline-success"
                >
                    Complete upload
                </button>
            </div>
        </h2>
        <div class="upload-manager">
            <ul class="mb-5 upload-manager__files file-list nolist">
                <li
                    v-for="(file, index) in filesArray"
                    :key="file.id"
                    class="file-list__item"
                >
                    <div
                        v-if="!file.cancelled"
                        class="file"
                        :id="'file' + file.id"
                    >
                        <div class="file__thumb">
                            <div v-if="file.uploadCompleted" class="file__pic">
                                <completed-tick></completed-tick>
                            </div>
                            <div
                                v-else
                                style="
                                    background-image: url('/images/spinner.svg');
                                "
                                class="file__pic"
                            ></div>

                            <button
                                v-on:click="cancelSingleUpload(index)"
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
                                    :id="file.id"
                                    v-bind:title="file.title"
                                    v-on:title-change="
                                        file.title = $event;
                                        updateTitle($event, file.id);
                                    "
                                >
                                </file-title>
                                <file-notes
                                    :hasDuplicate="file.hasDuplicate"
                                ></file-notes>
                            </div>
                            <file-tag
                                :tags="file.tags"
                                :id="file.id"
                                v-on:add-tag="file.tags.push($event)"
                                v-on:remove-tag="file.tags.splice($event, 1)"
                            >
                            </file-tag>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <tags-datalist :tags="tags"></tags-datalist>
    </div>
</template>


<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import UploadToolbar from "./Components/UploadToolbar.vue";
import { notify } from "@/util.js";
//import { addEventListener } from "@/frontend/util/utils.js";
import { inject } from "@vue/runtime-core";
import FileNotes from "./Components/ FileNotes.vue";
import FileTitle from "@/Components/FileTitle.vue";
import FileTag from "@/Components/FileTag.vue";
import CompletedTick from "./Components/CompletedTick.vue";
import TagsDatalist from "@/Components/TagsDatalist.vue";
export default {
    components: {
        UploadToolbar,
        FileNotes,
        FileTitle,
        FileTag,
        CompletedTick,
        TagsDatalist,
    },
    layout: MainLayout,
    setup() {
        const pushToFilesArray = inject("pushToFilesArray");
        const filesArray = inject("filesArray");
        const uploadedCount = inject("uploadedCount");
        const fetchTags = inject("fetchTags");
        const resetUpload = inject("resetUpload");
        return {
            pushToFilesArray,
            filesArray,
            uploadedCount,
            fetchTags,
            resetUpload,
        };
    },
    data() {
        return {
            // filesArray: [],
            tags: [],

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
        // this.filesArray.push(
        //     {
        //         id: 0,
        //         title: "test file",
        //         tags: [],
        //         //thumbnail_link: "http://placekitten.com/200/100",
        //     }
        //     // {
        //     //     id: 1,
        //     //     title: "test file",
        //     //     tags: [],
        //     //     //thumbnail_link: "http://placekitten.com/200/100",
        //     // }
        // );
        // setInterval(() => {
        //     console.log(this.filesArray[0].title);
        // }, 1000);
    },
    mounted() {
        // event listener for updating individual progress bar
        // this.rmlisteners.push(
        //     addEventListener(
        //         document,
        //         "update-progress-bar",
        //         this.animateUploadBar
        //     )
        // );
    },

    methods: {
        /**Cancels the full upload */
        cancelUpload() {
            console.log("upload cancelled");
            // saving the filesArray for restoring
            let filesArray = [...this.filesArray];
            let ids = [];
            for (let i = 0; i < this.filesArray.length; i++) {
                ids.push({ id: this.filesArray[i].id });
            }
            console.log(ids);
            this.sendCancelUploadReq(ids, () => {
                this.filesArray = filesArray;
            });
            this.$inertia.get(route("home"));
            this.filesArray = [];
        },

        cancelSingleUpload(index) {
            let file = this.filesArray[index];
            // removing the file from filesArray
            //this.filesArray.splice(index, 1);
            // cancelling the upload
            this.filesArray[index].cancelToken.cancel();
            console.log("cancelling ", this.filesArray[index]);
            this.filesArray[index].cancelled = true;
            // sending request to remove the file entry
            this.sendPhotoDetailsReq([{ id: file.id }], () => {
                // adding the file again to exact location
                // zthis.filesArray.splice(index, 0, file);
            });
        },
        sendCancelUploadReq(ids, errCallback) {
            axios
                .post(route("uploads.cancel_upload"), ids)
                .then((resp) => {
                    // let user know that this file is cancelled
                    notify("File removed");
                })
                .catch((err) => {
                    console.error(err);
                    notify("Something went wrong. Please try again");
                    errCallback();
                });
        },
        completeUpload(e) {
            this.resetUpload();
            notify("Upload completed", "success");
            this.$inertia.visit(route("home"));
        },
    },
    beforeUnmount() {
        // removing the event listener for this page
        this.rmlisteners.forEach((func) => func());
    },
};
</script>
