<template>
  <div class="main bg-white py-8 px-8 w-screen md:w-full font-sans text-sm">
    <!-- <flashmessage></flashmessage> -->

    <!-- Invitation FORM -->

    <!-- <tag ref="children" :findUser="findUser"></tag> -->

    <div>
      <form
        @submit.prevent="addNewUser"
        @keypress.enter.prevent
        class="flex flex-row space-x-5 space-y-0 border-b border-gray-200"
      >
        <div class="w-8/12 flex">
          <span class="flex mt-3">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="h-5 w-5 transform rotate-45 stroke-current stroke-3 text-black"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 10l7-7m0 0l7 7m-7-7v18"
              />
            </svg>
          </span>
          <tag
            v-on:enableInvite="enableInvite"
            ref="children"
            :findUser="findUser"
            v-on:tagsEmpty="tagsEmpty"
            v-on:emailChanged="emailChanged"
          ></tag>
        </div>
        <span class="flex flex-grow flex-row justify-between">
          <span class="flex justify-end pt-3">
            <access ref="access"></access>
          </span>
          <button
            type="submit"
            @keyup.enter="addNewUser"
            :disabled="!showInvite || this.checkInvalidEmailExists"
            tabindex="0"
            class="h-12 cursor-default focus:outline-none"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              class="stroke-current h-7 w-7 -mt-2 focus:ring focus:border-blue-300"
              :class="
                showInvite && !checkInvalidEmailExists
                  ? 'text-blue-500 cursor-pointer'
                  : 'text-blue-200 cursor-default'
              "
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </button>
        </span>
      </form>
    </div>

    <div
      v-if="checkInvalidEmailExists"
      class="rounded-md p-3 mt-3 bg-red-100 w-full text-gray-600"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        class="h-5 w-5 inline-block stroke-current text-red-500"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
      <span v-if="invalidEmails.length === 1">
        <span class="font-bold text-black">{{ invalidEmails[0] }}</span>
        is an invalid email address. Please check it for typos and enter a new
        one.
      </span>
      <span v-else-if="invalidEmails.length === 2">
        <span class="font-bold text-black">
          {{ invalidEmails[0] }} & {{ invalidEmails[1] }}</span
        >
        are invalid email addresses. Please check it for typos and enter a new
        one.
      </span>
      <span v-else>
        You have entered
        <span class="font-bold text-black">{{ invalidEmails.length }}</span>
        invalid email addresses. Please check it for typos and enter a new one.
      </span>
    </div>

    <div
      class="rounded-md p-3 mt-3 bg-blue-100 w-full text-gray-600"
      v-if="!checkInvalidEmailExists && tagValue.length !== 0"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        class="h-5 w-5 inline-block stroke-current text-blue-500"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>

      <span class="font-bold text-black">
        <span v-if="tagValue.length === 1">{{ tagValue[0].email }}</span>
        <span v-else-if="tagValue.length === 2">
          {{ tagValue[0].email }}, {{ tagValue[1].email }}</span
        >
        <span v-else>{{ tagValue.length }} members </span>
      </span>
      will be added to your team and will be able to access your entity.
    </div>

    <!-- INVITE LINK -->
    <!-- <invitelink :entity="entity"></invitelink> -->

    <!-- INVITED USERS  -->

    <users
      :users="usersLocal"
      :usersAbilties="usersAbilities"
      :entity="entityLocal"
      :modelOwner="modelOwner"
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
import InviteLink from "../../../../packages/share-dialog/src/resources/js/Share-Dialog/ShareDialog/InviteLink.vue";
export default {
  components: {
    Layout,
    Multiselect,
    users: Users,
    invitelink: inviteLink,
    flashmessage: FlashMessage,
    tag: Tag,
    access: Access,
    InviteLink,
  },
  data() {
    return {
      showInvite: false,
      invalidEmails: [],
      tagValue: [],
      checkInvalidEmailExists: false,
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
    usersAbilities: {},
    modelOwner: {},
  },
  methods: {
    emailChanged(tagValue, invalidEmails, checkInvalidEmailExists) {
      this.tagValue = tagValue;
      this.invalidEmails = invalidEmails;
      this.checkInvalidEmailExists = checkInvalidEmailExists;
    },
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
    handleSubmit(formData) {
      this.$inertia.post("/share-dialog", formData);
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
