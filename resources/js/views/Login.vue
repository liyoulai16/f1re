<template>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-lg">
            <div class="bg-white rounded-2xl shadow-lg p-10">
                <!-- Logo / Brand -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">欢迎回来</h1>
                    <p class="text-gray-500 mt-2 text-base">登录以查看博客内容</p>
                </div>

                <form @submit.prevent="handleLogin" class="space-y-6">
                    <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        {{ error }}
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">邮箱地址</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            required
                            placeholder="admin@blog.test"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-base"
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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition text-base"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition font-medium text-base disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="submitting">登录中...</span>
                        <span v-else>登录</span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-400">仅限管理员登录访问</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const { login } = useAuth();
const router = useRouter();

const email = ref('');
const password = ref('');
const error = ref('');
const submitting = ref(false);

async function handleLogin() {
    error.value = '';
    submitting.value = true;
    try {
        await login(email.value, password.value);
        router.push('/');
    } catch (e) {
        error.value = e.response?.data?.message || '登录失败，请重试';
    } finally {
        submitting.value = false;
    }
}
</script>
