<template>
    <div>
        <div class="mb-8">
            <router-link to="/admin/categories" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium transition-colors">
                &larr; 返回分类管理
            </router-link>
            <h1 class="text-2xl font-bold text-gray-900 mt-4">{{ isEdit ? '编辑分类' : '新建分类' }}</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 animate-fade-in-up">
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm animate-shake">
                    {{ error }}
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">分类名称</label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        placeholder="输入分类名称"
                        class="input-animated"
                    />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" :disabled="submitting" class="btn-primary">
                        <span v-if="submitting">保存中...</span>
                        <span v-else>{{ isEdit ? '更新分类' : '创建分类' }}</span>
                    </button>
                    <router-link to="/admin/categories" class="btn-secondary">
                        取消
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { createAdminCategory, updateAdminCategory, getAdminCategory } from '../../api';

const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const error = ref('');
const submitting = ref(false);

const form = reactive({
    name: '',
});

async function loadCategory() {
    if (!isEdit.value) return;
    try {
        const response = await getAdminCategory(route.params.id);
        const category = response.data.data;
        form.name = category.name;
    } catch (e) {
        error.value = '加载分类失败';
    }
}

async function handleSubmit() {
    error.value = '';
    submitting.value = true;
    try {
        if (isEdit.value) {
            await updateAdminCategory(route.params.id, form);
        } else {
            await createAdminCategory(form);
        }
        router.push('/admin/categories');
    } catch (e) {
        error.value = e.response?.data?.message || '保存失败，请重试';
        if (e.response?.data?.errors) {
            const errors = Object.values(e.response.data.errors).flat();
            error.value = errors.join('，');
        }
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    loadCategory();
});
</script>
