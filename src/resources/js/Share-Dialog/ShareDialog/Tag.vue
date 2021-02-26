<template>
    <div>
        <multiselect
            id="input-email"
            v-model="tagValue"
            @search-change="handleTagChange"
            noResult="Enter Valid Email"
            tag-placeholder=""
            placeholder="Enter Emails"
            label="email"
            track-by="email"
            :options="tagOptions"
            :multiple="true"
            @remove="removeTag"
            :taggable="true"
            @tag="addTag"
            :preserveSearch="true"
        >
            <template slot="option" slot-scope="props" class="cyrus">
                <div
                    v-if="validEmail"
                    class="option__desc p-3 shadow-xl bg-blue-100 overflow-x-auto text-gray-600 hover:text-white hover:bg-blue-700 rounded-md"
                >
                    <span class="valid-email text-base " v-if="validEmail">
                        Create "{{ props.option.label }}"
                    </span>
                </div>
                <div v-else class="hidden"></div>
            </template>
            <template slot="afterList" slot-scope="props" v-if="!validEmail">
                <div
                    class="option_noResult p-3 shadow-xl w-full bg-white border-2 border-gray-100 p-2 text-gray-500 text-base flex items-center justify-center rounded-md"
                >
                    <span class="invalid-email text-base">
                        Enter valid email
                    </span>
                </div>
            </template>
            <template slot="noOptions" slot-scope="props">
                <div class="option__desc hidden"></div>
            </template>
        </multiselect>
    </div>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect
    },
    data() {
        return {
            tagValue: [{ email: "Cyrus" }, { email: "passi" }],
            oldValues: [],
            tagOptions: [],
            email: [],
            validEmail: false,
            tags: ""
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
                return;
            }
            const tag = {
                email: newTag
            };
            this.oldValues.push(tag);
            this.$emit("enableInvite");
        },
        removeTag(removedOption, id) {
            const index = this.oldValues.indexOf(removedOption);
            if (index > -1) {
                this.oldValues.splice(index, 1);
            }
        },
        handleTagChange(searchQuery, id) {
            this.validateEmail(searchQuery);
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
        }
    }
};
</script>
<style scoped>
/deep/ #input-email:focus {
    outline: none !important;
    box-shadow: none !important;
}

/deep/ .multiselect__input {
    font-size: 1rem;
    border: none;
    margin-bottom: 0px;
    vertical-align: top;
    padding: 0px;
    width: 8rem !important;
}

/deep/ ::-webkit-input-placeholder {
    color: transparent;
}

/deep/ :-moz-placeholder {
    /* Firefox 18- */
    color: transparent;
}

/deep/ ::-moz-placeholder {
    /* Firefox 19+ */
    color: transparent;
}

/deep/ :-ms-input-placeholder {
    color: transparent;
}
/deep/ .multiselect__placeholder {
    color: gray;
    font-size: 1rem;
    padding-top: 0px;
}
/deep/ .multiselect__option--highlight {
    background: white !important;
    color: black !important;
    text-align: center;
    padding: 0px !important;
    padding-top: 10px;
    border: none;
}
/deep/ .multiselect__option {
    font-size: 12px;
    color: black;
    line-height: 8px;
    min-height: 0px !important;
    padding: 0px !important;
    border: none;
}
/deep/ .multiselect__content-wrapper {
    border: 0px !important;
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
/deep/ .multiselect__tag-icon:hover {
    background-color: rgb(255, 189, 173);
    color: red !important;
}
/deep/ .multiselect__tag-icon:hover:after {
    color: #266d4d;
}
/deep/ .multiselect__tag {
    background-color: rgb(230, 230, 230);
    color: rgb(51, 51, 51);
    padding: 6px 40px 6px 10px;
    border-radius: 16px;
    font-size: 14.4px;
}

/deep/ .multiselect__select {
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
</style>
