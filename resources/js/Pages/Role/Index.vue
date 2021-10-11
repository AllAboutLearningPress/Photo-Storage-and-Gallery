
<template>
    <div class="col-lg-6 col-sm-12 col-md-12 col-12">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="fw-light mb-4">Account Roles</h2>
            <button
                v-on:click="$inertia.visit(route('roles.create'))"
                type="button"
                class="btn btn-success invite-button"
            >
                Create
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(role, index) in roles" :key="role.id">
                    <th scope="row">1</th>
                    <td>{{ role.name }}</td>

                    <td>
                        <button
                            v-on:click="
                                $inertia.visit(
                                    route('roles.show', { id: role.id })
                                )
                            "
                            type="button"
                            class="btn btn-sm btn-primary resend"
                        >
                            Edit
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm btn-danger delete-invite"
                            v-on:click="deleteRole(invite.id, index)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal -->
        <div
            class="modal fade"
            id="staticBackdrop"
            data-bs-backdrop="static"
            data-bs-keyboard="false"
            tabindex="-1"
            aria-labelledby="staticBackdropLabel"
            aria-hidden="true"
            ref="invite-modal"
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
                                ref="invitation-email"
                                type="email"
                                placeholder="user@example.com"
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default"
                            />
                        </div>
                        <select
                            class="form-select form-select-sm"
                            aria-label=".form-select-sm example"
                        >
                            <option selected>Select User Role</option>
                            <option
                                v-for="role in roles"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </select>
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
                        >
                            Send Invite
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.resend {
    width: 5rem;
}
.delete-invite {
    width: 5rem;
    @media (max-width: 575.98px) {
        margin-top: 0.5rem;
    }
    @media (min-width: 560px) {
        margin-left: 0.5rem;
    }
}
.invite-button {
    height: 2.5rem;
    width: 6rem;
}
</style>
<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import Modal from "bootstrap/js/dist/modal";
import axios from "axios";
import { notify } from "@/util.js";
export default {
    props: ["roles"],
    layout: MainLayout,
    data() {
        return {};
    },
};
</script>

<style>
</style>
