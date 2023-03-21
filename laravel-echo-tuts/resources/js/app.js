import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import {createRouter, createWebHistory} from 'vue-router'
import {createApp} from 'vue'
import routes from './routes';

const router = createRouter({
    routes,
    history: createWebHistory(),
});

createApp({}).use(router).mount("#app")

