<template>
    <div class="row gutters">
        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <form @submit.prevent="submit" class="needs-validation">
                    <div class="card-body">
                        <div class="row gutters">
                            <div
                                class="
                                    col-xl-12
                                    col-lg-12
                                    col-md-12
                                    col-sm-12
                                    col-12
                                "
                            >
                                <h5 class="mb-2 text-primary">
                                    Personal Details
                                </h5>
                            </div>
                            <div
                                class="
                                    col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12
                                "
                            >
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
                                            required
                                        />
                                        <!-- <input type="text" class="form-control" /> -->
                                    </div>
                                </div>
                            </div>
                            <div
                                class="
                                    col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12
                                "
                            >
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
                                            v-model="$page.props.user.email"
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div
                                class="
                                    col-xl-12
                                    col-lg-12
                                    col-md-12
                                    col-sm-12
                                    col-12
                                "
                            >
                                <h5 class="mt-3 mb-2 text-primary">
                                    Change Password
                                </h5>
                            </div>
                            <div class="row mb-2">
                                <div
                                    class="
                                        col-xl-6
                                        col-lg-6
                                        col-md-6
                                        col-sm-6
                                        col-12
                                    "
                                >
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input
                                                type="password"
                                                class="form-control"
                                                name="current_password"
                                                placeholder="Current Password"
                                                v-model="form.currentPassword"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div
                                    class="
                                        col-xl-6
                                        col-lg-6
                                        col-md-6
                                        col-sm-6
                                        col-12
                                    "
                                >
                                    <div class="form-group">
                                        <input
                                            type="password"
                                            class="form-control"
                                            :class="newPassInputClass"
                                            id="new_password"
                                            placeholder="New Password"
                                            required
                                            v-model="form.password"
                                            v-on:input="checkPassword"
                                            v-on:click="checkPassword"
                                            min="10"
                                        />
                                        <div
                                            v-if="showFeedback"
                                            class="valid-feedback"
                                        >
                                            <ul>
                                                <li
                                                    v-for="(
                                                        feedback, index
                                                    ) in genValidArray(
                                                        passwordValidFeedbacks
                                                    )"
                                                    :key="index"
                                                >
                                                    {{ feedback }}
                                                </li>
                                            </ul>
                                        </div>
                                        <div
                                            v-if="showFeedback"
                                            class="invalid-feedback"
                                        >
                                            <ul>
                                                <li
                                                    v-for="(
                                                        feedback, index
                                                    ) in genValidArray(
                                                        passwordInvalidFeedbacks
                                                    )"
                                                    :key="index"
                                                >
                                                    {{ feedback }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div
                                    class="
                                        col-xl-6
                                        col-lg-6
                                        col-md-6
                                        col-sm-6
                                        col-12
                                    "
                                >
                                    <div class="form-group">
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password_confirmation"
                                            placeholder="Confirm New Password"
                                            required
                                            :pattern="'^' + form.password + '$'"
                                            v-on:input="checkConfirmPassword"
                                            min="10"
                                            v-model="form.passwordConfirmation"
                                        />
                                        <div
                                            v-if="showFeedback"
                                            class="invalid-feedback"
                                        >
                                            <ul>
                                                <li>Passwords don't match</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gutters">
                            <div
                                class="
                                    col-xl-12
                                    col-lg-12
                                    col-md-12
                                    col-sm-12
                                    col-12
                                "
                            >
                                <div class="text-right">
                                    <button
                                        type="button"
                                        id="submit"
                                        name="submit"
                                        class="btn btn-outline-primary"
                                        v-on:click="submit"
                                    >
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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

.validation-icon {
    width: 1rem;
    height: 1rem;
    opacity: 1;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16'  class='bi bi-check2' style='color:--bs-danger;' viewBox='0 0 16 16'%3E%3Cpath d='M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
    background-size: contain;
}
.is-invalid .bi-x-lg {
    display: block;
}
</style>
<script>
import CheckIcon from "../../Icons/CheckIcon.vue";
import XIcon from "../../Icons/XIcon.vue";
import MainLayoutVue from "../../Layouts/MainLayout.vue";
import { addEventListener } from "@/util.js";
export default {
    components: { CheckIcon, XIcon },
    layout: MainLayoutVue,
    data() {
        return {
            form: {
                name: this.$page.props.user.name,
                currentPassword: null,
                password: null,
                passwordConfirmation: null,
            },
            passwordInvalidFeedbacks: [],
            passwordValidFeedbacks: [],
            showFeedback: false,
            passwordValidationRules: [
                {
                    re: /[a-z]/,
                    text: "Password should contain at-least one small letter",
                },
                {
                    re: /[A-Z]/,
                    text: "Password should contain at-least one Capital letter",
                },
                {
                    re: /[0-9]/,
                    text: "Password should contain atleast one number",
                },
                {
                    re: /[@$!%*#?&]/,
                    text: "Password should contain at least one of @$!%*#?&",
                },
                {
                    re: /^.{10,}$/,
                    text: "Password needs to be atleast 10 characters long",
                },
            ],
            rmListeners: [],
            newPassInputClass: null,
        };
    },
    computed: {},
    mounted() {
        this.rmListeners = [
            addEventListener(
                document.querySelector("#new_password"),
                "focusout",
                (e) => {
                    this.showFeedback = false;
                    // this.newPassInputClass = this.newPassInputClass.includes(
                    //     "is-valid"
                    // )
                    //     ? "is-valid"
                    //     : null;
                }
            ),
        ];
    },
    methods: {
        submit(e) {
            console.log(e);
            let form = document.querySelector(".needs-validation");
            if (this.form.currentPassword) {
                this.checkPassword(e);
                this.checkConfirmPassword({
                    target: form.querySelector("#password_confirmation"),
                });
            }
            console.log(sa);
            let isInvalid = form.querySelector("is-invalid");
            if (isInvalid) {
                this.showFeedback = true;
            } else {
                this.$inertia.post(route("account.update"), this.form);
            }

            //  document.querySelector(".needs-valdiation").querySelectorAll(':invalid')[0].classList.add('is-invalid')
            // console.log(
            //     document.querySelector(".needs-valdiation").checkValidity()
            // );
        },

        passwordOnClick() {},
        checkPassword(e) {
            console.log(e);
            // that = this;
            this.showFeedback = true;
            this.passwordValidationRules.forEach(
                function (rule, i) {
                    this.addToPasswordFeedback(rule.re, rule.text, i);
                }.bind(this)
            );
            this.setNewPassInputClass();
        },
        addToPasswordFeedback(re, validationText, i) {
            if (this.form.password && re.test(this.form.password)) {
                this.passwordValidFeedbacks[i] = validationText;
                this.passwordInvalidFeedbacks[i] = null;
            } else {
                this.passwordValidFeedbacks[i] = null;
                this.passwordInvalidFeedbacks[i] = validationText;
            }
        },

        genValidArray(arr) {
            let arr2 = [];
            arr.forEach(function (val) {
                if (val) {
                    arr2.push(val);
                }
            });
            return arr2;
        },
        setNewPassInputClass() {
            let klass = "";
            for (let i = 0; i < this.passwordInvalidFeedbacks.length; i++) {
                if (this.passwordInvalidFeedbacks[i]) {
                    klass += "is-invalid ";
                    break;
                }
            }
            for (let i = 0; i < this.passwordValidFeedbacks.length; i++) {
                if (this.passwordValidFeedbacks[i]) {
                    klass += "is-valid";
                    break;
                }
            }
            if (klass != this.newPassInputClass) this.newPassInputClass = klass;
        },

        checkConfirmPassword(e) {
            if (!e.target.checkValidity()) {
                console.log(e.target.classList);
                e.target.classList.remove("is-valid");
                if (!e.target.classList.contains("is-invalid")) {
                    e.target.classList.add("is-invalid");
                }
            } else {
                // if (!e.target.classList.contains("is-invalid")) {
                e.target.classList.remove("is-invalid");
                // }
                e.target.classList.add("is-valid");
            }
        },
    },
};
</script>
