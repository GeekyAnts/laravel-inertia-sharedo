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
/deep/ ul > li:nth-child(3) span span {
  color: red;
}
/deep/ ul > li:nth-child(3) .multiselect__option--highlight span {
  color: white !important;
}
/deep/ .color-75 {
  color: rgba(95, 92, 128, 1);
}
/deep/ .multiselect__option--highlight {
  background: #318dd0 !important;
  color: white !important;
}

/deep/ .multiselect__option {
  color: rgb(55, 52, 97);
  line-height: 8px;
  font-weight: 400;
  font-size: 14px !important;
  min-height: 0px !important;
  padding: 16px 40px 16px 15px;
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
  right: -4px !important;
  transform: none !important;
}
/deep/ .multiselect__content-wrapper {
  width: 150px !important;
  margin-top: 30px;
  border-radius: 5px;
  z-index: 999;
  max-height: 600px !important;
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

/deep/ .multiselect__single {
  font-size: 14px;
  font-weight: 600;
  color: #318dd0;
}

/deep/ .multiselect__select:before {
  border-color: #318dd0 transparent transparent transparent;
}
</style>