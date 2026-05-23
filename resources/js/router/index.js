import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import Home from '../views/Home.vue';
import ArticleDetail from '../views/ArticleDetail.vue';
import Login from '../views/Login.vue';
import Dashboard from '../views/Dashboard.vue';

const routes = [
    { path: '/', name: 'home', component: Home, meta: { requiresAuth: true } },
    { path: '/article/:slug', name: 'article', component: ArticleDetail, meta: { requiresAuth: true } },
    { path: '/login', name: 'login', component: Login, meta: { guest: true } },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const { isAuthenticated, isLoading, checkAuth } = useAuth();

    if (isLoading.value) {
        await checkAuth();
    }

    if (to.meta.requiresAuth && !isAuthenticated.value) {
        next('/login');
    } else if (to.meta.guest && isAuthenticated.value) {
        next('/');
    } else {
        next();
    }
});

export default router;
