<template>
    <div>
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">分类管理</h1>
            <router-link to="/admin/categories/create" class="btn-primary">
                新建分类
            </router-link>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 card-hover">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="搜索分类名称..."
                        class="input-animated"
                        @input="debouncedSearch"
                    />
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Table -->
        <div v-else class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">名称</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">文章数</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">创建时间</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="category in categories" :key="category.id" class="hover:bg-indigo-50/30 transition-all duration-200">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ category.id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ category.name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ category.slug }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-1 text-xs font-medium rounded-full">
                                {{ category.articles_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(category.created_at) }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <router-link
                                :to="`/admin/categories/${category.id}/edit`"
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors"
                            >
                                编辑
                            </router-link>
                            <button
                                @click="handleDelete(category)"
                                class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors"
                            >
                                删除
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Empty state -->
            <div v-if="categories.length === 0" class="text-center py-12 text-gray-500">
                暂无分类数据
            </div>

            <!-- Pagination -->
            <div class="px-6 border-t border-gray-200 bg-gray-50">
                <Pagination
                    :current-page="currentPage"
                    :total-pages="totalPages"
                    :total="totalItems"
                    :per-page="perPage"
                    @page-change="fetchCategories"
                    @per-page-change="handlePerPageChange"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminCategories, deleteAdminCategory } from '../../api';
import Pagination from '../../components/Pagination.vue';

const categories = ref([]);
const loading = ref(true);
const search = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(10);

let searchTimeout = null;

function debouncedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchCategories(1);
    }, 300);
}

async function fetchCategories(page) {
    loading.value = true;
    try {
        const response = await getAdminCategories(page, search.value, perPage.value);
        categories.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        totalItems.value = response.data.total;
    } catch (error) {
        console.error('Failed to fetch categories:', error);
    } finally {
        loading.value = false;
    }
}

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage;
    fetchCategories(1);
}

async function handleDelete(category) {
    if (!confirm(`确定要删除分类「${category.name}」吗？该分类下的文章将变为未分类。`)) return;
    try {
        await deleteAdminCategory(category.id);
        fetchCategories(currentPage.value);
    } catch (error) {
        alert(error.response?.data?.message || '删除失败');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('zh-CN');
}

onMounted(() => {
    fetchCategories(1);
});
</script>
