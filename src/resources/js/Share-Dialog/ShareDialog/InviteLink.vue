<template>
  <div class="flex mt-14 w-3/4 justify-between">
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
      <span class="flex items-center pl-10">
        <multiselect
          v-model="inviteLink"
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
    </span>

    <span>
      <div class="flex flex-wrap">
        <div class="w-full text-center">
          <button
            ref="btnRef"
            v-on:click="togglePopover()"
            class="bg-purple-600 hover:bg-purple-500 rounded text-sm focus:outline-none text-white py-2 px-5"
            type="button"
            style="transition: all 0.15s ease"
          >
            Copy Invite Link
          </button>
          <div
            ref="popoverRef"
            v-bind:class="{ hidden: !popoverShow, block: popoverShow }"
            class="bg-black mb-3 block z-50 leading-normal text-xs text-left no-underline break-words rounded-lg"
          >
            <div>
              <div class="text-white p-2">Link Copied to the Clipboard!</div>
            </div>
          </div>
        </div>
      </div>
      <!-- <button
        class="bg-purple-600 hover:bg-purple-500 rounded text-sm focus:outline-none text-white py-2 px-5"
        v-on:click="handleClick"
      >
        Copy Invite Link
      </button> -->
    </span>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import axios from "axios";
import Popper from "popper.js";
export default {
  name: "top-pink-popover",
  components: {
    Multiselect,
  },
  props: {
    entity: {},
  },
  data() {
    return {
      inviteLink: {
        ability: "Read",
        value: "read",
      },
      inviteUrl: "",
      popoverShow: false,
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
    };
  },
  methods: {
    togglePopover: function () {
      if (this.popoverShow) {
        this.popoverShow = false;
      } else {
        this.popoverShow = true;
        new Popper(this.$refs.btnRef, this.$refs.popoverRef, {
          placement: "top",
        });
      }
      const data = {
        ability: this.inviteLink.value,
        entity_id: this.entity.id,
        entity_name: this.entity.entity_name,
      };
      axios.post("/share-link", data).then((response) => {
        console.log(response);
        this.inviteUrl = response.data.url;
        var dummy = document.createElement("input");
        document.body.appendChild(dummy);
        dummy.setAttribute("value", this.inviteUrl);
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
      });
    },
    handleClick() {
      // console.log(this.entity);
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
}
/deep/ .multiselect__content-wrapper {
  width: 150px !important;
  margin-top: 30px;
  border-radius: 5px;
  z-index: 999;
  left: 10px;
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
/deep/ .multiselect__select:before {
  border-color: #4262ff transparent transparent transparent;
}
</style>
