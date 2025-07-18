import '../css/app.css';
import './bootstrap';
// jQuery setup
import $ from 'jquery';
window.$ = window.jQuery = $;

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia';

// Font Awesome setup
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUser, faCoffee, faCartShopping } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@fortawesome/fontawesome-free/css/all.css'; // This is the key line!
library.add(faUser, faCoffee, faCartShopping);

const appName = import.meta.env.VITE_APP_NAME || 'Al Sabah';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(createPinia());

        vueApp.component('font-awesome-icon', FontAwesomeIcon);

        return vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
