<template>
    <layout title="Welcome">
        <div class="w-100 h-100 p-3">
            <div class="flex flex-col w-1/2">
                <div
                    v-if="$page.props.flash.message && show"
                    class="mb-8 flex items-center justify-between bg-green-500 rounded max-w-3xl"
                >
                    <div class="flex items-center">
                        <svg
                            class="ml-4 mr-2 flex-shrink-0 w-4 h-4 fill-white"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <polygon points="0 11 2 9 7 14 18 3 20 5 7 18" />
                        </svg>
                        <div class="py-4 text-white text-sm font-medium">
                            {{ $page.props.flash.message }}
                        </div>
                    </div>
                    <button
                        type="button"
                        class="group mr-2 p-2"
                        @click="show = false"
                    >
                        <svg
                            class="block w-2 h-2 fill-green-800 group-hover:fill-white"
                            xmlns="http://www.w3.org/2000/svg"
                            width="235.908"
                            height="235.908"
                            viewBox="278.046 126.846 235.908 235.908"
                        >
                            <path
                                d="M506.784 134.017c-9.56-9.56-25.06-9.56-34.62 0L396 210.18l-76.164-76.164c-9.56-9.56-25.06-9.56-34.62 0-9.56 9.56-9.56 25.06 0 34.62L361.38 244.8l-76.164 76.165c-9.56 9.56-9.56 25.06 0 34.62 9.56 9.56 25.06 9.56 34.62 0L396 279.42l76.164 76.165c9.56 9.56 25.06 9.56 34.62 0 9.56-9.56 9.56-25.06 0-34.62L430.62 244.8l76.164-76.163c9.56-9.56 9.56-25.06 0-34.62z"
                            />
                        </svg>
                    </button>
                </div>
                <div
                    v-if="Object.keys($page.props.errors).length > 0 && show"
                    class="mb-8 flex items-center justify-between bg-red-500 rounded max-w-3xl"
                >
                    <div
                        v-if="$page.props.errors"
                        class="py-4 text-white text-sm font-medium"
                    >
                        <ul>
                            <li
                                v-for="error in $page.props.errors"
                                :key="error"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </div>
                    <svg
                        class="ml-4 mr-2 flex-shrink-0 w-4 h-4 fill-white"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41A8 8 0 1 0 15.66 4.34 8 8 0 0 0 4.34 15.66zm9.9-8.49L11.41 10l2.83 2.83-1.41 1.41L10 11.41l-2.83 2.83-1.41-1.41L8.59 10 5.76 7.17l1.41-1.41L10 8.59l2.83-2.83 1.41 1.41z"
                        />
                    </svg>
                </div>

                <div>
                    <form
                        @submit.prevent="handleSubmit"
                        class="flex space-x-5 p-3"
                    >
                        <span class="font-bold text-2xl">To</span>
                        <span class="flex flex-col space-y-2">
                            <div
                                v-for="(find, index) in finds"
                                class="flex bg-gray-300 justify-between p-2"
                            >
                                <span class="inline">
                                    {{ find.email }}
                                </span>

                                <button
                                    type="button"
                                    class="inline mt-1"
                                    v-on:click="deleteUser(find)"
                                >
                                    <svg
                                        class="h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <input
                                type="email"
                                placeholder="Enter Emails"
                                name="email[]"
                                v-model="email"
                                class="border-none focus:outline-none"
                            />
                            <div
                                v-if="validEmail"
                                class="bg-blue-800 px-3 py-2 text-white"
                            >
                                <button v-on:click="createUser()">
                                    Create User
                                </button>
                            </div>
                        </span>
                        <span>
                            <multiselect
                                v-model="value"
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
                        <span>
                            <button
                                type="submit"
                                class="bg-blue-600 text-white px-5 py-2"
                            >
                                Invite
                            </button>
                        </span>
                    </form>
                </div>
                <div class="p-3 flex space-x-5">
                    <span class="flex items-center space-x-2">
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
                        <span class="text-bg-gray-500">
                            Invite through Link
                        </span>
                    </span>

                    <span>
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
                    <span>
                        <button class="bg-blue-600 text-white px-4 py-2">
                            Copy Invite Link
                        </button>
                    </span>
                </div>
                <div>
                    <div
                        v-for="(user, index) in users"
                        :key="user.id"
                        class="p-3 flex"
                    >
                        <span class="flex items-center space-x-2">
                            <span>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </span>
                            <span>{{ user.email }}</span>
                        </span>
                        <span>
                            <multiselect
                                v-model="users[index]"
                                :id="user.email"
                                track-by="ability"
                                label="ability"
                                @select="onSelect"
                                placeholder="Select one"
                                :options="options1"
                                :searchable="false"
                                :show-labels="false"
                                :allow-empty="false"
                            >
                            </multiselect>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from "./Layout";
import Multiselect from "vue-multiselect";
export default {
    components: {
        Layout,
        Multiselect
    },
    data() {
        return {
            form: {
                email: null
            },
            email: "",
            finds: [],
            validEmail: false,
            show: true,
            inviteLink: { ability: "Read" },
            value: { ability: "Read" },
            options: [{ ability: "Read" }, { ability: "Can Edit" }],
            options1: [
                { ability: "Read" },
                { ability: "Can Edit" },
                { ability: "Remove" }
            ],
            alertOpen: false
        };
    },
    props: {
        entity: {},
        users: Array
    },

    methods: {
        addFind: function() {
            this.finds.push({ email: "" });
            console.log(this.finds);
        },
        closeAlert: function() {
            this.alertOpen = false;
        },
        createUser: function() {
            const data = {
                email: this.email
            };
            this.validEmail = false;
            this.finds.push({ email: this.email });
            this.email = "";
        },
        deleteUser: function(find) {
            const userIndex = this.finds.indexOf(find);
            this.finds.splice(userIndex, 1);
            console.log(this.finds);
        },
        handleSubmit() {
            const formData = {
                emails: this.finds,
                ability: this.value.ability,
                entity_id: this.entity.id,
                entity_name: this.entity.entity_name
            };
            console.log(formData);
            this.$inertia.post("/share-dialog", formData);
            this.finds.length = 0;
            this.alertOpen = true;
        },
        onSelect: function(selectedOption, index) {
            console.log(selectedOption.ability);
            console.log(index);
            const emails = [];
            emails.push({ email: index });
            const data = {
                ability: selectedOption.ability,
                emails: emails,
                entity_id: this.entity.id,
                entity_name: this.entity.entity_name
            };
            this.$inertia.post("/share-dialog", data);
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
        "$page.flash": {
            handler() {
                this.show = true;
            },
            deep: true
        },
        email(value) {
            console.log(value);
            this.email = value;
            this.validateEmail(value);
        }
    }
};
</script>
<!-- New step!
     Add Multiselect CSS. Can be added as a static asset or inside a component. -->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.multiselect__option--highlight {
    background: #00bfff !important;
}
.multiselect__option {
    font-size: 12px;
}
.multiselect__placeholder {
    display: inline-block !important;
    margin-bottom: 20px !important;
    padding-top: 0px !important;
    background: red;
}

.multiselect__tag {
    display: block;
    font-size: 4rem !important;
    border: none;
    background: #fff;
}
.multiselect__tags {
    /* display: block; */
    /* padding: 3px 40px 0 8px; */
    /* padding-top: 2px; */
    font: 5px;
    border: none;
    background: #fff;
}
</style>
