<template>
    <nav class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50 transition-colors">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <router-link to="/" class="text-xl font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition">
                    Blog
                </router-link>

                <!-- Desktop nav -->
                <div class="hidden sm:flex items-center space-x-6">
                    <router-link to="/" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium">
                        首页
                    </router-link>
                    <template v-if="isAuthenticated">
                        <template v-if="isAdmin">
                            <router-link to="/admin" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium">
                                管理后台
                            </router-link>
                        </template>
                        <template v-else>
                            <router-link to="/my-articles" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium">
                                我的博客
                            </router-link>
                            <router-link to="/my-favorites" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium">
                                我的收藏
                            </router-link>
                            <router-link to="/dashboard" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium">
                                个人中心
                            </router-link>
                        </template>
                        <button
                            @click="handleLogout"
                            class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 transition font-medium"
                        >
                            退出
                        </button>
                        <span class="text-sm text-gray-400 dark:text-gray-500">{{ user?.name }}</span>
                    </template>
                    <template v-else>
                        <router-link
                            to="/login"
                            class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium"
                        >
                            登录
                        </router-link>
                        <router-link
                            to="/register"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium text-sm"
                        >
                            注册
                        </router-link>
                    </template>

                    <!-- Dark Mode Toggle -->
                    <button
                        @click="toggleDark"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                        :title="isDark ? '切换亮色模式' : '切换暗色模式'"
                    >
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden space-x-2">
                    <button
                        @click="toggleDark"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    >
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>
                    <button @click="mobileOpen = !mobileOpen" class="text-gray-600 dark:text-gray-300 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileOpen" class="sm:hidden pb-4 space-y-2 border-t border-gray-100 dark:border-gray-700 pt-2">
                <router-link to="/" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400" @click="mobileOpen = false">
                    首页
                </router-link>
                <template v-if="isAuthenticated">
                    <template v-if="isAdmin">
                        <router-link to="/admin" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400" @click="mobileOpen = false">
                            管理后台
                        </router-link>
                    </template>
                    <template v-else>
                        <router-link to="/my-articles" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400" @click="mobileOpen = false">
                            我的博客
                        </router-link>
                        <router-link to="/my-favorites" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400" @click="mobileOpen = false">
                            我的收藏
                        </router-link>
                        <router-link to="/dashboard" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400" @click="mobileOpen = false">
                            个人中心
                        </router-link>
                    </template>
                    <button @click="handleLogout" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400">
                        退出
                    </button>
                </template>
                <template v-else>
                    <router-link to="/login" class="block py-2 text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400" @click="mobileOpen = false">
                        登录
                    </router-link>
                    <router-link to="/register" class="block py-2 text-indigo-600 dark:text-indigo-400 font-medium" @click="mobileOpen = false">
                        注册
                    </router-link>
                </template>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import { useDarkMode } from '../composables/useDarkMode';

const { user, isAuthenticated, isAdmin, logout } = useAuth();
const { isDark, toggleDark } = useDarkMode();
const router = useRouter();
const mobileOpen = ref(false);

async function handleLogout() {
    await logout();
    mobileOpen.value = false;
    router.push('/');
}
</script>
