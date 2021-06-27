
<template>
    <div>
        <div class="d-flex flex-row justify-content-between">
            <h2 class="fw-light mb-4">Sent Invitations</h2>
            <button
                v-on:click="toggleInviteModal"
                type="button"
                class="btn btn-success invite-button"
            >
                Invite
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Sent at</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="invite in invitations" :key="invite.id">
                    <th scope="row">1</th>
                    <td>{{ invite.email }}</td>
                    <td>5th oct, 2021</td>
                    <td>
                        <button type="button" class="btn btn-primary resend">
                            Resend
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger delete-invite"
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
                                class="form-control"
                                aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default"
                            />
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
import Button from "../../Jetstream/Button.vue";
import Modal from "bootstrap/js/dist/modal";
import axios from "axios";
import { notify } from "@/util.js";
export default {
    components: { Button },
    props: ["invitations"],
    layout: MainLayout,
    mounted() {
        window.title = "Invitations";
    },
    methods: {
        toggleInviteModal(e) {
            if (this.deleteModal == null) {
                this.deleteModal = new Modal(this.$refs["invite-modal"], {
                    backdrop: "static",
                });
                console.log(this.deleteModal);
            }
            this.$refs["invitation-email"].value = "";
            this.deleteModal.toggle();
        },
        sendInvitation(e) {
            this.deleteModal.toggle;
            axios
                .post(route("invitations.send_invite"), {
                    email: this.$refs["invitation-email"].value,
                })
                .then((resp) => {
                    notify("Invitation send", "success");
                    this.$refs["invitation-email"].value = "";
                })
                .catch((err) => {
                    notify(
                        "Something went wrong. Please send invitation again",
                        "danger"
                    );
                    console.error(err);
                });
        },
    },
};
</script>

<style>
</style>
