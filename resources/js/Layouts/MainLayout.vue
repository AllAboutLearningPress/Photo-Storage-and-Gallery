<template>
    <div>
        <div class="wrapper">
            <div class="js-selected-toolbar selected-toolbar toolbar">
                <div class="toolbar__main">
                    <button
                        type="button"
                        title="Unselect"
                        class="js-unselect selected-toolbar__unselect btn btn-lg btn-subtle"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            fill="currentColor"
                            class="me-1 bi bi-x-lg"
                            viewBox="0 0 16 16"
                        >
                            <path
                                d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"
                            />
                        </svg>
                        <span>2</span> selected
                    </button>
                </div>
                <div class="toolbar__aside">
                    <div class="toolbar__specific-actions">
                        <button
                            title="Download images"
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
                            <span class="visually-hidden">Download</span>
                        </button>
                        <button
                            title="Delete images"
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
                            <span class="visually-hidden">Delete</span>
                        </button>
                    </div>
                </div>
            </div>
            <Header></Header>
            <div class="content">
                <side-bar></side-bar>
                <main class="main">
                    <slot></slot>
                </main>
            </div>
        </div>

        <div class="js-drop-target drop-target">
            <div class="drop-target__message">
                \where
                <div class="js-drop-manager drop-manager">
                    <div class="drop-manager__note_search drop-manager__note">
                        (hold the Shift key to search)
                    </div>
                    <div class="drop-manager__note_upload drop-manager__note">
                        (release the Shift key to upload)
                    </div>
                </div>
            </div>
        </div>

        <div aria-live="polite" aria-atomic="true" class="notification-area">
            <div
                class="js-notification-container notification-area__container toast-container"
            >
                <!-- Put toasts here -->

                <div
                    class="js-invalid-drop-note notification-area__toast toast align-items-center"
                    role="alert"
                    aria-live="assertive"
                    aria-atomic="true"
                >
                    <div class="d-flex">
                        <div class="toast-body">
                            Some of the file types you dropped aren't allowed
                        </div>
                        <button
                            type="button"
                            class="btn-close me-2 m-auto"
                            data-bs-dismiss="toast"
                            aria-label="Close"
                        ></button>
                    </div>
                </div>
            </div>
        </div>
        <upload-progress-bar></upload-progress-bar>
    </div>
</template>
<style lang='scss'>
@import "../../sass/main.scss";
</style>
<script>
import Header from "../Components/Header.vue";
import SideBar from "../Components/SideBar.vue";
//import GlobalDropTarget from "../frontend/components/GlobalDropTarget.js";
import UploadProgressBar from "../Components/UploadProgressBar.vue";

export default {
    components: {
        Header,
        SideBar,
        UploadProgressBar,
    },
};
</script>
