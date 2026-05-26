<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">我的收藏</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">收藏的文章会保存在这里</p>
        </div>

        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else>
            <div class="space-y-6">
                <div v-for="article in articles" :key="article.id" class="relative">
                    <ArticleCard :article="article" />
                    <button
                        @click.prevent="removeFavorite(article.slug)"
                        class="absolute top-4 right-4 p-2 bg-white dark:bg-gray-700 rounded-full shadow-md hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition-all z-10"
                        title="取消收藏"
                    >
                        <svg class="w-4 h-4" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div v-if="articles.length === 0" class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                </svg>
                <p class="text-gray-400 dark:text-gray-500 text-lg">还没有收藏任何文章</p>
                <router-link to="/" class="text-indigo-600 dark:text-indigo-400 hover:underline mt-2 inline-block">
                    去发现好文章 →
                </router-link>
            </div>

            <Pagination
                v-if="totalItems > 0"
                :current-page="currentPage"
                :total-pages="totalPages"
                :total="totalItems"
                :per-page="perPage"
                :per-page-options="[6, 10, 20]"
                @page-change="goToPage"
                @per-page-change="handlePerPageChange"
                class="mt-12"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getMyFavorites, toggleArticleFavorite } from '../api';
import ArticleCard from '../components/ArticleCard.vue';
import Pagination from '../components/Pagination.vue';

const articles = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(10);

async function fetchFavorites(page, pp) {
    loading.value = true;
    try {
        const response = await getMyFavorites(page, pp || perPage.value);
        articles.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        totalItems.value = response.data.total;
    } catch (error) {
        console.error('Failed to fetch favorites:', error);
    } finally {
        loading.value = false;
    }
}

async function removeFavorite(slug) {
    try {
        await toggleArticleFavorite(slug);
        articles.value = articles.value.filter(a => a.slug !== slug);
        totalItems.value--;
    } catch {}
}

function goToPage(page) {
    fetchFavorites(page);
}

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage;
    fetchFavorites(1, newPerPage);
}

onMounted(() => {
    fetchFavorites(1);
});
</script>
