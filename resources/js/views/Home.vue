<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">欢迎来到我的博客</h1>
            <p class="text-lg text-gray-500">分享技术见解，记录成长点滴</p>
        </div>

        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else>
            <div class="space-y-6">
                <ArticleCard v-for="article in articles" :key="article.id" :article="article" />
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex justify-center items-center space-x-2 mt-12">
                <button
                    @click="goToPage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                >
                    上一页
                </button>
                <span class="px-4 py-2 text-gray-500 text-sm">
                    {{ currentPage }} / {{ totalPages }}
                </span>
                <button
                    @click="goToPage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
                >
                    下一页
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getArticles } from '../api';
import ArticleCard from '../components/ArticleCard.vue';

const articles = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);

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
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

onMounted(() => {
    fetchArticles(1);
});
</script>
