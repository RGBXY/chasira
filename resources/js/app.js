import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { createPinia } from "pinia";

const pinia = createPinia();

createInertiaApp({
    title: (title) => `My app ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .component("Head", Head)
            .component("Link", Link)
            .mount(el);
    },
    progress: {
        color: "white",
        includeCSS: true,
        showSpinner: true,
    },
});
