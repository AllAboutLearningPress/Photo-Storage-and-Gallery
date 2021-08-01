<template >
    <div class="image-view-wrapper">
        <div class="image-view">
            <aside
                id="image-details-sidebar"
                ref="sidebar"
                class="
                    js-image-details-sidebar
                    sidebar_always-hideable sidebar_right sidebar
                    image-view__details
                "
                tabindex="-1"
                aria-labelledby="sidebar"
            >
                <div class="image-view__details-head sidebar__head">
                    <button
                        title="Close details"
                        type="button"
                        class="
                            js-sidebar-toggle
                            image-view__details-toggle
                            btn-subtle btn-lg btn-close
                        "
                        data-sidebar="#image-details-sidebar"
                        aria-label="Close details"
                        v-on:click="toggleSidebar"
                    >
                        <span class="visually-hidden">Close details</span>
                    </button>
                </div>
                <div class="sidebar__body scrollbar">
                    <div class="image-view__details-content">
                        <ul class="nolist">
                            <li class="js-editable editable">
                                <!-- <dl class="editable__content dlist_0 dlist">
                                    <dt class="mr-1">Title:</dt>
                                    <dd>
                                        <span class="js-editable__val">
                                            {{ photo.title }}
                                        </span>
                                        <edit-pen></edit-pen>
                                    </dd>
                                </dl> -->

                                <file-title
                                    v-on:title-change="photo.title = $event"
                                    :id="photo.id"
                                    :title="photo.title"
                                    name="title"
                                    >Title:
                                </file-title>
                            </li>
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Created:</dt>
                                    <dd>21.21.21</dd>
                                </dl>
                            </li>
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Uploaded:</dt>
                                    <dd>
                                        {{ formatTimestamp(photo.created_at) }}
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Dimensions:</dt>
                                    <dd>
                                        {{ photo.height }}x{{ photo.width }}
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>File size:</dt>
                                    <dd>{{ bytesToSize(photo.size) }}</dd>
                                </dl>
                            </li>
                            <!-- <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Family tree:</dt>
                                    <dd>
                                        <a class="link-action" href="#">Show</a>
                                    </dd>
                                </dl>
                            </li> -->
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Uploaded by:</dt>
                                    <dd>{{ photo.user.name }}</dd>
                                </dl>
                            </li>
                            <li v-if="photo.location">
                                <dl class="dlist_0 dlist">
                                    <dt>Location map:</dt>
                                    <dd>
                                        LocationLocationLocationLocationLocationLocationLocationLocationLocation
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>Licensing and attribution:</dt>
                                    <dd>
                                        Whether the photographer and/or models
                                        has signed an agreement with us; Whether
                                        we need to give attribution; Whether
                                        someone owns the copyright other than
                                        us; Whether it is stock photography
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl class="dlist_0 dlist">
                                    <dt>
                                        <label for="tag-input">Tags</label>
                                    </dt>
                                    <dd>
                                        <file-tag
                                            :tags="photo.tags"
                                            :id="photo.id"
                                            v-on:add-tag="
                                                photo.tags.push($event)
                                            "
                                            v-on:remove-tag="
                                                photo.tags.splice($event, 1)
                                            "
                                        >
                                        </file-tag>
                                    </dd>
                                </dl>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
            <div class="sidebar-backdrop"></div>
            <div class="image-view__picture-area">
                <div class="image-view__toolbar toolbar">
                    <div class="toolbar__main">
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
                                    v-on:click="genShareableUrl"
                                ></share-button>
                                <download-button
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
                <div :style="style" class="image-view__pic">
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
                <a
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
                </a>
            </div>
        </div>

        <tags-datalist></tags-datalist>
        <data-modal
            modalId="deleteModal"
            title="Delete Photo"
            :deleted="photo.deleted_at ? true : false"
        >
            <template v-slot:body
                >Are you sure about
                <span class="text-danger">{{
                    photo.deleted_at ? "permanently" : ""
                }}</span>
                deleting "{{ photo.title }}" ?</template
            >
            <template v-slot:action_button>
                <button v-on:click="deletePhoto" class="btn btn-danger">
                    {{ photo.deleted_at ? "Permanently" : "" }} Delete
                </button>
            </template>
        </data-modal>
        <data-modal
            modalId="shareModal"
            title="Share photo"
            :deleted="photo.deleted_at ? true : false"
        >
            <template v-slot:body
                ><div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder=""
                        aria-label=""
                        aria-describedby="button-addon2"
                        :value="shareUrl"
                    />
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        id="button-addon2"
                        v-on:click="copyShareUrl"
                    >
                        Copy
                    </button>
                </div></template
            >
            <template v-slot:action_button>
                <button v-on:click="openShareUrl" class="btn btn-success">
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
import FileTag from "@/Components/FileTag.vue";
import TagsDatalist from "@/Components/TagsDatalist.vue";
import Modal from "bootstrap/js/dist/modal";
import { notify, updatePhotoDetails } from "@/util.js";
import RestoreButton from "./Componenets/RestoreButton.vue";
import FileTitle from "@/Components/FileTitle.vue";
import DataModal from "@/Components/DataModal.vue";
import UploadIcon from "../../Components/UploadIcon.vue";

export default {
    props: ["photo", "downloadUrl"],
    components: {
        BackButton,
        HomeButton,
        ShowDetailsButton,
        DeleteButton,
        ShareButton,
        DownloadButton,
        FileTag,
        TagsDatalist,
        RestoreButton,
        FileTitle,
        DataModal,
        UploadIcon,
    },
    layout: MainLayout,

    setup() {
        const toggleHeader = inject("toggleHeader");
        const showHeader = inject("showHeader");

        // hiding header
        toggleHeader(false);
        return { showHeader, toggleHeader };
    },
    data() {
        return {
            deleteModal: null,
            deleteModalText: "",
            style: this.genStyle(),
            shareUrl: "",
        };
    },

    beforeUnmount() {
        // Showing header again for other pages
        this.toggleHeader(true);
    },
    methods: {
        bytesToSize(bytes) {
            var sizes = ["Bytes", "KB", "MB", "GB", "TB"];
            if (bytes == 0) return "0 Byte";
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + " " + sizes[i];
        },
        genStyle() {
            return (
                "padding-top: min(" +
                `${(this.photo.height / this.photo.width) * 100}%` +
                ", " +
                `${this.photo.height}px` +
                `); width: ${this.photo.width}px`
            );
        },
        /**Toggles the photo info sidebar */
        toggleSidebar(e) {
            this.$refs["sidebar"].classList.toggle("is-open");
        },
        formatTimestamp(timestamp) {
            let d = new Date(timestamp);

            return d.toString();
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
            this.$inertia.post(
                route("photo.delete"),
                {
                    id: this.photo.id,
                    force: this.photo.deleted_at ? true : false,
                },
                {
                    onSuccess: (resp) => {
                        console.log("resp", resp);
                        notify("Photo moved to trash", "success", {
                            text: "Undo",
                            onClick: () => this.restorePhoto(),
                        });
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
        },
        downloadPhoto(e) {
            if (this.downloadUrl) {
                window.open(this.downloadUrl);
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
        genShareableUrl() {
            axios
                .post(route("share.create"), { photo_id: this.photo.id })
                .then((resp) => {
                    console.log(resp);
                    //window.open(resp.data);
                    if (this.shareModal == null) {
                        this.shareModal = new Modal(
                            document.querySelector("#shareModal"),
                            {
                                backdrop: "static",
                            }
                        );
                        this.shareUrl = resp.data;
                        console.log(this.shareModal);
                    }
                    this.shareModal.toggle();
                    this.copyShareUrl();
                })
                .catch((err) => {
                    console.error(err);
                    notify("Something went wrong. Please try again", "danger");
                });
        },
        copyShareUrl() {
            let urlInput = document
                .querySelector("#shareModal")
                .querySelector("input");
            console.log(urlInput);
            urlInput.select();
            document.execCommand("copy");
            urlInput.blur();
            notify("Link copied in clipboard", "success");
        },
        openShareUrl() {
            window.open(this.shareUrl);
        },
    },
};
</script>
