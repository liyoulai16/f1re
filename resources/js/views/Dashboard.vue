<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">欢迎回来，{{ user?.name }}</h1>
                    <p class="text-gray-500">{{ user?.email }}</p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-200 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-indigo-600 font-medium">账号角色</p>
                                <p class="text-lg font-bold text-indigo-900">普通用户</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-200 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-green-600 font-medium">账号状态</p>
                                <p class="text-lg font-bold text-green-900">正常</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 p-5 bg-gray-50 rounded-xl">
                <h3 class="font-medium text-gray-800 mb-2">快速导航</h3>
                <div class="flex flex-wrap gap-3">
                    <router-link
                        to="/my-articles"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:border-indigo-300 hover:text-indigo-600 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        我的博客
                    </router-link>
                    <router-link
                        to="/my-articles/create"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:border-indigo-300 hover:text-indigo-600 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        写新文章
                    </router-link>
                    <router-link
                        to="/"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 hover:border-indigo-300 hover:text-indigo-600 transition"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        浏览文章
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuth } from '../composables/useAuth';

const { user } = useAuth();
</script>
