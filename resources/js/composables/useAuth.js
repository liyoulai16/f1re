import { ref, computed } from 'vue';
import { getUser as fetchUser, login as apiLogin, logout as apiLogout, register as apiRegister } from '../api';

const user = ref(null);
const isAuthenticated = ref(false);
const isLoading = ref(true);

export function useAuth() {
    const isAdmin = computed(() => user.value?.role === 'admin');

    async function checkAuth() {
        isLoading.value = true;
        try {
            const response = await fetchUser();
            user.value = response.data;
            isAuthenticated.value = true;
        } catch {
            user.value = null;
            isAuthenticated.value = false;
        } finally {
            isLoading.value = false;
        }
    }

    async function login(email, password) {
        const response = await apiLogin(email, password);
        user.value = response.data.user;
        isAuthenticated.value = true;
        return response;
    }

    async function register(name, email, password, password_confirmation) {
        const response = await apiRegister(name, email, password, password_confirmation);
        user.value = response.data.user;
        isAuthenticated.value = true;
        return response;
    }

    async function logout() {
        await apiLogout();
        user.value = null;
        isAuthenticated.value = false;
    }

    return {
        user,
        isAuthenticated,
        isAdmin,
        isLoading,
        checkAuth,
        login,
        register,
        logout,
    };
}
