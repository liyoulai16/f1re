<template>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-8 relative overflow-hidden">
        <!-- Background decorations -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-indigo-50/50 to-purple-50/50"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-200/30 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-200/20 rounded-full blur-3xl"></div>

        <div class="w-full max-w-lg relative z-10">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-100/50 p-10 border border-white/60">
                <!-- Logo / Brand -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mb-4 shadow-lg shadow-indigo-200">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">欢迎回来</h1>
                    <p class="text-gray-500 mt-2 text-base">选择身份登录您的账号</p>
                </div>

                <!-- Role Selector -->
                <div class="flex gap-3 mb-8">
                    <button
                        @click="selectedRole = 'user'"
                        :class="selectedRole === 'user'
                            ? 'border-indigo-500 bg-indigo-50 text-indigo-700 shadow-sm'
                            : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50'"
                        class="flex-1 flex flex-col items-center py-4 px-3 rounded-xl border-2 transition-all duration-200 cursor-pointer"
                    >
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium text-sm">普通用户</span>
                    </button>
                    <button
                        @click="selectedRole = 'admin'"
                        :class="selectedRole === 'admin'
                            ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm'
                            : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50'"
                        class="flex-1 flex flex-col items-center py-4 px-3 rounded-xl border-2 transition-all duration-200 cursor-pointer"
                    >
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="font-medium text-sm">管理员</span>
                    </button>
                </div>

                <form @submit.prevent="handleLogin" class="space-y-5">
                    <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm animate-shake">
                        {{ error }}
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">邮箱地址</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            required
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-base bg-gray-50/50 focus:bg-white"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">密码</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-base bg-gray-50/50 focus:bg-white"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="submitting"
                        :class="selectedRole === 'admin'
                            ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-purple-200'
                            : 'bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 shadow-indigo-200'"
                        class="w-full text-white py-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all font-medium text-base disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
                    >
                        <span v-if="submitting">登录中...</span>
                        <span v-else>以{{ selectedRole === 'admin' ? '管理员' : '用户' }}身份登录</span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        还没有账号？
                        <router-link to="/register" class="text-indigo-600 hover:text-indigo-700 font-medium">注册</router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const { login, user } = useAuth();
const router = useRouter();

const email = ref('');
const password = ref('');
const error = ref('');
const submitting = ref(false);
const selectedRole = ref('user');

async function handleLogin() {
    error.value = '';
    submitting.value = true;
    try {
        await login(email.value, password.value);
        const actualRole = user.value?.role;

        if (selectedRole.value === 'admin' && actualRole !== 'admin') {
            error.value = '该账号不具有管理员权限';
            submitting.value = false;
            return;
        }

        if (selectedRole.value === 'admin' && actualRole === 'admin') {
            router.push('/admin');
        } else {
            router.push('/');
        }
    } catch (e) {
        error.value = e.response?.data?.message || '登录失败，请重试';
    } finally {
        submitting.value = false;
    }
}
</script>
