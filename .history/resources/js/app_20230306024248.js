import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { InertiaProgress } from "@inertiajs/progress";
import Layout from "./Shared/Layout.vue";

createInertiaApp({
    // layout: h => <Layout>{h}</Layout>,
    resolve: (name) => {
        // let page = require(`./Pages/${name}`).default;

        // if (page.layout === undefined) {
        //   page.layout = Layout;
        // }

        // return page;

        // const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        if (page.layout === undefined) {
            page.layout = Layout;
        }
        return page;
        // return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () =>
                h(App, {
                    ...props,
                    initialPage: JSON.parse(el.dataset.page),
                    Layout, // Pass Layout component as a prop
                }),
        })
        .use(plugin)
        .component("Link", Link)
        .component("Head", Head)
        .mount(el);
    },
    title: (title) => `My App - ${title}`,
});

InertiaProgress.init({
    color: "red",
    showSpinner: true,
});

// ➜  insider-task ./vendor/bin/sail composer require inertiajs/inertia-laravel
// ./vendor/bin/sail npm install @inertiajs/react
// ./vendor/bin/sail npm install --save-dev @vitejs/plugin-react
