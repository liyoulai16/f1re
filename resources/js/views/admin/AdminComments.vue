<template>
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-8">评论管理</h1>

        <!-- Search & Filters -->
        <div class="flex items-center gap-4 mb-6">
            <div class="flex-1">
                <input
                    v-model="search"
                    @input="handleSearch"
                    type="text"
                    placeholder="搜索评论内容或用户名..."
                    class="input-animated"
                />
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else>
            <!-- Comments Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div v-if="comments.length === 0" class="text-center py-12 text-gray-400 dark:text-gray-500">
                    暂无评论
                </div>
                <table v-else class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">用户</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">评论内容</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">文章</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">时间</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">操作</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <tr v-for="comment in comments" :key="comment.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold mr-2">
                                        {{ comment.user?.name?.charAt(0) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ comment.user?.name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-2 max-w-xs">{{ comment.body }}</p>
                                <span v-if="comment.parent_id" class="text-xs text-indigo-500 dark:text-indigo-400">回复</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 max-w-[150px]">{{ comment.article?.title }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ formatDate(comment.created_at) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    @click="handleDelete(comment.id)"
                                    class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 font-medium transition-colors"
                                >
                                    删除
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <Pagination
                v-if="totalItems > 0"
                :current-page="currentPage"
                :total-pages="totalPages"
                :total="totalItems"
                :per-page="perPage"
                @page-change="fetchComments"
                @per-page-change="handlePerPageChange"
                class="mt-6"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminComments, deleteAdminComment } from '../../api';
import Pagination from '../../components/Pagination.vue';

const comments = ref([]);
const loading = ref(true);
const search = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);
const perPage = ref(10);
let searchTimer = null;

function handleSearch() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        fetchComments(1);
    }, 300);
}

async function fetchComments(page) {
    loading.value = true;
    try {
        const response = await getAdminComments(page, search.value, '', perPage.value);
        comments.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        totalItems.value = response.data.total;
    } catch (error) {
        console.error('Failed to fetch comments:', error);
    } finally {
        loading.value = false;
    }
}

function handlePerPageChange(newPerPage) {
    perPage.value = newPerPage;
    fetchComments(1);
}

async function handleDelete(commentId) {
    if (!confirm('确定要删除此评论吗？其下所有回复也会被删除。')) return;
    try {
        await deleteAdminComment(commentId);
        fetchComments(currentPage.value);
    } catch (error) {
        alert(error.response?.data?.message || '删除失败');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleString('zh-CN');
}

onMounted(() => {
    fetchComments(1);
});
</script>
