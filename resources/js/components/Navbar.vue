<template>
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <router-link to="/" class="text-xl font-bold text-indigo-600 hover:text-indigo-700 transition">
                    Blog
                </router-link>

                <!-- Desktop nav -->
                <div class="hidden sm:flex items-center space-x-6">
                    <router-link to="/" class="text-gray-600 hover:text-indigo-600 transition font-medium">
                        首页
                    </router-link>
                    <template v-if="isAuthenticated">
                        <template v-if="isAdmin">
                            <router-link to="/admin" class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                管理后台
                            </router-link>
                        </template>
                        <template v-else>
                            <router-link to="/my-articles" class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                我的博客
                            </router-link>
                            <router-link to="/dashboard" class="text-gray-600 hover:text-indigo-600 transition font-medium">
                                个人中心
                            </router-link>
                        </template>
                        <button
                            @click="handleLogout"
                            class="text-gray-600 hover:text-red-600 transition font-medium"
                        >
                            退出
                        </button>
                        <span class="text-sm text-gray-400">{{ user?.name }}</span>
                    </template>
                    <template v-else>
                        <router-link
                            to="/login"
                            class="text-gray-600 hover:text-indigo-600 transition font-medium"
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
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileOpen = !mobileOpen" class="sm:hidden text-gray-600 p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileOpen" class="sm:hidden pb-4 space-y-2">
                <router-link to="/" class="block py-2 text-gray-600 hover:text-indigo-600" @click="mobileOpen = false">
                    首页
                </router-link>
                <template v-if="isAuthenticated">
                    <template v-if="isAdmin">
                        <router-link to="/admin" class="block py-2 text-gray-600 hover:text-indigo-600" @click="mobileOpen = false">
                            管理后台
                        </router-link>
                    </template>
                    <template v-else>
                        <router-link to="/my-articles" class="block py-2 text-gray-600 hover:text-indigo-600" @click="mobileOpen = false">
                            我的博客
                        </router-link>
                        <router-link to="/dashboard" class="block py-2 text-gray-600 hover:text-indigo-600" @click="mobileOpen = false">
                            个人中心
                        </router-link>
                    </template>
                    <button @click="handleLogout" class="block py-2 text-gray-600 hover:text-red-600">
                        退出
                    </button>
                </template>
                <template v-else>
                    <router-link to="/login" class="block py-2 text-gray-600 hover:text-indigo-600" @click="mobileOpen = false">
                        登录
                    </router-link>
                    <router-link to="/register" class="block py-2 text-indigo-600 font-medium" @click="mobileOpen = false">
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

const { user, isAuthenticated, isAdmin, logout } = useAuth();
const router = useRouter();
const mobileOpen = ref(false);

async function handleLogout() {
    await logout();
    mobileOpen.value = false;
    router.push('/');
}
</script>
