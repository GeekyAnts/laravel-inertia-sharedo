<template>
  <div>
    <multiselect
      v-model="tagValue"
      tag-placeholder=""
      :placeholder="!this.tagValue.length ? 'Enter Emails' : ''"
      label="email"
      @search-change="handleTagChange"
      track-by="email"
      @select="selectOption"
      :options="tagOptions"
      :multiple="true"
      :taggable="true"
      @tag="addTag"
      @remove="removeTag"
      ref="email"
      :hideSelected="true"
      selectLabel=""
    >
      <template slot="noResult">
        <div class="hidden"></div>
      </template>
      <template slot="noOptions"><div class="hidden"></div></template>
      <template slot="beforeList">
        <div v-if="!validEmail" class="text-sm text-right text-red-500 p-1">
          Enter Valid Email
        </div>
      </template>
      <template slot="option" slot-scope="props">
        <div
          v-if="!validEmail && props.option.label"
          class="option-label p-6 hidden"
        >
          Enter Valid Email
        </div>
        <div class="btn-create p-6" v-if="validEmail && props.option.label">
          <div
            v-if="validEmail && props.option.label"
            class="flex items-center justify-center"
          >
            Create "{{ props.option.label }}"
          </div>
        </div>
        <div v-if="props.option.email" class="p-6">
          <span>{{ props.option.email }}</span>
        </div>
      </template>
    </multiselect>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import axios from "axios";
export default {
  components: {
    Multiselect,
  },
  props: {
    findUser: Boolean,
  },
  data() {
    return {
      tagValue: [],
      oldValues: [],
      tagOptions: [],
      validEmail: false,
      tags: "",
      isLoading: false,
    };
  },
  updated() {
    this.tagValue = this.oldValues;
    if (this.oldValues.length === 0) {
      this.$emit("tagsEmpty");
    }
  },
  methods: {
    addTag(newTag) {
      if (!this.validEmail) {
        this.$refs.email.$el.focus();
        return;
      }
      const tag = {
        email: newTag,
      };
      this.$refs.email.$el.focus();
      this.oldValues.push(tag);
      this.validEmail = false;
      // this.tagValue.push(tag);
      this.$emit("enableInvite");
    },
    selectOption(selectedOption, id) {
      this.oldValues.push(selectedOption);
      this.$refs.email.$el.focus();
      this.$emit("enableInvite");
    },
    removeTag(removedOption, id) {
      const index = this.oldValues.indexOf(removedOption);
      if (index > -1) {
        this.oldValues.splice(index, 1);
      }
    },
    handleTagChange(searchQuery, id) {
      if (!searchQuery) return;
      this.isLoading = true;
      this.validateEmail(searchQuery);
      if (!this.findUser) return;
      if (this.timeout) clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        axios
          .get(`/share-dialog/searchUsers/${searchQuery}`)
          .then((response) => {
            this.isLoading = false;
            this.tagOptions = response.data.searchUsers;
          });
      }, 300); // delay
    },
    validateEmail(value) {
      let emailExists = false;
      this.tagValue.forEach((object, i) => {
        if (object.email === value) {
          emailExists = true;
        }
      });
      if (
        /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value) &&
        !emailExists
      ) {
        this.validEmail = true;
      } else {
        this.validEmail = false;
      }
    },
  },
};
</script>
<style scoped>
/deep/ .multiselect__tag {
  background-color: rgb(240, 240, 243, 1);
  color: #292555;
  padding: 8px 40px 8px 10px;
  border-radius: 16px;
  font-size: 14px;
}
/deep/ .multiselect__tag:hover {
  background-color: rgba(235, 235, 239, 1);
  cursor: default;
}
/deep/ .multiselect__tag-icon {
  cursor: pointer;
  margin-left: 7px;
  position: absolute;
  right: 9px;
  top: 2px;
  bottom: 0;
  font-weight: 300;
  font-style: normal;
  width: 22px;
  text-align: center;
  line-height: 22px;
  transition: all 0.2s ease;
  border-radius: 0px;
}
/deep/ .multiselect__tag-icon:after {
  color: rgb(75, 75, 75) !important;
  font-size: 24px;
}
/deep/ .multiselect__tags {
  font-size: 12.5px;
  border: none;
  min-height: 0px !important;
  line-height: unset !important;
  background: #fff;
  padding: 14px 0px 10px 10px;
  border: 1px solid rgb(240, 240, 240);
  vertical-align: middle !important;
  overflow: hidden;
}

/deep/ .multiselect__spinner {
  width: 21px !important;
  top: 8px !important;
  right: 1px !important;
}
/deep/ .multiselect__input {
  border: none;
  color: #050038;
  padding: 5px !important;
  width: 8rem !important;
  position: relative !important;
  font-size: 14px !important;
}
/deep/ .multiselect__select {
  display: none !important;
}

/deep/ .multiselect__option {
  padding: 0px !important;
  line-height: 0px !important;
  min-height: 0px !important;
  border: none;
}
/deep/ .multiselect__placeholder {
  display: none !important;
}
/deep/ ::placeholder {
  font-size: 16px !important;
  color: rgb(94, 92, 127);
}
</style>



<style scoped>
/deep/ .multiselect__option {
  background-color: white;
}
/deep/ .valid-email {
  background: #318dd0 !important;
}
/deep/ .multiselect__option--highlight:after {
  background: #318dd0 !important;
}
/deep/ .multiselect__option--highlight {
  background-color: #318dd0 !important;
}
/deep/ .btn-create:hover {
  background-color: #318dd0 !important;
  color: white !important;
}
/deep/ .btn-create {
  background-color: rgba(219, 234, 254, 1) !important;
  color: rgb(75, 75, 75) !important;
}

/deep/ .multiselect__tag-icon:hover {
  background-color: rgba(235, 235, 239, 1);
}
/deep/ .multiselect__content-wrapper {
  border-radius: 0.375rem;
  background: none;
  border: 0px !important;
}
</style>