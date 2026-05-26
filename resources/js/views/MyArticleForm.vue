<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-8">
            <router-link to="/my-articles" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium transition-colors">
                &larr; 返回我的博客
            </router-link>
            <h1 class="text-2xl font-bold text-gray-900 mt-4">{{ isEdit ? '编辑文章' : '写新文章' }}</h1>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 animate-fade-in-up">
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div v-if="error" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm animate-shake">
                    {{ error }}
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">标题</label>
                    <input
                        v-model="form.title"
                        type="text"
                        required
                        placeholder="输入文章标题"
                        class="input-animated"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">摘要</label>
                    <textarea
                        v-model="form.excerpt"
                        required
                        rows="3"
                        placeholder="输入文章摘要"
                        class="input-animated resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">正文</label>
                    <textarea
                        v-model="form.body"
                        required
                        rows="12"
                        placeholder="输入文章正文（支持HTML）"
                        class="input-animated resize-y font-mono text-sm"
                    ></textarea>
                </div>

                <CoverImageUpload v-model="form.cover_image" />

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            v-model="form.is_published"
                            type="checkbox"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition"
                        />
                        <span class="text-sm text-gray-700">立即发布</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" :disabled="submitting" class="btn-primary">
                        <span v-if="submitting">保存中...</span>
                        <span v-else>{{ isEdit ? '更新文章' : '创建文章' }}</span>
                    </button>
                    <router-link to="/my-articles" class="btn-secondary">
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
import { createMyArticle, updateMyArticle, getMyArticle } from '../api';
import CoverImageUpload from '../components/CoverImageUpload.vue';

const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const error = ref('');
const submitting = ref(false);

const form = reactive({
    title: '',
    excerpt: '',
    body: '',
    cover_image: '',
    is_published: false,
});

async function loadArticle() {
    if (!isEdit.value) return;
    try {
        const response = await getMyArticle(route.params.id);
        const article = response.data.data;
        form.title = article.title;
        form.excerpt = article.excerpt;
        form.body = article.body;
        form.cover_image = article.cover_image || '';
        form.is_published = article.is_published;
    } catch (e) {
        error.value = '加载文章失败';
    }
}

async function handleSubmit() {
    error.value = '';
    submitting.value = true;
    try {
        if (isEdit.value) {
            await updateMyArticle(route.params.id, form);
        } else {
            await createMyArticle(form);
        }
        router.push('/my-articles');
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
    loadArticle();
});
</script>
