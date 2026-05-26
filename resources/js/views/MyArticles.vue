<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">我的博客</h1>
            <router-link to="/my-articles/create" class="btn-primary">
                写新文章
            </router-link>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else>
            <!-- Articles list -->
            <div class="space-y-4">
                <div
                    v-for="article in articles"
                    :key="article.id"
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 card-hover animate-fade-in-up"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ article.title }}</h3>
                            <p class="text-gray-500 text-sm mt-1 line-clamp-2">{{ article.excerpt }}</p>
                            <div class="flex items-center gap-3 mt-3">
                                <span
                                    :class="article.is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                    class="px-2 py-0.5 text-xs font-medium rounded-full"
                                >
                                    {{ article.is_published ? '已发布' : '草稿' }}
                                </span>
                                <span class="text-xs text-gray-400">{{ formatDate(article.created_at) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            <router-link
                                :to="`/my-articles/${article.id}/edit`"
                                class="px-3 py-1.5 text-sm text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-300 font-medium"
                            >
                                编辑
                            </router-link>
                            <button
                                @click="handleDelete(article)"
                                class="px-3 py-1.5 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-all duration-300 font-medium"
                            >
                                删除
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="articles.length === 0" class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <p class="text-gray-400 text-lg mb-4">还没有发表文章</p>
                <router-link to="/my-articles/create" class="btn-primary">
                    开始写作
                </router-link>
            </div>

            <!-- Pagination -->
            <Pagination
                v-if="totalItems > 0"
                :current-page="currentPage"
                :total-pages="totalPages"
                :total="totalItems"
                :per-page="perPage"
                @page-change="fetchArticles"
                @per-page-change="handlePerPageChange"
                class="mt-8"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getMyArticles, deleteMyArticle } from '../api';
import Pagination from '../components/Pagination.vue';

const articles = ref([]);
const loading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(10);

async function fetchArticles(page) {
    loading.value = true;
    try {
        const response = await getMyArticles(page, perPage.value);
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

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage;
    fetchArticles(1);
}

async function handleDelete(article) {
    if (!confirm(`确定要删除《${article.title}》吗？`)) return;
    try {
        await deleteMyArticle(article.id);
        fetchArticles(currentPage.value);
    } catch (error) {
        alert(error.response?.data?.message || '删除失败');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('zh-CN');
}

onMounted(() => {
    fetchArticles(1);
});
</script>
