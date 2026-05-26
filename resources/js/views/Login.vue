<template>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-8 relative overflow-hidden">
        <!-- Animated background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50"></div>

        <!-- Animated floating shapes -->
        <div class="absolute top-10 left-10 w-20 h-20 opacity-60 animate-float">
            <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40" cy="40" r="36" stroke="url(#grad1)" stroke-width="3" fill="none" class="animate-draw"/>
                <path d="M28 40 L36 48 L52 32" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <defs><linearGradient id="grad1"><stop stop-color="#818cf8"/><stop offset="1" stop-color="#c084fc"/></linearGradient></defs>
            </svg>
        </div>

        <!-- Animated book/blog illustration top-right -->
        <div class="absolute top-16 right-16 w-48 h-48 opacity-50 animate-float-delayed">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="40" y="30" width="120" height="140" rx="8" fill="url(#bookGrad)" class="animate-pulse-slow"/>
                <rect x="55" y="50" width="90" height="8" rx="4" fill="white" opacity="0.8"/>
                <rect x="55" y="68" width="70" height="6" rx="3" fill="white" opacity="0.6"/>
                <rect x="55" y="84" width="80" height="6" rx="3" fill="white" opacity="0.6"/>
                <rect x="55" y="100" width="60" height="6" rx="3" fill="white" opacity="0.6"/>
                <circle cx="100" cy="140" r="16" fill="white" opacity="0.3"/>
                <path d="M94 140 L100 146 L108 134" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <defs><linearGradient id="bookGrad" x1="40" y1="30" x2="160" y2="170"><stop stop-color="#6366f1"/><stop offset="1" stop-color="#a855f7"/></linearGradient></defs>
            </svg>
        </div>

        <!-- Animated pen/writing illustration bottom-left -->
        <div class="absolute bottom-20 left-20 w-40 h-40 opacity-50 animate-float">
            <svg viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M120 20 L140 40 L60 120 L40 125 L45 105 Z" fill="url(#penGrad)" class="animate-pulse-slow"/>
                <path d="M110 30 L130 50" stroke="white" stroke-width="2" opacity="0.5"/>
                <circle cx="80" cy="130" r="20" stroke="#c084fc" stroke-width="2" fill="none" stroke-dasharray="4 4" class="animate-spin-slow"/>
                <path d="M30 145 L130 145" stroke="#e0e7ff" stroke-width="2" stroke-linecap="round"/>
                <path d="M40 155 L120 155" stroke="#e0e7ff" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
                <defs><linearGradient id="penGrad" x1="40" y1="125" x2="140" y2="20"><stop stop-color="#6366f1"/><stop offset="1" stop-color="#ec4899"/></linearGradient></defs>
            </svg>
        </div>

        <!-- Animated code/tech decoration bottom-right -->
        <div class="absolute bottom-16 right-20 w-36 h-36 opacity-40 animate-float-delayed">
            <svg viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10" y="10" width="120" height="90" rx="10" stroke="#818cf8" stroke-width="2" fill="none"/>
                <path d="M30 40 L50 55 L30 70" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="animate-typing"/>
                <path d="M60 70 L100 70" stroke="#a855f7" stroke-width="3" stroke-linecap="round" class="animate-typing-delayed"/>
                <circle cx="25" cy="23" r="3" fill="#f87171"/>
                <circle cx="35" cy="23" r="3" fill="#fbbf24"/>
                <circle cx="45" cy="23" r="3" fill="#34d399"/>
                <path d="M20 110 L50 110" stroke="#c7d2fe" stroke-width="2" stroke-linecap="round"/>
                <path d="M60 110 L120 110" stroke="#e0e7ff" stroke-width="2" stroke-linecap="round"/>
                <path d="M20 125 L80 125" stroke="#e0e7ff" stroke-width="2" stroke-linecap="round" opacity="0.6"/>
            </svg>
        </div>

        <!-- Floating dots -->
        <div class="absolute top-1/3 left-1/4 w-3 h-3 bg-indigo-400/40 rounded-full animate-bounce-slow"></div>
        <div class="absolute top-2/3 right-1/3 w-2 h-2 bg-purple-400/50 rounded-full animate-bounce-slow" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/4 right-1/4 w-4 h-4 bg-pink-300/30 rounded-full animate-bounce-slow" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-1/3 left-1/3 w-2 h-2 bg-indigo-300/40 rounded-full animate-bounce-slow" style="animation-delay: 0.5s;"></div>

        <!-- Gradient blur orbs -->
        <div class="absolute top-20 left-1/4 w-72 h-72 bg-indigo-200/30 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-20 right-1/4 w-96 h-96 bg-purple-200/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 2s;"></div>

        <div class="w-full max-w-lg relative z-10">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl shadow-indigo-100/50 p-10 border border-white/60 animate-fade-in-up">
                <!-- Logo / Brand -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mb-4 shadow-lg shadow-indigo-200 animate-bounce-in">
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
                            ? 'border-indigo-500 bg-indigo-50 text-indigo-700 shadow-sm scale-[1.02]'
                            : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:scale-[1.01]'"
                        class="flex-1 flex flex-col items-center py-4 px-3 rounded-xl border-2 transition-all duration-300 cursor-pointer"
                    >
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium text-sm">普通用户</span>
                    </button>
                    <button
                        @click="selectedRole = 'admin'"
                        :class="selectedRole === 'admin'
                            ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm scale-[1.02]'
                            : 'border-gray-200 text-gray-500 hover:border-gray-300 hover:bg-gray-50 hover:scale-[1.01]'"
                        class="flex-1 flex flex-col items-center py-4 px-3 rounded-xl border-2 transition-all duration-300 cursor-pointer"
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

                    <div class="group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">邮箱地址</label>
                        <input
                            id="email"
                            v-model="email"
                            type="email"
                            required
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300 text-base bg-gray-50/50 focus:bg-white focus:shadow-md focus:shadow-indigo-100"
                        />
                    </div>

                    <div class="group">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">密码</label>
                        <input
                            id="password"
                            v-model="password"
                            type="password"
                            required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300 text-base bg-gray-50/50 focus:bg-white focus:shadow-md focus:shadow-indigo-100"
                        />
                    </div>

                    <button
                        type="submit"
                        :disabled="submitting"
                        :class="selectedRole === 'admin'
                            ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-purple-200'
                            : 'bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 shadow-indigo-200'"
                        class="w-full text-white py-3 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 font-medium text-base disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 btn-ripple"
                    >
                        <span v-if="submitting" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            登录中...
                        </span>
                        <span v-else>以{{ selectedRole === 'admin' ? '管理员' : '用户' }}身份登录</span>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500">
                        还没有账号？
                        <router-link to="/register" class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors">注册</router-link>
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
