import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import Home from '../views/Home.vue';
import ArticleDetail from '../views/ArticleDetail.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Dashboard from '../views/Dashboard.vue';

const routes = [
    { path: '/', name: 'home', component: Home },
    { path: '/article/:slug', name: 'article', component: ArticleDetail },
    { path: '/login', name: 'login', component: Login, meta: { guest: true } },
    { path: '/register', name: 'register', component: Register, meta: { guest: true } },
    { path: '/dashboard', name: 'dashboard', component: Dashboard, meta: { requiresAuth: true } },
    {
        path: '/my-articles',
        name: 'my-articles',
        component: () => import('../views/MyArticles.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/my-articles/create',
        name: 'my-article-create',
        component: () => import('../views/MyArticleForm.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/my-articles/:id/edit',
        name: 'my-article-edit',
        component: () => import('../views/MyArticleForm.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/my-favorites',
        name: 'my-favorites',
        component: () => import('../views/MyFavorites.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin',
        component: () => import('../views/admin/AdminLayout.vue'),
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
            { path: '', name: 'admin-dashboard', component: () => import('../views/admin/AdminDashboard.vue') },
            { path: 'users', name: 'admin-users', component: () => import('../views/admin/AdminUsers.vue') },
            { path: 'users/create', name: 'admin-user-create', component: () => import('../views/admin/AdminUserCreate.vue') },
            { path: 'users/:id/edit', name: 'admin-user-edit', component: () => import('../views/admin/AdminUserEdit.vue') },
            { path: 'articles', name: 'admin-articles', component: () => import('../views/admin/AdminArticles.vue') },
            { path: 'articles/create', name: 'admin-article-create', component: () => import('../views/admin/AdminArticleForm.vue') },
            { path: 'articles/:id/edit', name: 'admin-article-edit', component: () => import('../views/admin/AdminArticleForm.vue') },
            { path: 'drafts', name: 'admin-drafts', component: () => import('../views/admin/AdminDrafts.vue') },
            { path: 'categories', name: 'admin-categories', component: () => import('../views/admin/AdminCategories.vue') },
            { path: 'categories/create', name: 'admin-category-create', component: () => import('../views/admin/AdminCategoryForm.vue') },
            { path: 'categories/:id/edit', name: 'admin-category-edit', component: () => import('../views/admin/AdminCategoryForm.vue') },
            { path: 'comments', name: 'admin-comments', component: () => import('../views/admin/AdminComments.vue') },
        ]
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const { isAuthenticated, isLoading, checkAuth, user } = useAuth();

    if (isLoading.value) {
        await checkAuth();
    }

    if (to.meta.requiresAuth && !isAuthenticated.value) {
        next('/login');
    } else if (to.meta.requiresAdmin && user.value?.role !== 'admin') {
        next('/');
    } else if (to.meta.guest && isAuthenticated.value) {
        next('/');
    } else {
        next();
    }
});

export default router;
