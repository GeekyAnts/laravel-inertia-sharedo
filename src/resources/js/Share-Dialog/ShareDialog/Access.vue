<template>
    <multiselect
        v-model="value"
        track-by="ability"
        label="ability"
        :options="options"
        :searchable="false"
        :show-labels="false"
        :allow-empty="false"
    >
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
            value: {
                ability: "Read",
                value: "read"
            },
            options: [
                {
                    ability: "Read",
                    value: "read"
                },
                {
                    ability: "Can Edit",
                    value: "write"
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
#input-email:focus {
    outline: none !important;
    box-shadow: none !important;
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
