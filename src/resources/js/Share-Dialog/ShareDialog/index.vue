<template>
    <div
        class="main bg-white h-screen px-1 py-2 w-screen md:w-1/2 font-sans text-sm text-gray-600"
    >
        <!-- success and error mesaage" -->
        <!-- <flashmessage></flashmessage> -->

        <!-- Invitation FORM -->
        <div>
            <form
                @submit.prevent="addNewUser"
                @keypress.enter.prevent
                class="flex p-3 border-b border-gray-200 flex-row space-x-5 space-y-0"
            >
                <span class="font-bold text-base pt-1">To:</span>

                <div class="w-1/2">
                    <tag
                        v-on:enableInvite="enableInvite($event)"
                        ref="children"
                    ></tag>
                </div>

                <span
                    class="flex pt-1 flex-grow flex-row justify-between"
                    v-if="showInvite"
                >
                    <span v-if="showInvite">
                        <access ref="access"></access>
                    </span>
                    <!-- <span v-if="showInvite"> -->
                    <button
                        type="submit"
                        tabindex="0"
                        @keyup.enter="addNewUser"
                        class="focus:outline-none focus:ring focus:border-blue-300 bg-purple-600 hover:bg-purple-500 rounded text-sm focus:outline-none text-white h-10 px-16"
                    >
                        Invite
                    </button>
                    <!-- </span> -->
                </span>
            </form>
        </div>

        <!-- INVITE LINK -->
        <!-- <invitelink></invitelink> -->

        <!-- INVITED USERS  -->
        <users
            :users="users"
            :usersAbilties="usersAbilities"
            :entity="entity"
            v-on:updateAccess="updateAccessForUser($event)"
        ></users>
    </div>
</template>
<script>
import Layout from "./Layout";
import Multiselect from "vue-multiselect";
import inviteLink from "./InviteLink";
import FlashMessage from "./FlashMessage";
import Access from "./Access";
import Tag from "./Tag";
import Users from "./Users";
export default {
    components: {
        Layout,
        Multiselect,
        users: Users,
        invitelink: inviteLink,
        flashmessage: FlashMessage,
        tag: Tag,
        access: Access
    },
    data() {
        return {
            usersAbilities: [],
            showInvite: false,
            value: {
                ability: "Read",
                value: "read"
            },
            options: [
                {
                    ability: "Read",
                    value: "read"
                },
                {
                    ability: "Can Edit",
                    value: "write"
                }
            ]
        };
    },
    props: {
        entity: {},
        users: Array
    },
    mounted() {
        this.generateUsersAbilities();
    },
    methods: {
        enableInvite() {
            this.showInvite = true;
        },
        addNewUser(e) {
            e.preventDefault();
            const formData = {
                emails: this.$refs.children.tagValue,
                ability: this.$refs.access.value.value,
                entity_id: this.entity.id,
                entity_name: this.entity.entity_name
            };
            this.email = "";
            this.showInvite = false;
            this.handleSubmit(formData);
        },
        updateAccessForUser: function(data) {
            this.handleSubmit(data);
        },
        generateUsersAbilities() {
            this.users.forEach((user, idx) => {
                if (user.ability === "Read")
                    this.usersAbilities.push({
                        ability: "Read",
                        value: "read"
                    });
                else if (user.ability == "Can Edit")
                    this.usersAbilities.push({
                        ability: "Can Edit",
                        value: "write"
                    });
            });
        },
        handleSubmit(formData) {
            console.log("tag", this.tagValue);
            this.$refs.children.tagValue = null;
            this.showInvite = false;
            this.$inertia.post("/share-dialog", formData, {
                onSuccess: () => {
                    this.usersAbilities.length = 0;
                    this.$refs.children.tagValue = [];
                    this.generateUsersAbilities();
                }
            });
        }
    }
};
</script>
