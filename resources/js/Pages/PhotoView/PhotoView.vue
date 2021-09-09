<template >
    <div class="image-view-wrapper">
        <div class="image-view">
            <photo-info
                v-if="photo.user"
                :photo="photo"
                :sidebarPositionClass="sidebarPositionClass"
                :toggleSidebar="toggleSidebar"
            ></photo-info>
            <div class="sidebar-backdrop"></div>
            <div class="image-view__picture-area">
                <div class="image-view__toolbar toolbar">
                    <div v-if="$page.props.user" class="toolbar__main">
                        <back-button></back-button>
                        <home-button></home-button>
                    </div>
                    <div class="toolbar__aside">
                        <div class="toolbar__specific-actions">
                            <input
                                type="checkbox"
                                class="
                                    toolbar__specific-actions-toggle
                                    toggler
                                    btn-check
                                "
                                id="toolbar__specific-actions-toggle"
                                autocomplete="off"
                            />
                            <label
                                title="Show more actions"
                                class="btn-subtle btn"
                                for="toolbar__specific-actions-toggle"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="currentColor"
                                    class="
                                        toggler__icon-open
                                        bi bi-three-dots-vertical
                                    "
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="currentColor"
                                    class="toggler__icon-close bi bi-x"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
                                    />
                                </svg>
                                <span class="visually-hidden"
                                    >Show more actions</span
                                >
                            </label>

                            <span class="toolbar__specific-actions-more">
                                <share-button
                                    v-if="$page.props.user"
                                    v-on:click="openShareModal"
                                ></share-button>
                                <download-button
                                    v-if="downloadLink"
                                    v-on:click="downloadPhoto"
                                ></download-button>
                                <delete-button
                                    v-if="$page.props.user"
                                    v-on:click="toggleDeleteModal"
                                ></delete-button>
                                <restore-button
                                    v-if="photo.deleted_at"
                                    v-on:click="restorePhoto"
                                ></restore-button>
                            </span>
                            <show-details-button
                                v-if="photo.user"
                                v-on:open-sidebar="toggleSidebar"
                            ></show-details-button>
                        </div>
                        <upload-icon v-if="$page.props.user"></upload-icon>
                    </div>
                </div>
                <!--
          The values for required inline styles are the following:

          `padding-top: min(x, y)`: x is an img aspect ratio calculated by a formula "img height / img width * 100%"; y — actual image height
          `width`: actual img width

          Add any elements inside (for preloading/etc.) but keep existing elements intact
        -->
                <div :style="genStyle" class="image-view__pic">
                    <img
                        draggable="false"
                        class="image-view__img"
                        width="800"
                        height="800"
                        :src="photo.src"
                        alt=""
                    />
                </div>
                <div v-if="photo.deleted_at" class="image-view-trash-info">
                    You are viewing a trashed photo
                </div>
                <!-- <a
                    href="#"
                    draggable="false"
                    class="image-view__picture-nav-prev image-view__picture-nav"
                    title="Previous image"
                >
                    <span class="visually-hidden">Previous image</span>
                </a>
                <a
                    href="#"
                    draggable="false"
                    class="image-view__picture-nav-next image-view__picture-nav"
                    title="Next image"
                >
                    <span class="visually-hidden">Next image</span>
                </a> -->
            </div>
        </div>

        <tags-datalist></tags-datalist>
        <data-modal
            modalId="deleteModal"
            title="Delete Photo"
            :deleted="photo.deleted_at ? true : false"
        >
            <template v-slot:body
                ><p class="text-break">
                    Are you sure about
                    <span class="text-danger">{{
                        photo.deleted_at ? "permanently" : ""
                    }}</span>
                    deleting "{{ photo.title }}" ?
                </p>
            </template>
            <template v-slot:action_button>
                <button v-on:click="deletePhoto" class="btn btn-danger">
                    {{ photo.deleted_at ? "Permanently" : "" }} Delete
                </button>
            </template>
        </data-modal>
        <data-modal
            modalId="shareModal"
            id="shareModal"
            title="Share photo"
            :deleted="photo.deleted_at ? true : false"
        >
            <template v-slot:body>
                <div class="row share-check-container">
                    <div class="form-check">
                        <input
                            ref="shareCanDownload"
                            class="form-check-input"
                            type="checkbox"
                            id="share-download-checkbox"
                            style="margin-bottom: 0.5rem"
                            v-on:click="genDownloadableLink"
                        />
                        <label
                            class="form-check-label"
                            for="share-download-checkbox"
                        >
                            Can Download
                        </label>
                    </div>

                    <div class="form-check">
                        <input
                            ref="shareCanViewInfo"
                            class="form-check-input"
                            type="checkbox"
                            id="share-view-info-checkbox"
                            style="margin-bottom: 0.5rem"
                            v-on:click="genDownloadableLink"
                        />
                        <label
                            class="form-check-label"
                            for="share-view-info-checkbox"
                        >
                            Can View Info
                        </label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        id="photo-share-link"
                        placeholder=""
                        aria-label="Photo Shareable Link"
                        aria-describedby="Photo shreable link"
                        :value="shareLink"
                    />
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        id="button-addon2"
                        v-on:click="copyShareLink"
                    >
                        Copy
                    </button>
                </div></template
            >
            <template v-slot:action_button>
                <button v-on:click="openShareLink" class="btn btn-success">
                    Open Share Link
                </button>
            </template>
        </data-modal>
    </div>
</template>
<style lang="scss" >
.modal-backdrop {
    z-index: 100;
}
.share-check-container {
    margin-left: 0;
}
</style>
<script>
import { inject } from "@vue/runtime-core";
import MainLayout from "@/Layouts/MainLayout.vue";
import BackButton from "@/Components/BackButton.vue";
import HomeButton from "@/Components/HomeButton.vue";
import DownloadButton from "./Componenets/DownloadButton.vue";
import ShareButton from "./Componenets/ShareButton.vue";
import DeleteButton from "./Componenets/DeleteButton.vue";
import ShowDetailsButton from "./Componenets/ShowDetailsButton.vue";

import TagsDatalist from "@/Components/TagsDatalist.vue";
import Modal from "bootstrap/js/dist/modal";
import { notify } from "@/util.js";
import RestoreButton from "./Componenets/RestoreButton.vue";

import DataModal from "@/Components/DataModal.vue";
import UploadIcon from "../../Components/UploadIcon.vue";

import PhotoInfo from "./Componenets/PhotoInfo.vue";

export default {
    props: ["photo", "downloadLink", "info"],
    components: {
        BackButton,
        HomeButton,
        ShowDetailsButton,
        DeleteButton,
        ShareButton,
        DownloadButton,
        TagsDatalist,
        RestoreButton,
        DataModal,
        UploadIcon,
        PhotoInfo,
    },
    layoout: MainLayout,

    setup() {
        // const toggleHeader = inject("toggleHeader");
        // const showHeader = inject("showHeader");
        // // hiding header
        // console.log(toggleHeader);
        // console.log(showHeader);
        // //toggleHeader(false);
        // return {
        //     showHeader,
        //     toggleHeader,
        // };
    },
    data() {
        return {
            deleteModal: null,
            deleteModalText: "",
            shareLink: "",
            sidebarPositionClass: "", // used to show/hide sidebar. Will show if set to "is-open"
        };
    },
    // created() {
    //     // if (!this.photo.size && this.$page.props.user) {
    //     //     // componenet is created from gallery index
    //     //     // so we need to request data about the photo
    //     //     // axios
    //     //     //     .post(route("photo.get_info"), { id: this.photo.id })
    //     //     //     .then((resp) => {
    //     //     //         console.log(resp);
    //     //     //         this.photo = resp.data;
    //     //     //     });
    //     // }
    //     // setTimeout(() => {
    //     //     console.log(this.photo);
    //     // });
    // },
    beforeMount() {
        // getting the last sidebarposition from localstorage
        this.sidebarPositionClass = localStorage.getItem(
            "sidebar-position-class"
        );

        if (this.$page.props.users) {
            this.toggleHeader = inject("toggleHeader");
            this.showHeader = inject("showHeader");

            // // hiding header
            // console.log(toggleHeader);
            // console.log(showHeader);
            // //toggleHeader(false);

            // return {
            //     showHeader,
            //     toggleHeader,
            // };
        }
    },
    mounted() {
        // console.log(this.$page.props.user);
        console.log(this.photo);
        if (!this.photo.file_name) {
            console.log("reload");
            this.$inertia.reload({ only: ["photo"] });
        }
    },
    beforeUnmount() {
        // Showing header again for other pages
        console.log(this.toggleHeader);
        this.toggleHeader(true);
    },
    computed: {
        genStyle() {
            console.log(this.photo);
            console.log("generating styles");
            return (
                "padding-top: min(" +
                `${(this.photo.height / this.photo.width) * 100}%` +
                ", " +
                `${this.photo.height}px` +
                `); width: ${this.photo.width}px`
            );
        },
    },

    methods: {
        /**Toggles the photo info sidebar */
        toggleSidebar() {
            this.sidebarPositionClass =
                this.sidebarPositionClass == "is-open" ? "" : "is-open";

            localStorage.setItem(
                "sidebar-position-class",
                this.sidebarPositionClass
            );
        },

        toggleDeleteModal(e) {
            if (this.deleteModal == null) {
                this.deleteModal = new Modal(
                    document.querySelector("#deleteModal"),
                    {
                        backdrop: "static",
                    }
                );
                console.log(this.deleteModal);
            }
            this.deleteModal.toggle();
        },
        /** Moves a photo to trash and deletes permanenetly from trash
         * Executed when clicking on the deletePhoto button.
         * Performs a softdelete on first request and performs
         * a force delete on 2nd request
         */
        deletePhoto(e) {
            e.preventDefault();
            // hiding the delete modal
            this.deleteModal.toggle();
            this.$emit("close");
            this.$inertia.post(
                route("photo.delete"),
                {
                    id: this.photo.id,
                    force: this.photo.deleted_at ? true : false,
                },
                {
                    onSuccess: (resp) => {
                        console.log("resp", resp);
                        // show notification according to the delete state
                        if (this.photo.deleted_at) {
                            notify("Photo permanently deleted", "success");
                        } else {
                            notify("Photo moved to trash", "success", {
                                text: "Undo",
                                onClick: () => this.restorePhoto(),
                            });
                        }
                    },
                }
            );
        },
        /**Restores a photo from trash to photos */
        restorePhoto(e) {
            this.$inertia.post(
                route("photo.restore"),
                { id: this.photo.id },
                {
                    onSuccess: () => notify("Photo restored", "success"),
                }
            );
            this.$emit("close");
        },
        downloadPhoto(e) {
            if (this.downloadLink) {
                window.open(this.downloadLink);
            } else {
                axios
                    .post(route("downloads.generate_link"), {
                        id: this.photo.id,
                    })
                    .then((resp) => {
                        // open the download link in a new tab. So it can start downloading
                        window.open(resp.data);
                    });
            }
        },
        genDownloadableLink(e, param_name) {
            console.log(e);
            let perm_params = {
                download: this.$refs["shareCanDownload"].checked,
                info: this.$refs["shareCanViewInfo"].checked,
            };

            this.genShareableLink(perm_params);
            this.shareModal.show();
        },
        openShareModal(e) {
            //this.copyShareLink(e);
            let shareModal = document.querySelector("#shareModal");

            /** this will copy the link to clipboard once the model
             * is visible */
            shareModal.addEventListener(
                "shown.bs.modal",
                () => {
                    this.copyShareLink(e);
                },
                { once: true }
            );
            // requesting shareable link
            this.genShareableLink();
        },
        genShareableLink(perm_params) {
            this.shareLink = "Generating Link";
            let req_data = {
                photo_id: this.photo.id,
                ...perm_params,
            };

            axios
                .post(route("share.create"), req_data)
                .then((resp) => {
                    console.log(resp);
                    this.shareLink = resp.data;
                    //window.open(resp.data);
                    if (this.shareModal == null) {
                        this.shareModal = new Modal(
                            document.querySelector("#shareModal"),
                            {
                                backdrop: "static",
                            }
                        );

                        console.log(this.shareModal);
                    }
                    this.shareModal.show();
                    if (perm_params) {
                        this.copyShareLink();
                    }
                })
                .catch((err) => {
                    console.error(err);
                    notify("Something went wrong. Please try again", "danger");
                });
        },
        copyShareLink(e) {
            let linkInput = document.querySelector("#photo-share-link");
            linkInput.focus();
            linkInput.select();
            document.execCommand("copy");
            linkInput.blur();
            notify("Link copied in clipboard", "success");
        },
        openShareLink() {
            window.open(this.shareLink);
        },
    },
};
</script>
