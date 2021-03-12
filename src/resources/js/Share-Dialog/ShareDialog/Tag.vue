<template>
    <div class="w-full">
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
                <div
                    v-if="!validEmail"
                    class="text-sm text-left text-red-500 mt-3"
                >
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
                <div
                    class="btn-create p-6 mt-3"
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
/*basic tag */
/deep/ .multiselect__input {
    border: none;
    color: #050038;
    padding: 5px !important;
    width: 8rem !important;
    outline: none !important;
    box-shadow: none !important;
    position: relative !important;
    font-size: 14px !important;
}
/deep/ .multiselect__tag {
    background-color: #e3e3e3;
    color: #292555;
    border-radius: 16px;
    padding: 8px 40px 8px 10px;
}

/deep/ .multiselect__tags {
    max-height: 150px;
    overflow: scroll;
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
/deep/ .multiselect__option--highlight:after {
    background: #318dd0 !important;
}
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
}

.btn-create {
    background-color: #318dd0;
}
.btn-create:hover {
    background-color: #4682bf;
}
</style>
