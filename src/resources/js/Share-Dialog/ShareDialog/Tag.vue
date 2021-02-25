<template>
    <multiselect
        id="input-email"
        v-model="tagValue"
        @search-change="handleTagChange"
        noResult="Enter Valid Email"
        tag-placeholder=""
        placeholder="Enter Email"
        label="email"
        track-by="email"
        :options="tagOptions"
        :multiple="true"
        :taggable="true"
        preserveSearch="true"
        @tag="addTag"
    >
        <template slot="option" slot-scope="props">
            <div
                v-if="validEmail"
                class="option__desc p-3 shadow-xl bg-blue-100 overflow-x-auto text-gray-600 hover:text-white hover:bg-blue-700 rounded-md"
            >
                <span class="valid-email text-base " v-if="validEmail">
                    Create "{{ props.option.label }}"
                </span>
            </div>
            <div
                v-if="!validEmail"
                class="option_desc p-3 shadow-xl w-full bg-white border-2 border-gray-100 p-2 text-gray-500 text-base flex items-center justify-center rounded-md"
            >
                <span class="invalid-email text-base">
                    Enter valid email
                </span>
            </div>

            <!-- <span>{{
                    validEmail
                        ? "Create " + props.option.label
                        : "Enter valid email"
                }}</span> -->
        </template>
        <template slot="noOptions" slot-scope="props">
            <div class="option__desc"></div>
        </template>
    </multiselect>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect
    },
    data() {
        return {
            tagValue: [],
            tagOptions: [],
            email: [],
            validEmail: false,
            tags: ""
        };
    },
    methods: {
        addTag(newTag) {
            if (!this.validEmail) {
                return;
            }
            const tag = {
                email: newTag
            };
            this.tagValue.push(tag);
            this.$emit("enableInvite");
        },
        handleTagChange(searchQuery, id) {
            this.validateEmail(searchQuery);
        },
        validateEmail(value) {
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
                this.validEmail = true;
            } else {
                this.validEmail = false;
            }
        }
    },
    watch: {
        email(value, oldValue) {
            this.validateEmail(value);
        }
    }
};
</script>
<style scoped>
/deep/ #input-email:focus {
    outline: none !important;
    box-shadow: none !important;
}
/deep/ .multiselect__placeholder {
    color: gray;
}
/deep/ .multiselect__option--highlight {
    background: white !important;
    color: black !important;
    text-align: center;
    padding: 0px !important;
    padding-top: 10px;
}
/deep/ .multiselect__option {
    font-size: 12px;
    color: black;
    line-height: 8px;
    min-height: 0px !important;
}
/deep/ .multiselect__option:hover {
    color: black !important;
}
/deep/ .multiselect {
    min-height: 0px !important;
    position: none;
}
/deep/ .multiselect__tag-icon {
    cursor: pointer;
    margin-left: 7px;
    position: absolute;
    right: 9px;
    top: 1px;
    bottom: 0;
    font-weight: 700;
    font-style: normal;
    width: 22px;
    text-align: center;
    line-height: 22px;
    transition: all 0.2s ease;
    border-radius: 0px;
}
/deep/ .multiselect__tag-icon {
    cursor: pointer;
    margin-left: 7px;
    position: absolute;
    right: 9px;
    top: 1px;
    bottom: 0;
    font-weight: 700;
    font-style: normal;
    width: 22px;
    text-align: center;
    line-height: 22px;
    transition: all 0.2s ease;
    border-radius: 0px;
}
/deep/ .multiselect__tag-icon:hover {
    background-color: rgb(255, 189, 173);
    color: rgb(222, 53, 11) !important;
}

/deep/ .multiselect__tag {
    background-color: rgb(230, 230, 230);
    color: rgb(51, 51, 51);
    padding: 6px 40px 6px 10px;
    border-radius: 16px;
    font-size: 14.4px;
}
/deep/ .multiselect__single {
    min-height: 0px !important;
    line-height: unset !important;
    color: black;
    display: unset !important;
    font-weight: bolder;
}
/deep/ .multiselect__select {
    height: 24px !important;
    line-height: unset !important;
    display: none !important;
}
/deep/ .multiselect__tags {
    font-size: 12.5px;
    border: none;
    min-height: 0px !important;
    line-height: unset !important;
    padding: 5px 40px 2px 2px !important;
    background: #fff;
}
/deep/ .multiselect__tags:hover {
    /* background-color: rgba(128, 128, 128, 0.2);
    background-color: red; */
}
/deep/ .mutliselect__select:hover::before .multiselect__tags {
    background-color: rgba(128, 128, 128, 0.2);
}
/deep/ .multiselect__single {
    font-size: 14.4px;
    color: #4b4b4b;
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
</style>
