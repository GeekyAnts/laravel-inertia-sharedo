<template>
  <div class="w-full">
    <multiselect
      v-model="tagValue"
      tag-placeholder=""
      :placeholder="!this.tagValue.length ? 'Enter email or invite' : ''"
      label="email"
      @search-change="handleTagChange"
      track-by="email"
      @select="selectOption"
      :options="tagOptions"
      :multiple="true"
      :taggable="true"
      :closeOnSelect="false"
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
      <!-- <template slot="beforeList">
        <div
          v-if="!validEmail && this.tagOptions.length === 0"
          class="text-sm text-left text-red-500 mt-3"
        >
          Enter Valid Email
        </div>
      </template> -->
      <template slot="option" slot-scope="props">
        <div
          v-if="!validEmail && props.option.label"
          class="option-label p-6 hidden"
        >
          Enter Valid Email
        </div>
        <div
          class="btn-create p-6 mt-3 hidden"
          v-if="validEmail && props.option.label"
        >
          <div
            v-if="validEmail && props.option.label"
            class="flex items-center justify-center"
          >
            "{{ props.option.label }}"
          </div>
        </div>
        <div v-if="props.option.email" class="p-6">
          <span>{{ props.option.email }}</span>
        </div>
      </template>
    </multiselect>

    <!-- memebers dialog box -->
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import Access from "./Access";
import axios from "axios";
export default {
  components: {
    Multiselect,
    access: Access,
  },
  props: {
    findUser: Boolean,
    entity_id: "",
    entity_name: "",
  },
  data() {
    return {
      tagValue: [],
      oldValues: [],
      tagOptions: [],
      validEmail: false,
      invalidEmails: [],
      tags: "",
      isLoading: false,
    };
  },
  updated() {
    this.tagValue = this.oldValues;
    let checkInvalidEmail = this.checkInvalidEmailExists();
    let tagsContainers = document.getElementsByClassName(
      "multiselect__tags-wrap"
    )[0].children;
    let invalidEmails = this.invalidEmails;
    Array.from(tagsContainers).forEach((tag, i) => {
      let email = tag.children[0].innerText;
      if (this.invalidEmails.includes(email)) {
        tag.style.backgroundColor = "rgba(253, 242, 242, 1)";
      } else {
        tag.style.backgroundColor = "#e3e3e3";
      }
      tagsContainers[i].addEventListener("mouseover", function (e) {
        if (invalidEmails.includes(e.target.innerText)) {
          tagsContainers[i].style.backgroundColor = "rgba(247, 212, 212, 1)";
        } else {
          tagsContainers[i].style.backgroundColor = "#e3e3e3";
        }
      });
      tagsContainers[i].addEventListener("mouseleave", function (e) {
        if (invalidEmails.includes(email)) {
          tagsContainers[i].style.backgroundColor = "rgba(253, 242, 242, 1)";
        } else {
          tagsContainers[i].style.backgroundColor = "#e3e3e3";
        }
      });
    });

    this.$emit(
      "emailChanged",
      this.tagValue,
      this.invalidEmails,
      checkInvalidEmail
    );
    if (this.oldValues.length === 0) {
      this.$emit("tagsEmpty");
    }
  },
  methods: {
    addTag(newTag) {
      const tag = {
        email: newTag,
        isValid: this.validEmail,
      };
      this.$refs.email.$el.focus();
      this.oldValues.push(tag);
      this.getInvalidEmails();
      this.validEmail = false;
      // this.tagValue.push(tag);
      this.$emit("enableInvite");
    },
    selectOption(selectedOption, id) {
      const tag = { ...selectedOption, isValid: true };
      this.oldValues.push(tag);
      this.$refs.email.$el.focus();
      this.getInvalidEmails();
      this.$emit("enableInvite");
    },
    removeTag(removedOption, id) {
      const index = this.oldValues.indexOf(removedOption);
      if (index > -1) {
        this.oldValues.splice(index, 1);
      }
      this.getInvalidEmails();
    },
    checkInvalidEmailExists() {
      let invalidEmailExist = false;
      this.tagValue.forEach((object, id) => {
        if (!object.isValid) invalidEmailExist = true;
      });
      return invalidEmailExist;
    },
    getInvalidEmails() {
      this.invalidEmails = [];
      this.tagValue.forEach((object, id) => {
        if (!object.isValid) {
          this.invalidEmails.push(object.email);
        }
      });
    },
    handleTagChange(searchQuery, id) {
      if (!searchQuery) return;
      this.isLoading = true;
      this.validateEmail(searchQuery);
      if (!this.findUser) return;
      if (this.timeout) clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        axios.get(`/sharedo/searchUsers/${searchQuery}`).then((response) => {
          this.isLoading = false;
          this.tagOptions = response.data.searchUsers;
        });
      }, 300); // delay
    },
    addNewUser(e) {
      e.preventDefault();
      const formData = {
        emails: this.tagValue,
        ability: this.$refs.access.value.value,
        entity_id: this.entityLocal.id,
        entity_name: this.entityLocal.entity_name,
      };
      this.email = "";
      this.tagsEmpty();
      this.oldValues = [];
      this.tagValue = [];
      this.handleSubmit(formData);
    },
    handleSubmit(formData) {
      this.$inertia.post("/sharedo", formData);
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
/*basic tag */

/deep/ .multiselect__input {
  border: none;
  color: #050038;
  padding: 5px !important;
  width: 9rem !important;
  outline: none !important;
  box-shadow: none !important;
  position: relative !important;
  font-size: 14px !important;
}
/deep/ .multiselect__tag {
  background-color: #e3e3e3;
  color: #292555;
  border-radius: 100px;
  padding: 8px 40px 8px 10px;
}

/deep/ .multiselect__tags {
  max-height: 150px;
  overflow: scroll;
  border: none !important;
  cursor: default;
}

/*icon*/

/deep/ .multiselect__tag-icon {
  right: 13px;
  top: 9px;
  width: 13px;
  height: 13px;
  line-height: 9px;
  background: black;
  border-radius: 50%;
}

/deep/ .multiselect__tag-icon:after {
  color: white;
  font-size: 10px;
}
/deep/ .multiselect__select {
  display: none !important;
}

/* options*/
/deep/ .multiselect__option {
  padding: 0px !important;
  line-height: 0px !important;
  min-height: 0px !important;
  border: none;
}
/deep/ .multiselect__option {
  background-color: white;
}
/* /deep/ .multiselect__option--highlight:after {
  background: #318dd0 !important;
} */
/deep/ .multiselect__option--highlight {
  background-color: #318dd0 !important;
}
/deep/ .multiselect__content-wrapper {
  border-radius: 0.375rem;
  background: none;
  border: 0px !important;
}

/*placeholder*/
/deep/ .multiselect__placeholder {
  display: none !important;
  color: rgba(182, 182, 182, 255);
}

/deep/ ::placeholder {
  color: rgba(161, 161, 161, 255);
}
.btn-create {
  background-color: #318dd0;
}
.btn-create:hover {
  background-color: #4682bf;
}
</style>

