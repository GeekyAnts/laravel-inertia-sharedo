<template>
  <div class="main bg-white px-4 py-4 w-screen md:w-full font-sans text-sm">
    <!-- <flashmessage></flashmessage> -->

    <!-- Invitation FORM -->
    <div>
      <form
        @submit.prevent="addNewUser"
        @keypress.enter.prevent
        class="flex p-2 flex-row space-x-5 space-y-0"
      >
        <span class="font-bold text-base text-center pt-4">To:</span>

        <div class="w-7/12">
          <tag
            v-on:enableInvite="enableInvite"
            ref="children"
            :findUser="findUser"
            v-on:tagsEmpty="tagsEmpty"
          ></tag>
        </div>

        <span class="flex flex-grow flex-row justify-between">
          <span class="flex justify-end pt-4">
            <access ref="access"></access>
          </span>
          <button
            type="submit"
            tabindex="0"
            @keyup.enter="addNewUser"
            :disabled="!showInvite"
            :class="showInvite ? 'btn-invite' : 'bg-blue-200'"
            class="focus:outline-none mt-2 focus:ring focus:border-blue-300 rounded text-sm focus:outline-none text-white py-1 h-10 px-16"
          >
            Invite
          </button>
        </span>
      </form>
    </div>

    <!-- INVITE LINK -->
    <!-- <invitelink></invitelink> -->

    <!-- INVITED USERS  -->

    <users
      :users="usersLocal"
      :usersAbilties="usersAbilities"
      :entity="entityLocal"
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
    access: Access,
  },
  data() {
    return {
      usersAbilities: [],
      showInvite: false,
      value: {
        ability: "Can View",
        value: "read",
      },
      options: [
        {
          ability: "Can View",
          value: "read",
        },
        {
          ability: "Can Edit",
          value: "write",
        },
      ],
      entityLocal: { ...this.entity },
      usersLocal: [...this.users],
    };
  },
  props: {
    entity: {},
    users: Array,
    findUser: Boolean,
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
        entity_id: this.entityLocal.id,
        entity_name: this.entityLocal.entity_name,
      };
      this.email = "";
      this.tagsEmpty();
      this.$refs.children.oldValues = [];
      this.$refs.children.tagValue = [];
      this.handleSubmit(formData);
    },
    updateAccessForUser: function (data) {
      this.handleSubmit(data);
    },
    tagsEmpty() {
      this.showInvite = false;
    },
    generateUsersAbilities() {
      this.usersLocal.forEach((user, idx) => {
        if (user.ability === "Can View")
          this.usersAbilities.push({
            ability: "Can View",
            value: "read",
          });
        else if (user.ability == "Can Edit")
          this.usersAbilities.push({
            ability: "Can Edit",
            value: "write",
          });
      });
    },
    handleSubmit(formData) {
      this.$inertia.post("/share-dialog", formData, {
        onSuccess: () => {
          this.usersAbilities.length = 0;
          this.generateUsersAbilities();
        },
      });
    },
  },
  watch: {
    users: function (users) {
      this.users = users;
      this.usersLocal = users;
    },
  },
};
</script>
<style>
.to {
  color: rgb(75, 75, 75);
}
.btn-invite {
  background-color: #318dd0;
}
.btn-invite:hover {
  background-color: #4682bf;
}
</style>
