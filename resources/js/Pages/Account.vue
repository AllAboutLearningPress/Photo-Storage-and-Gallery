<template>
    <div class="row gutters">
        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row gutters">
                        <div
                            class="
                                col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12
                            "
                        >
                            <h5 class="mb-2 text-primary">Personal Details</h5>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span
                                            class="
                                                input-group-text
                                                bg-transparent
                                            "
                                            id=""
                                            >Name</span
                                        >
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="form.name"
                                    />
                                    <!-- <input type="text" class="form-control" /> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span
                                            class="
                                                input-group-text
                                                bg-transparent
                                            "
                                            id=""
                                            >Email</span
                                        >
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="form.email"
                                    />
                                    <!-- <input type="text" class="form-control" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div
                            class="
                                col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12
                            "
                        >
                            <h5 class="mt-3 mb-2 text-primary">
                                Change Password
                            </h5>
                        </div>
                        <div class="row mb-2">
                            <div
                                class="
                                    col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12
                                "
                            >
                                <div class="form-group">
                                    <div class="input-group">
                                        <input
                                            type="password"
                                            class="form-control"
                                            placeholder="Current Password"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div
                                class="
                                    col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12
                                "
                            >
                                <div class="form-group">
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="ciTy"
                                        placeholder="New Password"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div
                                class="
                                    col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12
                                "
                            >
                                <div class="form-group">
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="confirm-new-password"
                                        placeholder="Confirm New Password"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gutters">
                        <div
                            class="
                                col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12
                            "
                        >
                            <div class="text-right">
                                <button
                                    type="button"
                                    id="submit"
                                    name="submit"
                                    class="btn btn-outline-primary"
                                >
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.form-control {
    /* border: 1px solid #cfd1d8; */
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    /* font-size: 0.825rem; */
    background: inherit;
    /* color: #2e323c; */
    color: white;
}

.card {
    background: inheri;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    border: 0;
    margin-bottom: 1rem;
}
</style>
<script>
import MainLayoutVue from "../Layouts/MainLayout.vue";
export default {
    layout: MainLayoutVue,
    data() {
        return {
            form: {
                name: this.$page.props.user.name,
                email: this.$page.props.user.email,
                password: null,
                passwordConfirmation: null,
            },
        };
    },
};
</script>
