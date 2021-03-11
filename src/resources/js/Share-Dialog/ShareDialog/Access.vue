<template>
  <multiselect
    v-model="value"
    track-by="ability"
    label="ability"
    :options="options"
    :searchable="false"
    :show-labels="false"
    :allow-empty="false"
    openDirection="bottom"
  >
  </multiselect>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
  },
  data() {
    return {
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
    };
  },
  methods: {
    onSelect: function (selectedOption, index) {
      const emails = [];
      emails.push({
        email: this.users[index].email,
      });
      const data = {
        ability: selectedOption.value,
        emails: emails,
        entity_id: this.entity.id,
        entity_name: this.entity.entity_name,
      };
      this.users[index].ability = selectedOption.value;
      this.$emit("updateAccess", data);
    },
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>
/deep/ .multiselect__tags {
  display: flex;
  align-items: center;
  height: 48px;
  width: 118px;
  background-color: #e3e3e3;
}
/deep/ .multiselect__single {
  background-color: #e3e3e3 !important;
  color: black;
  font-size: 14px;
  font-weight: 500;
}
/deep/ .multiselect__select {
  top: 4px;
}
/deep/ .multiselect__content-wrapper {
  margin-top: 10px;
  border-radius: 5px;
  max-height: 400px !important;
  box-shadow: 0 8px 16px 0 rgba(5, 0, 56, 0.12);
}
/deep/ .multiselect__option {
  height: 48px;
  display: flex;
  align-items: center;
  font-weight: 500;
  font-size: 14px;
}
/deep/ .multiselect__option--highlight {
  background: #318dd0 !important;
}
/deep/ .multiselect__option--selected {
  font-weight: 500;
}

/*icon */
/deep/ .multiselect__select:before {
  color: red;
}
/deep/ .multiselect__select:active::before {
  color: red;
}
/deep/ .multiselect__select {
  transform: none !important;
}
/deep/ .multiselect__select:before {
  border-color: black transparent transparent transparent;
}
</style>