import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { InertiaProgress } from "@inertiajs/progress";
import Layout from "./Shared/Layout.vue";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });

        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .component("Link", Link)
            .component("Head", Head)
            .provide('layout', Layout)
            .mount(el);
    },
    title: (title) => `Championship Prediction - ${title}`,
});

InertiaProgress.init({
    color: "red",
    showSpinner: true,
});
