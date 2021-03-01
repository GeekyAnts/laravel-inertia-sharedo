const app = new Vue({
    el: "#app",
});

import { App, plugin } from "@inertiajs/inertia-vue";
import Vue from "vue";
Vue.use(plugin);

const el = document.getElementById("app");

new Vue({
    render: (h) =>
        h(App, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: (name) =>
                    require(`./Share-Dialog/${name}`).default,
            },
        }),
}).$mount(el);
