import { createApp, h } from 'vue'
import { createInertiaApp, Link, Head } from '@inertiajs/vue3'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .component("Link", Link)
      .component("Head", Head)
      .mount(el)
  },
  title: title => `My App - ${title}`
})

// ➜  insider-task ./vendor/bin/sail composer require inertiajs/inertia-laravel
// ./vendor/bin/sail npm install @inertiajs/react
// ./vendor/bin/sail npm install --save-dev @vitejs/plugin-react
