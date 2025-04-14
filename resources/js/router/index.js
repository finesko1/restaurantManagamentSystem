import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/App/Home.vue';
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';
import store from "@/stores/store.js";
import WaiterDashboard from "../components/Waiter/WaiterDashboard.vue";
import CreateOrder from "../components/Orders/CreateOrder.vue";
import OrderDetail from "../components/Orders/OrderDetail.vue";
import OrderList from "../components/Orders/OrderList.vue";
import CookDashboard from "../components/Cook/CookDashboard.vue";

let OrderDetails;
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guest: true },
    },
    {
        path: '/waiter',
        component: WaiterDashboard,
        meta: { requiresAuth: true, role: 'waiter' },
        props: true,
        children: [
            {
                path: '/waiter/orders',
                name: 'waiter-orders',
                component: OrderList
            },
            {
                path: '/waiter/create',
                name: 'create-order',
                component: CreateOrder
            },
            {
                path: 'order/:id',
                name: 'order-details',
                component: OrderDetail,
                props: true
            }
        ]
    },
    {
        path: '/cook',
        component: CookDashboard,
        meta: { requiresAuth: true, role: 'cook' },
        props: true,
        children: [
            {
                path: '/cook/orders',
                name: 'cook-orders',
                component: OrderList
            },
            {
                path: '/cook/create',
                name: 'create-order',
                component: CreateOrder
            },
            {
                path: 'order/:id',
                name: 'order-details',
                component: OrderDetail,
                props: true
            }
        ]
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    await store.dispatch('fetchUser');

    if (to.meta.requiresAuth) {
        const isAuthenticated = store.getters.isAuthenticated;
        const userRole = store.getters.role;

        if (!isAuthenticated || userRole !== to.meta.role) {
            return next('/login');
        }
    }

    next();
});

export default router;
