import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../components/Home.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../components/Login.vue'),
    },
    {
        path: '/requests',
        name: 'Requests',
        component: () => import('../components/Requests.vue'),
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../components/Register.vue'),
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('../components/Profile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/startup',
        name: 'Startup',
        component: () => import('../components/Startup.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/messenger',
        name: 'Messenger',
        component: () => import('../components/Messenger.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/chat/:roomId',
        name: 'Chat',
        component: () => import('../components/Chat.vue'),
        props: true,
        meta: { requiresAuth: true }
    },
    {
        path: '/network',
        name: 'Network',
        component: () => import('../components/Network.vue'),
        meta: { requiresAuth: true }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        try {
            const response = await axios.get('/api/check-auth');
            if (response.status === 200) {
                next();
            }
        } catch (error) {
            next('/login');
        }
    } else {
        next();
    }
});

export default router;
