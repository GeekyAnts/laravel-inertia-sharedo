<template>
    <div>
        <div
            v-for="(user, index) in users"
            :key="index"
            class="py-4 px-1 flex border-b border-gray-100 space-x-3"
        >
            <span class="flex items-center space-x-2">
                <span>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-6"
                        viewBox="0 0 20 20"
                        fill="currentColor fill-current text-black"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </span>
                <span class="text-base text-gray-500">{{ user.email }}</span>
            </span>
            <span>
                <multiselect
                    v-model="usersAbilties[index]"
                    :id="index"
                    track-by="value"
                    label="ability"
                    @select="onSelect"
                    placeholder="Select one"
                    :options="options"
                    :searchable="false"
                    :show-labels="false"
                    :allow-empty="false"
                >
                </multiselect>
            </span>
        </div>
    </div>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect
    },
    props: {
        entity: {},
        users: Array,
        usersAbilties: Array
    },
    data() {
        return {
            options: [
                {
                    ability: "Read",
                    value: "read"
                },
                {
                    ability: "Can Edit",
                    value: "write"
                },
                {
                    ability: "Remove",
                    value: "remove"
                }
            ]
        };
    },
    methods: {
        onSelect: function(selectedOption, index) {
            const emails = [];
            emails.push({
                email: this.users[index].email
            });
            const data = {
                ability: selectedOption.value,
                emails: emails,
                entity_id: this.entity.id,
                entity_name: this.entity.entity_name
            };
            this.users[index].ability = selectedOption.value;
            this.$emit("updateAccess", data);
        }
    }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
/deep/ ul > li:nth-child(3) span span {
    color: red;
}

/deep/ .multiselect__option--highlight {
    background: #d3eeff !important;
    color: black !important;
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
    display: unset !important;
    font-weight: bolder;
}
/deep/ .multiselect__select {
    height: 24px !important;
    line-height: unset !important;
}
/deep/ .multiselect__tags {
    font-size: 12.5px;
    border: none;
    min-height: 0px !important;
    line-height: unset !important;
    padding: 3px 40px 2px 2px !important;
    background: #fff;
}
/deep/ .multiselect__tags:hover {
    background-color: rgba(128, 128, 128, 0.2);
}
/deep/ .multiselect__option--selected {
    font-weight: normal;
    background-color: white;
}
/deep/ .mutliselect__select:hover::before .multiselect__tags {
    background-color: rgba(128, 128, 128, 0.2);
}
/deep/ .multiselect__single {
    font-size: 14.4px;
    color: #4b4b4b;
    font-weight: 500;
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
