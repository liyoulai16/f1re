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
                    <router-link
                        to="/admin/articles"
                        active-class="!bg-white/15 !text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm"
                        class="flex items-center px-4 py-3 rounded-xl text-indigo-200 hover:bg-white/10 hover:text-white transition-all duration-200 group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        博客管理
                    </router-link>
                    <router-link
                        to="/admin/drafts"
                        active-class="!bg-white/15 !text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm"
                        class="flex items-center px-4 py-3 rounded-xl text-indigo-200 hover:bg-white/10 hover:text-white transition-all duration-200 group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        草稿箱
                    </router-link>
                    <router-link
                        to="/admin/categories"
                        active-class="!bg-white/15 !text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm"
                        class="flex items-center px-4 py-3 rounded-xl text-indigo-200 hover:bg-white/10 hover:text-white transition-all duration-200 group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        分类管理
                    </router-link>
                    <router-link
                        to="/admin/comments"
                        active-class="!bg-white/15 !text-white shadow-lg shadow-indigo-900/20 backdrop-blur-sm"
                        class="flex items-center px-4 py-3 rounded-xl text-indigo-200 hover:bg-white/10 hover:text-white transition-all duration-200 group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-400/20 to-purple-400/20 flex items-center justify-center mr-3 group-hover:from-indigo-400/30 group-hover:to-purple-400/30 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        评论管理
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
        <main class="flex-1 ml-64 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors">
            <!-- Top bar -->
            <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-sm border-b border-gray-200/60 dark:border-gray-700/60 sticky top-0 z-30 transition-colors">
                <div class="flex items-center justify-between px-8 py-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">管理后台</h2>
                    <div class="flex items-center space-x-4">
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
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ user?.name?.charAt(0) }}
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-300 font-medium">{{ user?.name }}</span>
                        </div>
                        <button
                            @click="handleLogout"
                            class="text-sm text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors"
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
import { useDarkMode } from '../../composables/useDarkMode';

const { user, logout } = useAuth();
const { isDark, toggleDark } = useDarkMode();
const router = useRouter();

async function handleLogout() {
    await logout();
    router.push('/login');
}
</script>
