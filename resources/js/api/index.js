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

export function logout() {
    return api.post('/logout');
}

export function getUser() {
    return api.get('/user');
}

export default api;
