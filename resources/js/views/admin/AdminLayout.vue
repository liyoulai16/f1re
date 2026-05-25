<template>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 fixed inset-y-0 left-0 z-40 flex flex-col overflow-hidden">
            <!-- Gradient background with animation -->
            <div class="absolute inset-0 bg-gradient-to-b from-indigo-900 via-purple-900 to-indigo-950"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(129,140,248,0.2),_transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_rgba(192,132,252,0.15),_transparent_50%)]"></div>
            <!-- Animated orbs -->
            <div class="absolute top-20 -left-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-2xl animate-float"></div>
            <div class="absolute bottom-32 -right-10 w-32 h-32 bg-purple-500/20 rounded-full blur-2xl animate-float-delayed"></div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col h-full">
                <div class="p-6 border-b border-white/10">
                    <h1 class="text-xl font-bold text-white tracking-tight">Blog 管理后台</h1>
                    <p class="text-xs text-indigo-300 mt-1">Administration Panel</p>
                </div>
                <nav class="flex-1 p-4 space-y-1">
                    <router-link
                        to="/admin"
                        exact-active-class="!bg-white/15 !text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm"
                        class="flex items-center px-4 py-3 rounded-xl text-indigo-200 hover:bg-white/10 hover:text-white transition-all duration-200 group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        仪表盘
                    </router-link>
                    <router-link
                        to="/admin/users"
                        active-class="!bg-white/15 !text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm"
                        class="flex items-center px-4 py-3 rounded-xl text-indigo-200 hover:bg-white/10 hover:text-white transition-all duration-200 group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        用户管理
                    </router-link>
                </nav>
                <div class="p-4 border-t border-white/10">
                    <router-link to="/" class="flex items-center px-4 py-3 rounded-xl text-indigo-300 hover:bg-white/10 hover:text-white transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </div>
                        返回前台
                    </router-link>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 ml-64 bg-gray-50 min-h-screen">
            <!-- Top bar -->
            <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/60 sticky top-0 z-30">
                <div class="flex items-center justify-between px-8 py-4">
                    <h2 class="text-lg font-semibold text-gray-800">管理后台</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ user?.name?.charAt(0) }}
                            </div>
                            <span class="text-sm text-gray-600 font-medium">{{ user?.name }}</span>
                        </div>
                        <button
                            @click="handleLogout"
                            class="text-sm text-gray-400 hover:text-red-500 transition-colors"
                        >
                            退出
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <div class="p-8">
                <router-view />
            </div>
        </main>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useAuth } from '../../composables/useAuth';

const { user, logout } = useAuth();
const router = useRouter();

async function handleLogout() {
    await logout();
    router.push('/login');
}
</script>
