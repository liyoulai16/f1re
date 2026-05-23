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
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-xl p-5 text-center">
                        <div class="text-3xl font-bold text-indigo-600">6</div>
                        <div class="text-sm text-gray-500 mt-1">已发布文章</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-5 text-center">
                        <div class="text-3xl font-bold text-green-600">128</div>
                        <div class="text-sm text-gray-500 mt-1">总阅读量</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-5 text-center">
                        <div class="text-3xl font-bold text-amber-600">12</div>
                        <div class="text-sm text-gray-500 mt-1">评论数</div>
                    </div>
                </div>
            </div>

            <div class="mt-8 p-4 bg-indigo-50 rounded-xl">
                <p class="text-indigo-700 text-sm">
                    <strong>提示：</strong>这是管理控制台的演示界面。在完整版本中，你可以在这里管理文章、查看统计和处理评论。
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useAuth } from '../composables/useAuth';

const { user } = useAuth();
</script>
