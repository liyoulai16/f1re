<template>
    <div class="min-h-[calc(100vh-4rem)]">
        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse-slow"></div>
                <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-pink-300/20 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-400/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative max-w-4xl mx-auto px-4 py-20 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight animate-fade-in-up">欢迎来到我的博客</h1>
                <p class="text-lg text-white/80 max-w-2xl mx-auto animate-fade-in-up" style="animation-delay: 0.2s;">分享技术见解，记录成长点滴</p>
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
                <Pagination
                    v-if="totalItems > 0"
                    :current-page="currentPage"
                    :total-pages="totalPages"
                    :total="totalItems"
                    :per-page="perPage"
                    :per-page-options="[6, 9, 12, 18, 24]"
                    @page-change="goToPage"
                    @per-page-change="handlePerPageChange"
                    class="mt-12"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getArticles } from '../api';
import ArticleCard from '../components/ArticleCard.vue';
import Pagination from '../components/Pagination.vue';

const articles = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(6);

async function fetchArticles(page, pp) {
    loading.value = true;
    try {
        const response = await getArticles(page, pp || perPage.value);
        articles.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        totalItems.value = response.data.total;
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

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage;
    fetchArticles(1, newPerPage);
}

onMounted(() => {
    fetchArticles(1);
});
</script>
