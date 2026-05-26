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

export function getArticles(page = 1, perPage = 6) {
    return api.get(`/articles?page=${page}&per_page=${perPage}`);
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

// Upload
export function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    return api.post('/upload/image', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });
}

// User article management
export function getMyArticles(page = 1, perPage = 10) {
    return api.get(`/my-articles?page=${page}&per_page=${perPage}`);
}

export function getMyArticle(id) {
    return api.get(`/my-articles/${id}`);
}

export function createMyArticle(data) {
    return api.post('/my-articles', data);
}

export function updateMyArticle(id, data) {
    return api.put(`/my-articles/${id}`, data);
}

export function deleteMyArticle(id) {
    return api.delete(`/my-articles/${id}`);
}

// Admin APIs
export function getAdminUsers(page = 1, search = '', role = '', perPage = 10) {
    let url = `/admin/users?page=${page}&per_page=${perPage}`;
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

// Admin article management
export function getAdminArticles(page = 1, search = '', status = '', perPage = 10) {
    let url = `/admin/articles?page=${page}&per_page=${perPage}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${status}`;
    return api.get(url);
}

export function getAdminArticle(id) {
    return api.get(`/admin/articles/${id}`);
}

export function createAdminArticle(data) {
    return api.post('/admin/articles', data);
}

export function updateAdminArticle(id, data) {
    return api.put(`/admin/articles/${id}`, data);
}

export function deleteAdminArticle(id) {
    return api.delete(`/admin/articles/${id}`);
}

export default api;
