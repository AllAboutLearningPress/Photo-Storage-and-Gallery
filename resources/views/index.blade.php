<!DOCTYPE html>
<html lang="">

    <head>
        <meta charset="utf-8" />
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <!-- See https://github.com/h5bp/html5-boilerplate for details -->

        <!-- see https://github.com/h5bp/html5-boilerplate/blob/fd9f88e846a409088e81292b24ac74a53e86c693/src/doc/html.md#web-app-manifest -->
        <link rel="manifest" href="site.webmanifest" />
        <link rel="apple-touch-icon" href="icon.png" />

        <!-- Place favicon.ico in the root directory -->

        <link rel="stylesheet" href="css/bundle.css" />

        <!-- see https://github.com/h5bp/html5-boilerplate/blob/0ebf00b4c24ea4e27fdb63ff312740f3b6aa14fe/dist/doc/extend.md#theme-color -->
        <meta name="theme-color" content="#fafafa" />
    </head>

    <body class="is-uploading">
        <div class="wrapper">
            <div class="js-selected-toolbar selected-toolbar toolbar">
                <div class="toolbar__main">
                    <button type="button" title="Unselect"
                        class="js-unselect selected-toolbar__unselect btn btn-lg btn-subtle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                            class="me-1 bi bi-x-lg" viewBox="0 0 16 16">
                            <path
                                d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z" />
                        </svg>
                        <span>2</span> selected
                    </button>
                </div>
                <div class="toolbar__aside">
                    <div class="toolbar__specific-actions">
                        <button title="Download images" type="button" class="js-image-download btn-subtle btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-cloud-download" viewBox="0 0 16 16">
                                <path
                                    d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z">
                                </path>
                                <path
                                    d="M7.646 15.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 14.293V5.5a.5.5 0 0 0-1 0v8.793l-2.146-2.147a.5.5 0 0 0-.708.708l3 3z">
                                </path>
                            </svg>
                            <span class="visually-hidden">Download</span>
                        </button>
                        <button title="Delete images" type="button" class="js-image-delete btn-subtle btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-trash" viewBox="0 0 16 16">
                                <path
                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z">
                                </path>
                                <path fill-rule="evenodd"
                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z">
                                </path>
                            </svg>
                            <span class="visually-hidden">Delete</span>
                        </button>
                    </div>
                </div>
            </div>
            <header class="js-search-header header">
                <div class="header__slot_logo header__slot">
                    <button title="Open menu" type="button"
                        class="js-sidebar-toggle header__open-sidebar btn-subtle btn-menu btn-lg btn"
                        data-sidebar="#content-sidebar" aria-controls="sidebar">
                        <span class="visually-hidden">Open menu</span>
                    </button>
                    <a class="logo btn-lg btn" href="#">Photos</a>
                </div>
                <div class="header__slot_search header__slot">
                    <form action="#" class="js-search search">
                        <div class="search__image-progress progress">
                            <div class="js-search__image-progress-bar progress-bar" role="progressbar" style="width: 0"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="js-search__field search__field">
                            <button title="Hide search" type="button"
                                class="js-search__toggle search__infield-action search__infield-action_hide btn btn-subtle btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                                    class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z">
                                    </path>
                                </svg>
                                <span class="visually-hidden">Go back</span>
                            </button>
                            <input class="js-search__input search__input form-control form-control-lg" type="text"
                                placeholder="Search..." autocomplete="off" inputmode="search" />
                            <label tabindex="-1" title="Search by image"
                                class="search__infield-action search__infield-action_image-search button-file btn btn-subtle btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                                    class="bi bi-camera" viewBox="0 0 16 16">
                                    <path
                                        d="M15 12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h1.172a3 3 0 0 0 2.12-.879l.83-.828A1 1 0 0 1 6.827 3h2.344a1 1 0 0 1 .707.293l.828.828A3 3 0 0 0 12.828 5H14a1 1 0 0 1 1 1v6zM2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4H2z" />
                                    <path
                                        d="M8 11a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5zm0 1a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM3 6.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
                                </svg>
                                <span class="visually-hidden">Search by image</span>
                                <input class="js-search__image-input button-file__input" type="file"
                                    accept="image/jpeg, image/png, image/gif, image/tiff, image/vnd.adobe.photoshop, .jpg, .jpeg, .png, .gif, .tif, .tiff, .psd" />
                            </label>
                            <div class="js-search__suggest search__suggest">
                                <div class="search__suggest-content scrollbar">
                                    <div class="list-group list-group-flush">
                                        <a data-suggestion-id="1" href="#" tabindex="-1"
                                            class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action">
                                            Suggestion 1
                                        </a>
                                        <a data-suggestion-id="2" href="#" tabindex="-1"
                                            class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action">
                                            Suggestion Suggestion Suggestion Suggestion Suggestion Suggestion
                                        </a>
                                        <a data-suggestion-id="3" href="#" tabindex="-1"
                                            class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action">
                                            Suggestion 2
                                        </a>
                                        <a data-suggestion-id="4" href="#" tabindex="-1"
                                            class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action">
                                            Suggestion 4
                                        </a>
                                        <a data-suggestion-id="5" href="#" tabindex="-1"
                                            class="js-search__suggest-item search__suggest-item list-group-item list-group-item-action">
                                            Suggestion 5
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button title="Show search" type="button"
                            class="js-search__toggle search__field-show btn btn-subtle btn-lg">
                            <span
                                class="search__field-image-search-spinner spinner-border spinner-border-sm text-secondary"
                                role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z">
                                </path>
                            </svg>
                            <span class="visually-hidden">Search</span>
                        </button>
                    </form>
                </div>
                <div class="header__slot_actions header__slot">
                    <label title="Upload" class="js-upload header__action-btn button-file btn btn-subtle btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-cloud-upload" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M4.406 1.342A5.53 5.53 0 0 1 8 0c2.69 0 4.923 2 5.166 4.579C14.758 4.804 16 6.137 16 7.773 16 9.569 14.502 11 12.687 11H10a.5.5 0 0 1 0-1h2.688C13.979 10 15 8.988 15 7.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 2.825 10.328 1 8 1a4.53 4.53 0 0 0-2.941 1.1c-.757.652-1.153 1.438-1.153 2.055v.448l-.445.049C2.064 4.805 1 5.952 1 7.318 1 8.785 2.23 10 3.781 10H6a.5.5 0 0 1 0 1H3.781C1.708 11 0 9.366 0 7.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383z">
                            </path>
                            <path fill-rule="evenodd"
                                d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z">
                            </path>
                        </svg>
                        <span class="header__action-btn__txt">Upload</span>
                        <input class="js-upload__input button-file__input" type="file"
                            accept="image/jpeg, image/png, image/gif, image/tiff, image/vnd.adobe.photoshop, .jpg, .jpeg, .png, .gif, .tif, .tiff, .psd"
                            multiple />
                        <span class="visually-hidden">Upload more</span>
                    </label>
                </div>
            </header>
            <div class="content">
                <aside id="content-sidebar" class="js-content-sidebar sidebar" tabindex="-1" aria-labelledby="sidebar">
                    <div class="sidebar__head">
                        <button title="Close menu" type="button"
                            class="js-sidebar-toggle sidebar__close btn-subtle btn-lg btn-close"
                            data-sidebar="#content-sidebar" aria-label="Close">
                            <span class="visually-hidden">Close menu</span>
                        </button>
                    </div>
                    <div class="sidebar__body scrollbar">
                        <div class="navigation list-group list-group-flush">
                            <span class="active navigation__item list-group-item" aria-current="true">
                                My photos
                            </span>
                            <a href="#" class="navigation__item list-group-item list-group-item-action">
                                Popular tags
                            </a>
                            <a href="#" class="navigation__item list-group-item list-group-item-action">
                                Trash
                            </a>
                            <a href="/accounts/logout" class="navigation__item list-group-item list-group-item-action">
                                Logout
                            </a>
                        </div>
                    </div>
                </aside>
                <div class="sidebar-backdrop"></div>
                <main class="main">
                    <form class="mb-3 js-filter filter" action="#">
                        <input type="checkbox" class="filter__toggle toggler btn-check" id="filter__toggle"
                            autocomplete="off" />
                        <label title="Show filter" class="filter__toggle-label btn-subtle btn" for="filter__toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="toggler__icon-open bi bi-funnel" viewBox="0 0 16 16">
                                <path
                                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="toggler__icon-close bi bi-x" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                            <span class="filter__header fw-light">Filter</span>
                        </label>
                        <a onclick="document.querySelector('.js-selected-toolbar').classList.toggle('is-visible');"
                            href="#">toggle header</a>
                        <div class="filter__fields">
                            <fieldset class="filter__param">
                                <h5 class="fw-light mb-2">Upload date:</h5>
                                <label class="me-1" for="upload-date-start">From</label>
                                <input id="upload-date-start" value="2021-05-11"
                                    class="me-1 mb-3 mw-100 w-auto d-inline-flex form-control" type="date" />
                                <label class="me-1" for="upload-date-end">to</label>
                                <input id="upload-date-end" value="2021-05-11"
                                    class="mb-3 mw-100 w-auto d-inline-flex form-control" type="date" />
                            </fieldset>
                        </div>
                    </form>
                    <div id="pig" class="gallery mb-4"></div>
                    <button type="button" class="btn btn-lg btn-primary">Load more</button>
                </main>
            </div>
        </div>

        <div class="js-drop-target drop-target">
            <div class="drop-target__message">
                Drop anywhere
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
            <div class="js-notification-container notification-area__container toast-container">
                <!-- Put toasts here -->

                <div class="js-invalid-drop-note notification-area__toast toast align-items-center" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">Some of the file types you dropped aren't allowed</div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" class="js-upload-bar upload-bar">
            Uploading 9243 photos (1h 53m remaining)
            <div class="upload-bar__progress progress">
                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100"></div>
            </div>
        </a>

        <script src="js/vendor/pig.min.js"></script>
        <script src="js/bundle.js"></script>
        <script>
            const imageData = [
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '100', aspectRatio: 1.777 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
        { filename: '150', aspectRatio: 1.777 },
        { filename: '500', aspectRatio: 1 },
        { filename: '400', aspectRatio: 2.4 },
        { filename: '200', aspectRatio: 1.5 },
        { filename: '400', aspectRatio: 1.777 },
      ];

      const options = {
        urlForSize: function (filename, size) {
          return `//placekitten.com/${size}/${filename}`;
        },
        onClickHandler(filename) {
          alert(`select ${filename}`);
        },
        figureTagName: 'a',
      };

      const pig = new Pig(imageData, options).enable();

      // WARNING: this is required to fix `pigjs` bug, use after each `Pig` initialisation
      window.dispatchEvent(new Event('resize'));
        </script>
    </body>

</html>
