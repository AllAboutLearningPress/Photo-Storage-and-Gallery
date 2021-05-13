<template>
    <form class="mb-3 js-filter filter" action="#">
        <input
            type="checkbox"
            class="filter__toggle toggler btn-check"
            id="filter__toggle"
            autocomplete="off"
        />
        <label
            title="Show filter"
            class="filter__toggle-label btn-subtle btn"
            for="filter__toggle"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                fill="currentColor"
                class="toggler__icon-open bi bi-funnel"
                viewBox="0 0 16 16"
            >
                <path
                    d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"
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
            <span class="filter__header fw-light">Filter</span>
        </label>
        <a
            onclick="document.querySelector('.js-selected-toolbar').classList.toggle('is-visible');"
            href="#"
            >toggle header</a
        >
        <div class="filter__fields">
            <fieldset class="filter__param">
                <h5 class="fw-light mb-2">Upload date:</h5>
                <label class="me-1" for="upload-date-start">From</label>
                <input
                    id="upload-date-start"
                    value="2021-05-11"
                    class="me-1 mb-3 mw-100 w-auto d-inline-flex form-control"
                    type="date"
                />
                <label class="me-1" for="upload-date-end">to</label>
                <input
                    id="upload-date-end"
                    value="2021-05-11"
                    class="mb-3 mw-100 w-auto d-inline-flex form-control"
                    type="date"
                />
            </fieldset>
        </div>
    </form>
</template>
<script>
export default {};
</script>
