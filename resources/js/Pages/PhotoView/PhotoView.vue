<template >
    <div class="image-view-wrapper">
        <div class="image-view">
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
                                <share-button></share-button>
                                <download-button></download-button>
                                <delete-button></delete-button>
                            </span>
                            <show-details-button></show-details-button>
                        </div>
                        <div class="toolbar__main-actions">
                            <label
                                title="Upload more"
                                class="js-upload button-file btn-subtle btn"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="currentColor"
                                    class="bi bi-cloud-upload"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"
                                    ></path>
                                    <path
                                        fill-rule="evenodd"
                                        d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"
                                    ></path>
                                </svg>
                                <input
                                    class="js-upload__input button-file__input"
                                    type="file"
                                    multiple
                                    accept="image/jpeg, image/png, image/gif, image/tiff, image/vnd.adobe.photoshop, .jpg, .jpeg, .png, .gif, .tif, .tiff, .psd"
                                />
                                <span class="visually-hidden">Upload more</span>
                            </label>
                        </div>
                    </div>
                </div>
                <!--
          The values for required inline styles are the following:

          `padding-top: min(x, y)`: x is an img aspect ratio calculated by a formula "img height / img width * 100%"; y — actual image height
          `width`: actual img width

          Add any elements inside (for preloading/etc.) but keep existing elements intact
        -->
                <div
                    style="padding-top: min(100%, 800px); width: 800px"
                    class="image-view__pic"
                >
                    <img
                        draggable="false"
                        class="image-view__img"
                        width="800"
                        height="800"
                        :src="'/storage/full_size/' + photo.file_name"
                        alt=""
                    />
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

        <datalist id="tag-list">
            <!-- preloaded minimal list -->
            <option value="San Francisco"></option>
            <option value="New York"></option>
            <option value="Seattle"></option>
            <option value="Los Angeles"></option>
            <option value="Chicago"></option>
            <option value="Dallas"></option>
            <option value="San Diego"></option>
            <option value="San Antonio"></option>
            <option value="Memphis"></option>
            <option value="Aurora"></option>
            <option value="Anaheim"></option>
            <option value="Cleveland"></option>
            <option value="Bakersfield"></option>
            <option value="Wichita"></option>
            <option value="Toledo"></option>
        </datalist>
    </div>
</template>

<script>
import { inject } from "@vue/runtime-core";
import MainLayout from "@/Layouts/MainLayout.vue";
import BackButton from "./Componenets/BackButton.vue";
import HomeButton from "./Componenets/HomeButton.vue";
import DownloadButton from "./Componenets/DownloadButton.vue";
import ShareButton from "./Componenets/ShareButton.vue";
import DeleteButton from "./Componenets/DeleteButton.vue";
import ShowDetailsButton from "./Componenets/ShowDetailsButton.vue";
export default {
    props: ["photo"],
    components: {
        BackButton,
        HomeButton,
        ShowDetailsButton,
        DeleteButton,
        ShareButton,
        DownloadButton,
    },
    layout: MainLayout,

    setup() {
        const toggleHeader = inject("toggleHeader");
        const showHeader = inject("showHeader");
        // hiding header
        console.log(showHeader);
        toggleHeader(false);
        console.log(showHeader);
        return { showHeader, toggleHeader };
    },
    data() {
        return {
            imgUrl: "//placekitten.com/800/800",
        };
    },
    mounted() {
        // this.photoView.value = true;
        //this.toggleHeader(false);
        console.log(this.photo);
    },

    beforeMount() {
        // this.toggleHeader(false);
    },
    beforeUnmount() {
        // Showing header again for othert pages
        this.toggleHeader(true);
    },
};
</script>
