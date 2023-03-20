import {createRouter, createWebHistory} from 'vue-router'

import Dashboard from "./components/Dashboard.vue";
import ListAppointments from "./pages/appointments/index.vue";
import AppointmentCreate from "./pages/appointments/create.vue";
import UserList from "./pages/users/UserList.vue";
import UpdateSettings from "./pages/settings/index.vue";
import UpdatProfile from "./pages/profile/index.vue";
import Logout from "./pages/logout/index.vue";

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/appointments',
        name: 'appointments',
        component: ListAppointments,
    },
    {
        path: '/appointments/create',
        name: 'appointments.create',
        component: AppointmentCreate,
    },
    {
        path: '/users',
        name: 'users',
        component: UserList,
    },
    {
        path: '/settings',
        name: 'settings',
        component: UpdateSettings,
    },
    {
        path: '/profile',
        name: 'profile',
        component: UpdatProfile,
    },
    {
        path: '/logout',
        name: 'logout',
        component: Logout,
    },
];

export default createRouter({
    routes,
    history: createWebHistory(),
});
