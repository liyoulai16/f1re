<template>
    <div>
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">用户管理</h1>
            <router-link
                to="/admin/users/create"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium text-sm"
            >
                新建用户
            </router-link>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="搜索用户名或邮箱..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                        @input="debouncedSearch"
                    />
                </div>
                <select
                    v-model="roleFilter"
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    @change="fetchUsers(1)"
                >
                    <option value="">全部角色</option>
                    <option value="admin">管理员</option>
                    <option value="user">普通用户</option>
                </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">昵称</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">邮箱</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">角色</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">状态</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">注册时间</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="u in users" :key="u.id" class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ u.id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ u.name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ u.email }}</td>
                        <td class="px-6 py-4">
                            <span
                                :class="u.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'"
                                class="px-2 py-1 text-xs font-medium rounded-full"
                            >
                                {{ u.role === 'admin' ? '管理员' : '用户' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                :class="u.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                class="px-2 py-1 text-xs font-medium rounded-full"
                            >
                                {{ u.is_active ? '正常' : '已禁用' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(u.created_at) }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <router-link
                                :to="`/admin/users/${u.id}/edit`"
                                class="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
                            >
                                编辑
                            </router-link>
                            <button
                                v-if="u.id !== currentUser?.id"
                                @click="handleToggleActive(u)"
                                :class="u.is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800'"
                                class="text-sm font-medium"
                            >
                                {{ u.is_active ? '禁用' : '启用' }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Empty state -->
            <div v-if="users.length === 0" class="text-center py-12 text-gray-500">
                暂无用户数据
            </div>

            <!-- Pagination -->
            <div v-if="totalPages > 1" class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                <span class="text-sm text-gray-500">
                    共 {{ totalItems }} 条记录，第 {{ currentPage }} / {{ totalPages }} 页
                </span>
                <div class="flex space-x-2">
                    <button
                        @click="fetchUsers(currentPage - 1)"
                        :disabled="currentPage === 1"
                        class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-600 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                        上一页
                    </button>
                    <button
                        @click="fetchUsers(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        class="px-3 py-1 rounded border border-gray-300 text-sm text-gray-600 hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition"
                    >
                        下一页
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { getAdminUsers, toggleAdminUserActive } from '../../api';
import { useAuth } from '../../composables/useAuth';

const { user: currentUser } = useAuth();

const users = ref([]);
const loading = ref(true);
const search = ref('');
const roleFilter = ref('');
const currentPage = ref(1);
const totalPages = ref(1);
const totalItems = ref(0);

let searchTimeout = null;

function debouncedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchUsers(1);
    }, 300);
}

async function fetchUsers(page) {
    loading.value = true;
    try {
        const response = await getAdminUsers(page, search.value, roleFilter.value);
        users.value = response.data.data;
        currentPage.value = response.data.current_page;
        totalPages.value = response.data.last_page;
        totalItems.value = response.data.total;
    } catch (error) {
        console.error('Failed to fetch users:', error);
    } finally {
        loading.value = false;
    }
}

async function handleToggleActive(u) {
    try {
        const response = await toggleAdminUserActive(u.id);
        u.is_active = response.data.is_active;
    } catch (error) {
        alert(error.response?.data?.message || '操作失败');
    }
}

function formatDate(dateStr) {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('zh-CN');
}

onMounted(() => {
    fetchUsers(1);
});
</script>
