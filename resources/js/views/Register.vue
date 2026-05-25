<template>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-lg">
            <div class="bg-white rounded-2xl shadow-lg p-10">
                <!-- Logo / Brand -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">创建账号</h1>
                    <p class="text-gray-500 mt-2 text-base">注册以开始浏览和互动</p>
                </div>

                <form @submit.prevent="handleRegister" class="space-y-5">
                    <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                        {{ error }}
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">昵称</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="你的昵称"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition text-base"
                        />
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">邮箱地址</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition text-base"
                        />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">密码</label>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="至少6位字符"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition text-base"
                        />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">确认密码</label>
                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            placeholder="再次输入密码"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition text-base"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="submitting"
                        class="w-full bg-emerald-600 text-white py-3 rounded-lg hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition font-medium text-base disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="submitting">注册中...</span>
                        <span v-else>注册</span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        已有账号？
                        <router-link to="/login" class="text-indigo-600 hover:text-indigo-700 font-medium">登录</router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const { register } = useAuth();
const router = useRouter();

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});
const error = ref('');
const submitting = ref(false);

async function handleRegister() {
    error.value = '';
    submitting.value = true;
    try {
        await register(form.name, form.email, form.password, form.password_confirmation);
        router.push('/');
    } catch (e) {
        if (e.response?.data?.errors) {
            const errors = e.response.data.errors;
            error.value = Object.values(errors).flat().join('；');
        } else {
            error.value = e.response?.data?.message || '注册失败，请重试';
        }
    } finally {
        submitting.value = false;
    }
}
</script>
