<template >
    <div>
        <div class="image-view">
            <div class="image-view__picture-area">
                <div class="image-view__toolbar toolbar">
                    <div class="toolbar__main">
                        <a
                            title="Go back"
                            href="#"
                            class="js-go-back btn-subtle btn"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="26"
                                height="26"
                                fill="currentColor"
                                class="bi bi-arrow-left-short"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"
                                ></path>
                            </svg>
                            <span class="visually-hidden">Go back</span>
                        </a>
                        <a
                            title="Go home"
                            href="#"
                            class="js-go-home btn-subtle btn"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                fill="currentColor"
                                class="bi bi-house"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"
                                />
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"
                                />
                            </svg>
                            <span class="visually-hidden">Go home</span>
                        </a>
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
                                <button
                                    title="Share image"
                                    type="button"
                                    class="js-image-share btn-subtle btn"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        fill="currentColor"
                                        class="bi bi-share"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"
                                        ></path>
                                    </svg>
                                    <span class="visually-hidden">Share</span>
                                </button>
                                <button
                                    title="Download image"
                                    type="button"
                                    class="js-image-download btn-subtle btn"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        fill="currentColor"
                                        class="bi bi-cloud-download"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z"
                                        ></path>
                                        <path
                                            d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z"
                                        ></path>
                                    </svg>
                                    <span class="visually-hidden"
                                        >Download</span
                                    >
                                </button>
                                <button
                                    title="Delete image"
                                    type="button"
                                    class="js-image-delete btn-subtle btn"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        fill="currentColor"
                                        class="bi bi-trash"
                                        viewBox="0 0 16 16"
                                    >
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"
                                        ></path>
                                        <path
                                            fill-rule="evenodd"
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"
                                        ></path>
                                    </svg>
                                    <span class="visually-hidden"
                                        >Download</span
                                    >
                                </button>
                            </span>
                            <button
                                title="Show details"
                                type="button"
                                class="
                                    js-sidebar-toggle
                                    image-view__show-details
                                    btn-subtle btn
                                "
                                data-sidebar="#image-details-sidebar"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="currentColor"
                                    class="bi bi-info-circle"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"
                                    />
                                    <path
                                        d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"
                                    />
                                </svg>
                                <span class="visually-hidden"
                                    >Show details</span
                                >
                            </button>
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
                        src="//placekitten.com/800/800"
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
import MainLayout from "../Layouts/MainLayout.vue";
export default {
    components: { MainLayout },
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
    mounted() {
        // this.photoView.value = true;
        //this.toggleHeader(false);
    },

    beforeMount() {
        // this.toggleHeader(false);
    },
    beforeUnmount() {
        // Showing header again for othert pages
        //this.toggleHeader(true);
    },
};
</script>
