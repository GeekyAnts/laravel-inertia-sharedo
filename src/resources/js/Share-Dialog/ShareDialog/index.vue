<template>
  <div class="main h-screen px-1 py-2 w-screen text-sm text-gray-600">
    <!-- success and error mesaage" -->
    <flashmessage></flashmessage>

    <!-- Invitation FORM -->
    <div>
      <form
        @submit.prevent="addNewUser"
        @keypress.enter.prevent
        class="flex p-3 border-b border-gray-300 space-x-3"
      >
        <span class="text-lg font-bold">To:</span>
        <span class="flex flex-col space-y-2 w-1/3">
          <div
            v-for="(find, index) in finds"
            v-bind:key="index"
            class="flex bg-gray-300 justify-between p-1 full"
          >
            <span class="inline overflow-ellipsis overflow-hidden ... w-36">
              {{ find.email }}
            </span>
            <button
              type="button"
              v-on:click="deleteUser(find)"
              class="inline hover:bg-red-300 focus:outline-none"
            >
              <svg
                class="h-4 w-4"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
          <input
            type="text"
            id="input-email"
            placeholder="Enter Emails"
            name="email[]"
            @input="onInputChange"
            @change="onChange"
            @keyup.delete="handleDelete()"
            v-model="email"
            @keyup.enter="createUser"
            class="border-none focus:outline-none pt-1"
          />
          <div class="w-full">
            <div
              v-if="validEmail"
              class="absolute z-40 bg-blue-400 p-3 text-white hover:bg-blue-700"
            >
              <button v-on:click="createUser()">Create {{ this.email }}</button>
            </div>
            <div
              v-else-if="showEnterValidEmail"
              class="absolute z-40 p-3 w-56 bg-white border-2 border-gray-300 p-2 color-gray-300 flex items-center justify-center"
            >
              Enter valid email
            </div>
          </div>
        </span>
        <span class="flex flex-row justify-between flex-1" v-if="showInvite">
          <span v-if="showInvite">
            <multiselect
              v-model="value"
              track-by="ability"
              label="ability"
              :options="options"
              :searchable="false"
              :show-labels="false"
              :allow-empty="false"
            >
            </multiselect>
          </span>
          <span v-if="showInvite">
            <button
              type="submit"
              class="bg-purple-600 hover:bg-purple-500 rounded text-sm focus:outline-none text-white py-2 px-12"
            >
              Invite
            </button>
          </span>
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
import Users from "./Users";
export default {
  components: {
    Layout,
    Multiselect,
    users: Users,
    invitelink: inviteLink,
    flashmessage: FlashMessage,
  },
  data() {
    return {
      form: {
        email: null,
      },
      email: "",
      usersAbilities: [],
      finds: [],
      validEmail: false,
      showEnterValidEmail: false,
      showInvite: false,
      value: {
        ability: "Read",
        value: "read",
      },
      options: [
        {
          ability: "Read",
          value: "read",
        },
        {
          ability: "Can Edit",
          value: "write",
        },
      ],
      alertOpen: false,
    };
  },
  props: {
    entity: {},
    users: Array,
  },
  mounted() {
    this.generateUsersAbilities();
  },
  methods: {
    handleDelete() {
      if (this.email == "") {
        this.finds.pop();
      }
    },
    addNewUser() {
      const formData = {
        emails: this.finds,
        ability: this.value.value,
        entity_id: this.entity.id,
        entity_name: this.entity.entity_name,
      };
      this.email = "";
      this.showInvite = false;
      this.handleSubmit(formData);
      this.finds.length = 0;
    },
    updateAccessForUser: function (data) {
      this.handleSubmit(data);
    },
    addFind: function () {
      this.finds.push({
        email: "",
      });
    },
    onChange: function () {
      this.showEnterValidEmail = false;
    },
    onInputChange: function () {
      if (!this.validEmail) this.showEnterValidEmail = true;
    },
    closeAlert: function () {
      this.alertOpen = false;
    },
    createUser: function () {
      if (!this.validEmail) return;
      const data = {
        email: this.email,
      };
      this.validEmail = false;
      this.finds.push({
        email: this.email,
      });
      this.showEnterValidEmail = false;
      this.showInvite = true;
      this.email = "";
    },
    deleteUser: function (find) {
      const userIndex = this.finds.indexOf(find);
      this.finds.splice(userIndex, 1);
      if (!this.finds.length) this.showInvite = false;
    },
    generateUsersAbilities() {
      this.users.forEach((user, idx) => {
        if (user.ability === "Read")
          this.usersAbilities.push({ ability: "Read", value: "read" });
        else if (user.ability == "Can Edit")
          this.usersAbilities.push({ ability: "Can Edit", value: "write" });
      });
    },
    handleSubmit(formData) {
      this.$inertia.post("/share-dialog", formData, {
        onSuccess: () => {
          this.usersAbilities.length = 0;
          this.generateUsersAbilities();
          this.alertOpen = true;
        },
      });
    },
    validateEmail(value) {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
        this.validEmail = true;
      } else {
        this.validEmail = false;
      }
    },
  },
  watch: {
    email(value) {
      this.email = value;
      this.validateEmail(value);
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
#input-email:focus {
  outline: none !important;
  box-shadow: none !important;
}
.multiselect__option--highlight {
  background: #d3eeff !important;
  color: black !important;
}
.multiselect__option {
  font-size: 12px;
  color: black;
  line-height: 8px;
  min-height: 0px !important;
}
.multiselect__option:hover {
  color: black !important;
}
.multiselect {
  min-height: 0px !important;
  position: none;
}
.multiselect__tag {
  position: static;
  font-size: 4rem !important;
  border: none;
  background: #fff;
}
.multiselect__single {
  min-height: 0px !important;
  line-height: unset !important;
  color: black;
  display: unset !important;
  font-weight: bolder;
}
.multiselect__select {
  height: 24px !important;
  line-height: unset !important;
}
.multiselect__tags {
  font-size: 12.5px;
  border: none;
  min-height: 0px !important;
  line-height: unset !important;
  padding: 3px 40px 2px 2px !important;
  background: #fff;
}
.multiselect__tags:hover {
  background-color: rgba(128, 128, 128, 0.2);
}
.mutliselect__select:hover::before .multiselect__tags {
  background-color: rgba(128, 128, 128, 0.2);
}
.multiselect__single {
  font-size: 14.4px;
  color: #4b4b4b;
}
.multiselect__tags:hover .multiselect__single {
  padding-left: 5px;
  margin-bottom: 8px;
  background-color: inherit !important;
  border-color: none;
  background: none !important;

  border: 0 !important;
  transition: none;
}
.multiselect__single:hover {
  padding-left: 5px;
  margin-bottom: 8px;
  background-color: inherit !important;
  border: none !important;
  transition: none;
}
</style>