<template>
  <div>
    <div
      v-for="(user, index) in users"
      :key="user.id"
      class="p-4 flex border-b border-gray-300 space-x-3"
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
              d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
              clip-rule="evenodd"
            />
          </svg>
        </span>
        <span>{{ user.email }}</span>
      </span>
      <span>
        <multiselect
          v-model="users[index]"
          :id="user.email"
          track-by="ability"
          label="ability"
          @select="onSelect"
          placeholder="Select one"
          :options="options"
          :searchable="false"
          :show-labels="false"
          :allow-empty="false"
        >
        </multiselect>
      </span>
    </div>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
  },
  props: {
    entity: {},
    users: Array,
  },
  data() {
    return {
      options: [
        {
          ability: "Read",
          value: "read",
        },
        {
          ability: "Can Edit",
          value: "write",
        },
        {
          ability: "Remove",
          value: "remove",
        },
      ],
      tags: [],
    };
  },
  methods: {
    onSelect: function (selectedOption, index) {
      const emails = [];
      emails.push({
        email: index,
      });
      const data = {
        ability: selectedOption.value,
        emails: emails,
        entity_id: this.entity.id,
        entity_name: this.entity.entity_name,
      };
      console.log(data);
      this.$inertia.post("/share-dialog", data, {
        onSuccess: () => {
          this.$emit("alertClose");
        },
      });
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
