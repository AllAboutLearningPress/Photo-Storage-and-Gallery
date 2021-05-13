<template>
    <div class="header__slot_search header__slot">
        <form action="#" class="js-search search">
            <div class="search__image-progress progress">
                <div
                    class="js-search__image-progress-bar progress-bar"
                    role="progressbar"
                    style="width: 0"
                    aria-valuenow="0"
                    aria-valuemin="0"
                    aria-valuemax="100"
                ></div>
            </div>
            <div class="js-search__field search__field">
                <button
                    title="Hide search"
                    type="button"
                    class="js-search__toggle search__infield-action search__infield-action_hide btn btn-subtle btn-lg"
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
                </button>
                <input
                    class="js-search__input search__input form-control form-control-lg"
                    type="text"
                    placeholder="Search..."
                    autocomplete="off"
                    inputmode="search"
                />
                <label
                    tabindex="-1"
                    title="Search by image"
                    class="search__infield-action search__infield-action_image-search button-file btn btn-subtle btn-lg"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="26"
                        height="26"
                        fill="currentColor"
                        class="bi bi-camera"
                        viewBox="0 0 16 16"
                    >
                        <path
                            d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z"
                        />
                        <path
                            d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"
                        />
                    </svg>
                    <span class="visually-hidden">Search by image</span>
                    <input
                        class="js-search__image-input button-file__input"
                        type="file"
                        accept="image/jpeg, image/png, image/gif, image/tiff, image/vnd.adobe.photoshop, .jpg, .jpeg, .png, .gif, .tif, .tiff, .psd"
                    />
                </label>
                <div class="js-search__suggest search__suggest">
                    <div class="search__suggest-content scrollbar">
                        <div class="list-group list-group-flush">
                            <a
                                data-suggestion-id="1"
                                href="#"
                                tabindex="-1"
                                class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action"
                            >
                                Suggestion 1
                            </a>
                            <a
                                data-suggestion-id="2"
                                href="#"
                                tabindex="-1"
                                class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action"
                            >
                                Suggestion Suggestion Suggestion Suggestion
                                Suggestion Suggestion
                            </a>
                            <a
                                data-suggestion-id="3"
                                href="#"
                                tabindex="-1"
                                class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action"
                            >
                                Suggestion 2
                            </a>
                            <a
                                data-suggestion-id="4"
                                href="#"
                                tabindex="-1"
                                class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action"
                            >
                                Suggestion 4
                            </a>
                            <a
                                data-suggestion-id="5"
                                href="#"
                                tabindex="-1"
                                class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action"
                            >
                                Suggestion 5
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <button
                title="Show search"
                type="button"
                class="js-search__toggle search__field-show btn btn-subtle btn-lg"
            >
                <span
                    class="search__field-image-search-spinner spinner-border spinner-border-sm text-secondary"
                    role="status"
                >
                    <span class="visually-hidden">Loading...</span>
                </span>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="22"
                    height="22"
                    fill="currentColor"
                    class="bi bi-search"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"
                    ></path>
                </svg>
                <span class="visually-hidden">Search</span>
            </button>
        </form>
    </div>
</template>

<script>
import Search from "../frontend/components/Search.js";
export default {
    mounted: () => {
        const search = new Search(document.querySelector(".js-search"));
    },
};
</script>
