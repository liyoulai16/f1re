<template>
    <div>
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">博客管理</h1>
            <router-link to="/admin/articles/create" class="btn-primary">
                新建文章
            </router-link>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="搜索文章标题或摘要..."
                        class="input-animated"
                        @input="debouncedSearch"
                    />
                </div>
                <select
                    v-model="statusFilter"
                    class="input-animated w-auto"
                    @change="fetchArticles(1)"
                >
                    <option value="">全部状态</option>
                    <option value="published">已发布</option>
                    <option value="draft">草稿</option>
                </select>
                <select
                    v-model="categoryFilter"
                    class="input-animated w-auto"
                    @change="fetchArticles(1)"
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">状态</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">赞/藏</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">创建时间</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="article in articles" :key="article.id" class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/30 transition-all duration-200">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100 max-w-xs truncate">{{ article.title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ article.user?.name || '未知' }}</td>
                        <td class="px-6 py-4">
                            <span v-if="article.category" class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 px-2 py-1 text-xs font-medium rounded-full">
                                {{ article.category.name }}
                            </span>
                            <span v-else class="text-gray-400 dark:text-gray-500 text-xs">未分类</span>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                :class="article.is_published ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400'"
                                class="px-2 py-1 text-xs font-medium rounded-full"
                            >
                                {{ article.is_published ? '已发布' : '草稿' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs text-red-500 dark:text-red-400 mr-2" title="点赞数">♥ {{ article.likes_count || 0 }}</span>
                            <span class="text-xs text-yellow-500 dark:text-yellow-400" title="收藏数">★ {{ article.favorites_count || 0 }}</span>
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
                暂无文章数据
            </div>

            <div class="px-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                <Pagination
                    :current-page="currentPage"
                    :total-pages="totalPages"
                    :total="totalItems"
                    :per-page="perPage"
                    @page-change="fetchArticles"
                    @per-page-change="handlePerPageChange"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminArticles, deleteAdminArticle, getCategories } from '../../api';
import Pagination from '../../components/Pagination.vue';

const articles = ref([]);
const categories = ref([]);
const loading = ref(true);
const search = ref('');
const statusFilter = ref('');
const categoryFilter = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(10);

let searchTimeout = null;

function debouncedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchArticles(1);
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

async function fetchArticles(page) {
    loading.value = true;
    try {
        const response = await getAdminArticles(page, search.value, statusFilter.value, perPage.value, categoryFilter.value);
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
        await deleteAdminArticle(article.id);
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
    fetchCategories();
    fetchArticles(1);
});
</script>
