<template>
    <div class="max-w-4xl mx-auto px-4 py-12">
        <div class="mb-8">
            <router-link to="/my-articles" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-medium transition-colors">
                &larr; 返回我的博客
            </router-link>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-4">{{ isEdit ? '编辑文章' : '写新文章' }}</h1>
        </div>

        <!-- Auto-save notification -->
        <div v-if="autoSaveMessage" class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-600 dark:text-green-400 px-4 py-2 rounded-lg text-sm animate-fade-in-up">
            {{ autoSaveMessage }}
        </div>

        <!-- Draft restore prompt -->
        <div v-if="hasDraftToRestore" class="mb-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg text-sm flex items-center justify-between">
            <span>发现本地保存的草稿，是否恢复？</span>
            <div class="flex gap-2">
                <button @click="restoreDraft" class="px-3 py-1 bg-blue-600 text-white rounded text-xs font-medium hover:bg-blue-700 transition">恢复</button>
                <button @click="dismissDraft" class="px-3 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded text-xs font-medium hover:bg-gray-300 dark:hover:bg-gray-500 transition">忽略</button>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 animate-fade-in-up">
            <form @submit.prevent="handlePublish" class="space-y-6">
                <div v-if="error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg text-sm animate-shake">
                    {{ error }}
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">标题</label>
                    <input
                        v-model="form.title"
                        type="text"
                        placeholder="输入文章标题"
                        class="input-animated"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">分类</label>
                    <select
                        v-model="form.category_id"
                        class="input-animated"
                    >
                        <option :value="null">未分类</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">摘要</label>
                    <textarea
                        v-model="form.excerpt"
                        rows="3"
                        placeholder="输入文章摘要"
                        class="input-animated resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">正文</label>
                    <QuillEditor v-model="form.body" placeholder="输入文章正文..." />
                </div>

                <CoverImageUpload v-model="form.cover_image" />

                <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button
                        type="submit"
                        :disabled="submitting"
                        class="btn-primary"
                    >
                        <span v-if="submitting">保存中...</span>
                        <span v-else>{{ isEdit ? '更新并发布' : '发布文章' }}</span>
                    </button>
                    <button
                        type="button"
                        @click="handleSaveDraft"
                        :disabled="submitting"
                        class="btn-secondary"
                    >
                        <span v-if="submitting">保存中...</span>
                        <span v-else>保存草稿</span>
                    </button>
                    <router-link to="/my-articles" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-sm font-medium transition">
                        取消
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onBeforeUnmount, computed, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { createMyArticle, updateMyArticle, getMyArticle, getCategories } from '../api';
import CoverImageUpload from '../components/CoverImageUpload.vue';
import QuillEditor from '../components/QuillEditor.vue';

const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const error = ref('');
const submitting = ref(false);
const categories = ref([]);
const autoSaveMessage = ref('');
const hasDraftToRestore = ref(false);
let autoSaveTimer = null;
let autoSaveNotifyTimer = null;

const STORAGE_KEY = computed(() => `blog_draft_${isEdit.value ? route.params.id : 'new'}`);

const form = reactive({
    title: '',
    category_id: null,
    excerpt: '',
    body: '',
    cover_image: '',
    is_published: false,
});

function saveDraftToLocal() {
    const data = { ...form, savedAt: Date.now() };
    localStorage.setItem(STORAGE_KEY.value, JSON.stringify(data));
    autoSaveMessage.value = '草稿已自动保存';
    clearTimeout(autoSaveNotifyTimer);
    autoSaveNotifyTimer = setTimeout(() => {
        autoSaveMessage.value = '';
    }, 3000);
}

function checkLocalDraft() {
    const stored = localStorage.getItem(STORAGE_KEY.value);
    if (stored) {
        try {
            const data = JSON.parse(stored);
            if (data.title || data.body || data.excerpt) {
                hasDraftToRestore.value = true;
            }
        } catch {}
    }
}

function restoreDraft() {
    const stored = localStorage.getItem(STORAGE_KEY.value);
    if (stored) {
        try {
            const data = JSON.parse(stored);
            form.title = data.title || '';
            form.category_id = data.category_id || null;
            form.excerpt = data.excerpt || '';
            form.body = data.body || '';
            form.cover_image = data.cover_image || '';
        } catch {}
    }
    hasDraftToRestore.value = false;
}

function dismissDraft() {
    localStorage.removeItem(STORAGE_KEY.value);
    hasDraftToRestore.value = false;
}

function clearLocalDraft() {
    localStorage.removeItem(STORAGE_KEY.value);
}

function startAutoSave() {
    autoSaveTimer = setInterval(() => {
        if (form.title || form.body || form.excerpt) {
            saveDraftToLocal();
        }
    }, 30000);
}

async function loadCategories() {
    try {
        const response = await getCategories();
        categories.value = response.data.data;
    } catch (e) {
        console.error('Failed to load categories:', e);
    }
}

async function loadArticle() {
    if (!isEdit.value) return;
    try {
        const response = await getMyArticle(route.params.id);
        const article = response.data.data;
        form.title = article.title;
        form.category_id = article.category_id || null;
        form.excerpt = article.excerpt;
        form.body = article.body;
        form.cover_image = article.cover_image || '';
        form.is_published = article.is_published;
    } catch (e) {
        error.value = '加载文章失败';
    }
}

async function handlePublish() {
    error.value = '';
    submitting.value = true;
    try {
        const data = { ...form, is_published: true };
        if (isEdit.value) {
            await updateMyArticle(route.params.id, data);
        } else {
            await createMyArticle(data);
        }
        clearLocalDraft();
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

async function handleSaveDraft() {
    error.value = '';
    submitting.value = true;
    try {
        const data = { ...form, is_published: false };
        if (isEdit.value) {
            await updateMyArticle(route.params.id, data);
        } else {
            await createMyArticle(data);
        }
        clearLocalDraft();
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
    loadCategories();
    loadArticle().then(() => {
        checkLocalDraft();
    });
    startAutoSave();
});

onBeforeUnmount(() => {
    if (autoSaveTimer) clearInterval(autoSaveTimer);
    if (autoSaveNotifyTimer) clearTimeout(autoSaveNotifyTimer);
});
</script>
