
<template>
    <div class="col-6">
        <div class="d-flex flex-row justify-content-between">
            <h2 class="fw-light mb-4">Sent Invitations</h2>
            <button
                v-if="canInvite"
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
                    <th scope="col">Status</th>
                    <th v-if="canInvite || canDelete" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(invite, index) in invitations.data"
                    :key="invite.id"
                >
                    <th scope="row">1</th>
                    <td>{{ invite.email }}</td>
                    <td>{{ genSentAt(invite.updated_at) }}</td>
                    <td>{{ invite.is_accepted ? "accepted" : "pending" }}</td>
                    <td v-if="canInvite || canDelete">
                        <button
                            v-if="canInvite"
                            v-on:click="
                                sendInvitation(
                                    $event,
                                    invite.email,
                                    invite.role_id
                                )
                            "
                            type="button"
                            class="btn btn-primary resend"
                        >
                            Resend
                        </button>
                        <button
                            v-if="canDelete"
                            type="button"
                            class="btn btn-danger delete-invite"
                            v-on:click="deleteInvite(invite.id, index)"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <send-invitation-modal
            ref="invite-modal"
            :roles="roles"
            @sendInvite="sendInvitation"
        ></send-invitation-modal>
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

import SendInvitationModal from "./SendInvitationModal.vue";
import { inject } from "@vue/runtime-core";
export default {
    components: { SendInvitationModal },
    props: ["invitations", "roles"],

    layout: MainLayout,
    data() {
        return { deleteModal: null };
    },

    setup() {
        const authorize = inject("authorize");
        const canInvite = authorize("invitations.create");
        const canDelete = authorize("invitations.delete");
        return {
            canInvite,
            canDelete,
        };
    },

    methods: {
        resendInvite(e) {},
        sendInvitation(email, id) {
            console.log(email);
            this.$emit("hide-model");
            if (this.deleteModal) {
                this.deleteModal.hide();
            }

            axios
                .post(route("invitations.send_invite"), {
                    email: email,
                    roleId: id,
                })
                .then((resp) => {
                    notify("Invitation sent", "success");
                    this.email = "";
                })
                .catch((err) => {
                    notify(
                        "Something went wrong. Please send invitation again",
                        "danger"
                    );
                    console.error(err);
                });
        },
        toggleInviteModal(e) {
            console.log(e);

            if (this.inviteModal == null) {
                let inviteModalComp = document.querySelector("#invite-modal");
                this.inviteModal = new Modal(inviteModalComp, {
                    backdrop: "static",
                });
                console.log(this.inviteModal);
            }
            // inviteModalComp.querySelector("#invitation-email").value = "";
            this.inviteModal.toggle();
        },

        /**Generates YYYY-MM-DD date format from mysql datetime */
        genSentAt(dateTime) {
            let dateObject = new Date(dateTime);
            let yyyy = dateObject.getFullYear();
            let mm = dateObject.getMonth() + 1;
            let dd = dateObject.getDate();

            return `${yyyy}-${mm}-${dd}`;
        },
        /**Deletes an invite if not accepted */
        deleteInvite(id, index) {
            let invite = this.invitations.data[index];
            console.log(this.invitations);
            this.invitations.data.splice(index, 1);
            axios
                .post(route("invitations.delete_invite", { id: id }))
                .then((resp) => {
                    notify("Invitation Deleted", "success");
                })
                .catch((err) => {
                    notify("Something went wrong", "danger");
                    this.invitations.data.splice(index, 0, invite);
                });
        },
    },
};
</script>

<style>
</style>
