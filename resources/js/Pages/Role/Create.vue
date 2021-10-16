
<template>
    <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
        <!-- <div class="d-flex flex-row justify-content-between">
            <h2 class="fw-light mb-4">Account Roles</h2>
            <button
                v-on:click="toggleInviteModal"
                type="button"
                class="btn btn-success invite-button"
            >
                Create
            </button>
        </div> -->

        <div class="card mb-3 col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <!-- <img class="card-img-top" src="" alt="Card image cap" /> -->
            <div class="card-body">
                <h5 class="card-title">
                    {{ role ? "Update Role" : "Create New Role" }}
                </h5>
                <div class="card-text">
                    <div class="input-group input-group-sm mb-3">
                        <span
                            class="input-group-text bg-transparent"
                            id="inputGroup-sizing-sm"
                            >Role Name</span
                        >
                        <input
                            type="text"
                            class="form-control"
                            aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-sm"
                            v-model="form.name"
                            required
                            id="role-name"
                            ref="role-name"
                        />
                        <div class="invalid-feedback">
                            Role name is required
                        </div>
                    </div>
                </div>
                <div class="card-text">
                    <div class="row m-0">
                        <div
                            v-for="perm in permissions"
                            :key="perm.id"
                            class="form-check col-xl-4 col-md-4 col-sm-4 col-6"
                        >
                            <input
                                class="form-check-input"
                                type="checkbox"
                                :value="perm.id"
                                :id="perm.slug"
                                v-model="form.permissions"
                            />

                            <label class="form-check-label" :for="perm.slug">
                                {{ perm.name }}
                            </label>
                        </div>
                    </div>
                </div>
                <button
                    v-on:click="createRole"
                    type="button"
                    class="btn btn-sm btn-success invite-button mt-3"
                >
                    {{ role ? "Update" : "Create" }}
                </button>
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
    props: ["role", "permissions", "added_permissions"],
    layout: MainLayout,
    data() {
        return {
            form: {
                permissions: this.added_permissions
                    ? this.added_permissions
                    : [],
                name: this.role ? this.role.name : null,
            },
        };
    },
    methods: {
        selectPerm(id) {
            //in here you can check what ever condition  before append to array.
            if (this.form.permissions.includes(id)) {
                this.form.permissions = _.without(this.form.permissions, id);
            } else {
                this.form.permissions.push(id);
            }
        },
        createRole() {
            console.log(this.form);
            // if (this.role && role.id) {
            //     this.form.id = role.id;
            // }
            if (!this.$refs["role-name"].checkValidity()) {
                this.$refs["role-name"].classList.add("is-invalid");
            } else this.$inertia.post(route("roles.store"), this.form);
        },
    },
};
</script>

<style>
</style>
