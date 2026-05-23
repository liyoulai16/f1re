<template>
    <router-link
        :to="`/article/${article.slug}`"
        class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 p-6 border border-gray-100"
    >
        <div class="flex items-center text-sm text-gray-400 mb-3">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ formatDate(article.published_at) }}
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600">
            {{ article.title }}
        </h2>
        <p class="text-gray-600 leading-relaxed line-clamp-2">
            {{ article.excerpt }}
        </p>
        <div class="mt-4 text-indigo-600 text-sm font-medium">
            阅读全文 →
        </div>
    </router-link>
</template>

<script setup>
defineProps({
    article: {
        type: Object,
        required: true,
    },
});

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('zh-CN', { year: 'numeric', month: 'long', day: 'numeric' });
}
</script>
