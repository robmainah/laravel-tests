import './bootstrap';

import "admin-lte/plugins/jquery/jquery.min.js";
import "admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js";
import "admin-lte/dist/js/adminlte.min.js";

import {createApp} from 'vue'
import router from './routes';

// import App from './App.vue'

createApp({}).use(router).mount("#app")
