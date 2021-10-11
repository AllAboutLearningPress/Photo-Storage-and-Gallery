<template>
    <!-- Modal -->
    <div
        class="modal fade"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
        id="invite-modal"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Invite user by email
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <span
                            class="input-group-text bg-transparent"
                            id="inputGroup-sizing-default"
                            >Email</span
                        >
                        <input
                            id="invitation-email"
                            type="email"
                            placeholder="user@example.com"
                            class="form-control"
                            aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default"
                            v-model="email"
                        />
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label
                                class="input-group-text bg-transparent"
                                for="inputGroupSelect01"
                                >Role</label
                            >
                        </div>

                        <select
                            class="form-select form-select-sm"
                            aria-label=".form-select-sm example"
                            v-model="roleId"
                        >
                            <!-- <option selected>Select User Role</option> -->
                            <option
                                v-for="role in roles"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        v-on:click="sendInvitation"
                        data-bs-dismiss="modal"
                    >
                        Send Invite
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { notify } from "@/util.js";
export default {
    props: ["roles"],
    data() {
        return {
            roleId: null,
            email: null,
        };
    },
    methods: {
        sendInvitation(e) {
            console.log(e);
            this.$emit("sendInvite", this.email, this.roleId);
        },
    },
};
</script>
