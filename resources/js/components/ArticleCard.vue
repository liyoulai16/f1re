<template>
    <router-link
        :to="`/article/${article.slug}`"
        class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden hover:-translate-y-0.5"
    >
        <div v-if="article.cover_image" class="relative h-48 overflow-hidden bg-gray-100 dark:bg-gray-700">
            <img
                :src="article.cover_image"
                :alt="article.title"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                @error="coverError = true"
            />
            <div v-if="coverError" class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30">
                <svg class="w-12 h-12 text-indigo-200 dark:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-center flex-wrap text-sm text-gray-400 dark:text-gray-500 mb-3">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ formatDate(article.published_at) }}
                <span v-if="article.user" class="ml-3 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ article.user.name }}
                </span>
                <span v-if="article.category" class="ml-3 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 text-xs font-medium rounded-full">
                    {{ article.category.name }}
                </span>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                {{ article.title }}
            </h2>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2">
                {{ article.excerpt }}
            </p>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                    阅读全文 →
                </span>
                <span v-if="article.likes_count !== undefined" class="flex items-center text-sm text-gray-400 dark:text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    {{ article.likes_count }}
                </span>
            </div>
        </div>
    </router-link>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
    article: {
        type: Object,
        required: true,
    },
});

const coverError = ref(false);

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('zh-CN', { year: 'numeric', month: 'long', day: 'numeric' });
}
</script>
