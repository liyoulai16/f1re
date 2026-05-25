<template>
    <div>
        <div class="flex items-center mb-8">
            <router-link to="/admin/users" class="text-gray-500 hover:text-gray-700 mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </router-link>
            <h1 class="text-2xl font-bold text-gray-900">新建用户</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-2xl">
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                    {{ error }}
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">昵称</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">邮箱</label>
                    <input
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">密码</label>
                    <input
                        v-model="form.password"
                        type="password"
                        required
                        placeholder="至少6位字符"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">角色</label>
                    <select
                        v-model="form.role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                    >
                        <option value="user">普通用户</option>
                        <option value="admin">管理员</option>
                    </select>
                </div>

                <div class="flex items-center space-x-4 pt-4">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="submitting">创建中...</span>
                        <span v-else>创建用户</span>
                    </button>
                    <router-link to="/admin/users" class="text-gray-500 hover:text-gray-700 font-medium">
                        取消
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { createAdminUser } from '../../api';

const router = useRouter();
const form = reactive({
    name: '',
    email: '',
    password: '',
    role: 'user',
});
const error = ref('');
const submitting = ref(false);

async function handleSubmit() {
    error.value = '';
    submitting.value = true;
    try {
        await createAdminUser(form);
        router.push('/admin/users');
    } catch (e) {
        if (e.response?.data?.errors) {
            error.value = Object.values(e.response.data.errors).flat().join('；');
        } else {
            error.value = e.response?.data?.message || '创建失败';
        }
    } finally {
        submitting.value = false;
    }
}
</script>
