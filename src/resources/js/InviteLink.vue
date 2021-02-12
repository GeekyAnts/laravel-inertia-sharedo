<template>
  <div class="flex p-4 border-b border-gray-300 justify-between">
    <span class="flex items-center space-x-1">
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
      <span> Invite through Link </span>
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
        class="bg-purple-600 hover:bg-purple-500 rounded text-sm focus:outline-none text-white py-2 px-5"
      >
        Copy Invite Link
      </button>
    </span>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
  },
  data() {
    return {
      inviteLink: {
        ability: "Read",
        value: "Read",
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
        {
          ability: "No Access",
          value: "no-access",
        },
      ],
    };
  },
};
</script>