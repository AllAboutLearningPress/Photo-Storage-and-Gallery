<template>
    <div
        class="modal fade"
        :id="modalId"
        aria-hidden="true"
        aria-labelledby
        tabindex="-1"
        data-backdrop="false"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalToggleLabel">
                        {{ title }}
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <button
                        class="btn btn-primary"
                        :data-bs-target="'#' + modalId"
                        data-bs-toggle="modal"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>
                    <slot name="action_button"> </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["title", "deleted", "modalId"],
};
</script>
