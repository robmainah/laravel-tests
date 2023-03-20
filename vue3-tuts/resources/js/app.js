import './bootstrap';

import "admin-lte/plugins/jquery/jquery.min.js";
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js";
import "admin-lte/dist/js/adminlte.min.js";
import {createRouter, createWebHistory} from 'vue-router'

import {createApp} from 'vue'
import routes from './routes';

// import App from './App.vue'

const router = createRouter({
    routes,
    history: createWebHistory(),
});

createApp({}).use(router).mount("#app")
