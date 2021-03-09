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
  transform: none;
  right: -7px !important;
}
/deep/ .multiselect__content-wrapper {
  width: 150px !important;
  margin-top: 40px;
  border-radius: 5px;
  max-height: 400px !important;
  z-index: 999;
  left: 5px;
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
  font-size: 16px;
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
