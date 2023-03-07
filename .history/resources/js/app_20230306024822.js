import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { InertiaProgress } from "@inertiajs/progress";
import Layout from "./Shared/Layout.vue";

createInertiaApp({
    Layout: (page) => h(Layout, { child: page }),
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
