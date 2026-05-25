<template>
    <div class="min-h-[calc(100vh-4rem)]">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-pink-300/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-400/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-4xl mx-auto px-4 py-20 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">欢迎来到我的博客</h1>
                <p class="text-lg text-white/80 max-w-2xl mx-auto">分享技术见解，记录成长点滴</p>
            </div>
            <!-- Wave divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
                    <path d="M0 80L60 74.7C120 69 240 59 360 53.3C480 48 600 48 720 53.3C840 59 960 69 1080 69.3C1200 69 1320 59 1380 53.3L1440 48V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#f9fafb"/>
                </svg>
            </div>
        </div>

        <!-- Articles Section -->
        <div class="max-w-4xl mx-auto px-4 py-12">
            <div v-if="loading" class="flex justify-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>

            <div v-else>
                <div class="space-y-6">
                    <ArticleCard v-for="article in articles" :key="article.id" :article="article" />
                </div>

                <!-- Empty state -->
                <div v-if="articles.length === 0" class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <p class="text-gray-400 text-lg">暂无文章</p>
                </div>

                <!-- Pagination -->
                <div v-if="totalPages > 1" class="flex justify-center items-center mt-12">
                    <nav class="flex items-center space-x-1">
                        <!-- Previous -->
                        <button
                            @click="goToPage(currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="px-3 py-2 rounded-lg text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        <!-- Page numbers -->
                        <template v-for="page in displayedPages" :key="page">
                            <span v-if="page === '...'" class="px-3 py-2 text-gray-400">...</span>
                            <button
                                v-else
                                @click="goToPage(page)"
                                :class="page === currentPage
                                    ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200'
                                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600'"
                                class="w-10 h-10 rounded-lg font-medium transition"
                            >
                                {{ page }}
                            </button>
                        </template>

                        <!-- Next -->
                        <button
                            @click="goToPage(currentPage + 1)"
                            :disabled="currentPage === totalPages"
                            class="px-3 py-2 rounded-lg text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { getArticles } from '../api';
import ArticleCard from '../components/ArticleCard.vue';

const articles = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);

const displayedPages = computed(() => {
    const total = totalPages.value;
    const current = currentPage.value;
    const pages = [];

    if (total <= 7) {
        for (let i = 1; i <= total; i++) pages.push(i);
        return pages;
    }

    pages.push(1);

    if (current > 3) {
        pages.push('...');
    }

    const start = Math.max(2, current - 1);
    const end = Math.min(total - 1, current + 1);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    if (current < total - 2) {
        pages.push('...');
    }

    pages.push(total);

    return pages;
});

async function fetchArticles(page) {
    loading.value = true;
    try {
        const response = await getArticles(page);
        articles.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
    } catch (error) {
        console.error('Failed to fetch articles:', error);
    } finally {
        loading.value = false;
    }
}

function goToPage(page) {
    if (page < 1 || page > totalPages.value) return;
    fetchArticles(page);
    window.scrollTo({ top: 300, behavior: 'smooth' });
}

onMounted(() => {
    fetchArticles(1);
});
</script>
