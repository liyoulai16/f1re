<template>
    <div>
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">草稿箱</h1>
        </div>

        <!-- Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="搜索草稿标题..."
                        class="input-animated"
                        @input="debouncedSearch"
                    />
                </div>
                <select
                    v-model="categoryFilter"
                    class="input-animated w-auto"
                    @change="fetchDrafts(1)"
                >
                    <option value="">全部分类</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Table -->
        <div v-else class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">标题</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">作者</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">分类</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">创建时间</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="article in articles" :key="article.id" class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/30 transition-all duration-200">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate">
                            {{ article.title || '(无标题)' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ article.user?.name || '未知' }}</td>
                        <td class="px-6 py-4">
                            <span v-if="article.category" class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-2 py-1 text-xs font-medium rounded-full">
                                {{ article.category.name }}
                            </span>
                            <span v-else class="text-gray-400 dark:text-gray-500 text-xs">未分类</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ formatDate(article.created_at) }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <router-link
                                :to="`/admin/articles/${article.id}/edit`"
                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm font-medium transition-colors"
                            >
                                编辑
                            </router-link>
                            <button
                                @click="handlePublish(article)"
                                class="text-green-600 dark:text-green-400 hover:text-green-800 text-sm font-medium transition-colors"
                            >
                                发布
                            </button>
                            <button
                                @click="handleDelete(article)"
                                class="text-red-600 dark:text-red-400 hover:text-red-800 text-sm font-medium transition-colors"
                            >
                                删除
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="articles.length === 0" class="text-center py-12 text-gray-500 dark:text-gray-400">
                暂无草稿
            </div>

            <div v-if="articles.length > 0" class="px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                <Pagination
                    :current-page="currentPage"
                    :total-pages="totalPages"
                    :total="totalItems"
                    :per-page="perPage"
                    @page-change="fetchDrafts"
                    @per-page-change="handlePerPageChange"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminArticles, updateAdminArticle, deleteAdminArticle, getCategories } from '../../api';
import Pagination from '../../components/Pagination.vue';

const articles = ref([]);
const categories = ref([]);
const loading = ref(true);
const search = ref('');
const categoryFilter = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(10);

let searchTimeout = null;

function debouncedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchDrafts(1);
    }, 300);
}

async function fetchCategories() {
    try {
        const response = await getCategories();
        categories.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch categories:', error);
    }
}

async function fetchDrafts(page) {
    loading.value = true;
    try {
        const response = await getAdminArticles(page, search.value, 'draft', perPage.value, categoryFilter.value);
        articles.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        totalItems.value = response.data.total;
    } catch (error) {
        console.error('Failed to fetch drafts:', error);
    } finally {
        loading.value = false;
    }
}

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage;
    fetchDrafts(1);
}

async function handlePublish(article) {
    if (!confirm(`确定要发布《${article.title || '无标题'}》吗？`)) return;
    try {
        await updateAdminArticle(article.id, { ...article, is_published: true });
        fetchDrafts(currentPage.value);
    } catch (error) {
        alert(error.response?.data?.message || '发布失败');
    }
}

async function handleDelete(article) {
    if (!confirm(`确定要删除《${article.title || '无标题'}》吗？`)) return;
    try {
        await deleteAdminArticle(article.id);
        fetchDrafts(currentPage.value);
    } catch (error) {
        alert(error.response?.data?.message || '删除失败');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('zh-CN');
}

onMounted(() => {
    fetchCategories();
    fetchDrafts(1);
});
</script>
