<template>
  <div>
    <div
      v-for="(user, index) in users"
      :key="index"
      class="py-2 mt-1 flex space-x-4"
    >
      <div class="flex items-center">
        <div
          :class="user.ability === 'Is Owner' ? 'bg-green-500' : 'bg-pink-500'"
          class="inline-block flex items-center justify-center text-white h-9 w-9 rounded-full ring-2 ring-white"
        >
          {{ user.name.charAt(0).toUpperCase() }}
        </div>
      </div>

      <div class="flex flex-col w-1/4">
        <div class="text-sm">{{ user.name }}</div>
        <div class="text-sm h-full color-75">
          {{ user.email }}
        </div>
      </div>
      <div
        v-if="user.ability == 'Is Owner'"
        class="pl-4 text-sm flex items-center"
        style="color: #4262ff"
      >
        {{ user.ability }}
      </div>
      <div v-else class="ml-1 w-28 flex items-center">
        <multiselect
          v-model="usersAbilties[user.id]"
          :id="user.email"
          track-by="value"
          label="ability"
          @select="onSelect"
          placeholder="Select one"
          :options="options"
          :searchable="false"
          :show-labels="false"
          :allow-empty="false"
          :max-height="500"
          open-direction="bottom"
        >
        </multiselect>
      </div>
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
    usersAbilties: {},
  },
  data() {
    return {
      options: [
        {
          ability: "Can View",
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
      let userIndex;
      this.users.forEach((user, idx) => {
        if (user.email === index) {
          userIndex = idx;
        }
      });
      this.users[userIndex].ability = selectedOption.value;
      this.$emit("updateAccess", data);
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
/deep/ ul > li:nth-child(3) span span {
  color: red;
}
/deep/ .color-75 {
  color: rgba(95, 92, 128, 1);
}
/deep/ .multiselect__option--highlight {
  background: rgba(0, 0, 0, 0.04) !important;
  color: #4262ff !important;
}

/deep/ .multiselect__option {
  color: rgb(55, 52, 97);
  line-height: 8px;
  font-weight: 400;
  font-size: 14px !important;
  min-height: 0px !important;
  padding: 20px 40px 20px 15px;
}

/deep/ .multiselect {
  min-height: 0px !important;
  position: none;
  right: 12px !important;
}
/deep/ .multiselect__tag {
  position: static;
  font-size: 4rem !important;
  border: none;
  background: #fff;
}
/deep/ .multiselect__single {
  min-height: 0px !important;
  line-height: unset !important;
  color: black;
  font-size: 14px !important;
  display: unset !important;
}

/deep/ .multiselect__select {
  height: 17px !important;
  line-height: unset !important;
  right: -7px !important;
}
/deep/ .multiselect__content-wrapper {
  width: 150px !important;
  margin-top: 40px;
  border-radius: 5px;
  max-height: 400px !important;
  z-index: 999;
  left: 20px;
  box-shadow: 0 8px 16px 0 rgba(5, 0, 56, 0.12);
}

/deep/ .multiselect__tags {
  font-size: 12.5px;
  border: none;
  min-height: 0px !important;
  line-height: unset !important;
  padding: 0px 30px 0px 0px !important;
  background: #fff;
  float: right;
}
/deep/ .multiselect__tags:hover {
  cursor: pointer;
}
/deep/ .multiselect__option--selected {
  font-weight: normal;
  background-color: white;
}

/deep/ .multiselect__select:hover ~ .multiselect__tags .multiselect__single {
  background-color: inherit !important;
  border-color: none;
  background: none !important;
}

/deep/ .multiselect__single {
  font-size: 14px;
  color: #4262ff;
}

/deep/ .multiselect__tags:hover .multiselect__single {
  padding-left: 5px;
  margin-bottom: 8px;
  background-color: inherit !important;
  border-color: none;
  background: none !important;
  border: 0 !important;
  transition: none;
}
/deep/ .multiselect__single:hover {
  padding-left: 5px;
  margin-bottom: 8px;
  background-color: inherit !important;
  border: none !important;
  transition: none;
}
/deep/ .multiselect--active .multiselect__select {
  transform: none;
}
</style>
