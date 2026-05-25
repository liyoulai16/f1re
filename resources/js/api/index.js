import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    withCredentials: true,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

export function getArticles(page = 1) {
    return api.get(`/articles?page=${page}`);
}

export function getArticle(slug) {
    return api.get(`/articles/${slug}`);
}

export function login(email, password) {
    return axios.get('/sanctum/csrf-cookie', { withCredentials: true }).then(() => {
        return api.post('/login', { email, password });
    });
}

export function register(name, email, password, password_confirmation) {
    return axios.get('/sanctum/csrf-cookie', { withCredentials: true }).then(() => {
        return api.post('/register', { name, email, password, password_confirmation });
    });
}

export function logout() {
    return api.post('/logout');
}

export function getUser() {
    return api.get('/user');
}

// Admin APIs
export function getAdminUsers(page = 1, search = '', role = '') {
    let url = `/admin/users?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (role) url += `&role=${role}`;
    return api.get(url);
}

export function createAdminUser(data) {
    return api.post('/admin/users', data);
}

export function getAdminUser(id) {
    return api.get(`/admin/users/${id}`);
}

export function updateAdminUser(id, data) {
    return api.put(`/admin/users/${id}`, data);
}

export function toggleAdminUserActive(id) {
    return api.patch(`/admin/users/${id}/toggle-active`);
}

export function getAdminStats() {
    return api.get('/admin/stats');
}

export default api;
