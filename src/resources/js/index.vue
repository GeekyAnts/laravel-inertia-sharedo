<template>
  <div class="main h-screen p-4 w-screen">
    <div
      v-if="$page.props.flash.success && show"
      class="flex items-center justify-between bg-green-500 rounded max-w-3xl"
    >
      <div class="flex items-center">
        <svg
          class="ml-4 mr-2 flex-shrink-0 w-4 h-4 fill-white"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
        </svg>
        <div class="py-3 text-white text-sm font-medium">
          {{ $page.props.flash.success }}
        </div>
      </div>
      <button type="button" class="group mr-2 p-2" @click="show = false">
        <svg
          class="block w-2 h-2 fill-green-800 group-hover:fill-white"
          xmlns="http://www.w3.org/2000/svg"
          width="235.908"
          height="235.908"
          viewBox="278.046 126.846 235.908 235.908"
        >
          <path
            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
          />
        </svg>
      </button>
    </div>
    <div
      v-if="Object.keys($page.props.errors).length > 0 && show"
      class="mb-8 flex items-center justify-between bg-red-500 p-3 text-white rounded max-w-3xl"
    >
      <ul>
        <li v-for="error in $page.props.errors" :key="error">
          {{ error }}
        </li>
      </ul>
      <button type="button" class="group mr-2 p-2" @click="show = false">
        <svg
          class="block w-2 h-2 fill-green-800 group-hover:fill-white"
          xmlns="http://www.w3.org/2000/svg"
          width="235.908"
          height="235.908"
          viewBox="278.046 126.846 235.908 235.908"
        >
          <path
            d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
          />
        </svg>
      </button>
    </div>
    <div>
      <form
        @submit.prevent="handleSubmit"
        @keypress.enter.prevent
        class="flex flex-col space-y-5 p-3 border-b border-gray-300 md:flex-row md:space-x-5 md:space-y-0"
      >
        <span class="text-lg font-bold">To:</span>
        <span class="flex flex-col space-y-2">
          <div
            v-for="(find, index) in finds"
            v-bind:key="index"
            class="flex bg-gray-300 justify-between p-1 w-full md:w-48"
          >
            <span class="inline overflow-ellipsis overflow-hidden ... w-3/4">
              {{ find.email }}
            </span>
            <button
              type="button"
              v-on:click="deleteUser(find)"
              class="inline hover:bg-red-400"
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
            class="border-none focus:outline-none"
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
        <span v-if="showInvite">
          <multiselect
            v-model="value"
            track-by="ability"
            label="ability"
            placeholder="Select one"
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
            class="bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 px-16 rounded"
          >
            Invite
          </button>
        </span>
      </form>
    </div>
    <div
      class="flex p-4 flex-col space-y-5 border-b border-gray-300 md:flex-row md:space-x-5 md:space-y-0"
    >
      <span class="flex items-center space-x-2">
        <span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z"
              clip-rule="evenodd"
            />
          </svg>
        </span>
        <span class="text-bg-gray-500"> Invite through Link </span>
      </span>
      <span class="flex items-center">
        <multiselect
          v-model="inviteLink"
          track-by="ability"
          label="ability"
          placeholder="Select one"
          :options="options"
          :searchable="false"
          :show-labels="false"
          :allow-empty="false"
          class="fixed"
        >
        </multiselect>
      </span>
      <span>
        <button
          class="bg-blue-700 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded"
        >
          Copy Invite Link
        </button>
      </span>
    </div>
    <users :users="users" :entity="entity" v-on:alertClose="alertClose"></users>
  </div>
</template>
<script>
import Layout from "./Layout";
import Multiselect from "vue-multiselect";
import Users from "./Users";
export default {
  components: {
    Layout,
    Multiselect,
    users: Users,
  },
  data() {
    return {
      form: {
        email: null,
      },
      email: "",
      finds: [],
      validEmail: false,
      show: true,
      showEnterValidEmail: false,
      showInvite: false,
      inviteLink: {
        ability: "Read",
      },
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
    this.show = false;
  },
  methods: {
    alertClose: function () {
      setTimeout(() => {
        this.show = false;
      }, 4000);
    },
    handleDelete() {
      if (this.email == "") {
        this.finds.pop();
      }
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
    handleSubmit(e) {
      const formData = {
        emails: this.finds,
        ability: this.value.value,
        entity_id: this.entity.id,
        entity_name: this.entity.entity_name,
      };
      this.email = "";
      this.showInvite = false;
      this.$inertia.post("/share-dialog", formData, {
        onSuccess: () => {
          this.alertOpen = true;
          this.alertClose();
        },
      });
      this.finds.length = 0;
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
    "$page.flash": {
      handler() {
        this.show = true;
      },
      deep: true,
    },
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