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

export function getArticles(page = 1, perPage = 6, category = '') {
    let url = `/articles?page=${page}&per_page=${perPage}`;
    if (category) url += `&category=${encodeURIComponent(category)}`;
    return api.get(url);
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

export function uploadEditorImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    return api.post('/upload/editor-image', formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
    });
}

// User article management
export function getMyArticles(page = 1, perPage = 10, status = '') {
    let url = `/my-articles?page=${page}&per_page=${perPage}`;
    if (status) url += `&status=${status}`;
    return api.get(url);
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

// Article likes & favorites
export function toggleArticleLike(slug) {
    return api.post(`/articles/${slug}/like`);
}

export function toggleArticleFavorite(slug) {
    return api.post(`/articles/${slug}/favorite`);
}

export function getArticleInteractions(slug) {
    return api.get(`/articles/${slug}/interactions`);
}

export function getMyFavorites(page = 1, perPage = 10) {
    return api.get(`/my-favorites?page=${page}&per_page=${perPage}`);
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
export function getAdminArticles(page = 1, search = '', status = '', perPage = 10, categoryId = '') {
    let url = `/admin/articles?page=${page}&per_page=${perPage}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${status}`;
    if (categoryId) url += `&category_id=${categoryId}`;
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

// Categories (public)
export function getCategories() {
    return api.get('/categories');
}

// Admin category management
export function getAdminCategories(page = 1, search = '', perPage = 10) {
    let url = `/admin/categories?page=${page}&per_page=${perPage}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    return api.get(url);
}

export function getAdminCategory(id) {
    return api.get(`/admin/categories/${id}`);
}

export function createAdminCategory(data) {
    return api.post('/admin/categories', data);
}

export function updateAdminCategory(id, data) {
    return api.put(`/admin/categories/${id}`, data);
}

export function deleteAdminCategory(id) {
    return api.delete(`/admin/categories/${id}`);
}

// Comments
export function getArticleComments(slug) {
    return api.get(`/articles/${slug}/comments`);
}

export function createComment(slug, data) {
    return api.post(`/articles/${slug}/comments`, data);
}

export function deleteComment(id) {
    return api.delete(`/comments/${id}`);
}

export function updateComment(id, data) {
    return api.put(`/comments/${id}`, data);
}

export function getAdminArticleComments(articleId) {
    return api.get(`/admin/articles/${articleId}/comments`);
}

// Admin comment management
export function getAdminComments(page = 1, search = '', articleId = '', perPage = 10) {
    let url = `/admin/comments?page=${page}&per_page=${perPage}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (articleId) url += `&article_id=${articleId}`;
    return api.get(url);
}

export function deleteAdminComment(id) {
    return api.delete(`/admin/comments/${id}`);
}

export default api;
