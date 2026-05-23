<template>
    <div class="max-w-3xl mx-auto px-4 py-12">
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else-if="article">
            <router-link to="/" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 mb-8 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                返回首页
            </router-link>

            <article>
                <header class="mb-8">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                        {{ article.title }}
                    </h1>
                    <div class="flex items-center text-sm text-gray-400">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ formatDate(article.published_at) }}
                    </div>
                </header>

                <div class="prose max-w-none" v-html="article.body"></div>
            </article>
        </div>

        <div v-else class="text-center py-12">
            <p class="text-gray-500 text-lg">文章未找到</p>
            <router-link to="/" class="text-indigo-600 hover:text-indigo-700 mt-4 inline-block">
                返回首页
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { getArticle } from '../api';

const route = useRoute();
const article = ref(null);
const loading = ref(true);

async function fetchArticle(slug) {
    loading.value = true;
    try {
        const response = await getArticle(slug);
        article.value = response.data.data;
    } catch {
        article.value = null;
    } finally {
        loading.value = false;
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('zh-CN', { year: 'numeric', month: 'long', day: 'numeric' });
}

onMounted(() => {
    fetchArticle(route.params.slug);
});

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchArticle(newSlug);
});
</script>
