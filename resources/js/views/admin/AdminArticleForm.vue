<template>
    <div>
        <div class="mb-8">
            <router-link to="/admin/articles" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 text-sm font-medium transition-colors">
                &larr; 返回博客管理
            </router-link>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-4">{{ isEdit ? '编辑文章' : '新建文章' }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 animate-fade-in-up">
            <form @submit.prevent="handleSubmit" class="space-y-6">
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

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            v-model="form.is_published"
                            type="checkbox"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 transition"
                        />
                        <span class="text-sm text-gray-700 dark:text-gray-300">立即发布</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" :disabled="submitting" class="btn-primary">
                        <span v-if="submitting">保存中...</span>
                        <span v-else>{{ isEdit ? '更新文章' : '创建文章' }}</span>
                    </button>
                    <router-link to="/admin/articles" class="btn-secondary">
                        取消
                    </router-link>
                </div>
            </form>
        </div>

        <!-- Comment Management (edit mode only) -->
        <div v-if="isEdit" class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 animate-fade-in-up">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">评论管理</h2>

            <div v-if="commentsLoading" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
            </div>

            <div v-else-if="flatComments.length === 0" class="text-center py-8 text-gray-400 dark:text-gray-500">
                该文章暂无评论
            </div>

            <div v-else class="space-y-4">
                <div v-for="comment in flatComments" :key="comment.id" class="border border-gray-100 dark:border-gray-700 rounded-lg p-4" :class="comment.depth > 0 ? 'ml-' + Math.min(comment.depth * 6, 18) : ''">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold mr-2">
                                {{ comment.user?.name?.charAt(0) }}
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ comment.user?.name }}</span>
                            <span v-if="comment.depth > 0" class="text-xs text-gray-400 dark:text-gray-500 ml-1">回复</span>
                            <span class="text-xs text-gray-400 dark:text-gray-500 ml-2">{{ formatDate(comment.created_at) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                v-if="editingCommentId !== comment.id"
                                @click="startEditComment(comment)"
                                class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 font-medium transition-colors"
                            >
                                编辑
                            </button>
                            <button
                                @click="handleDeleteComment(comment.id)"
                                class="text-xs text-red-500 dark:text-red-400 hover:text-red-700 font-medium transition-colors"
                            >
                                删除
                            </button>
                        </div>
                    </div>

                    <div v-if="editingCommentId === comment.id" class="mt-2">
                        <textarea
                            v-model="editingCommentBody"
                            rows="2"
                            class="input-animated resize-none text-sm"
                        ></textarea>
                        <div class="flex items-center gap-2 mt-2">
                            <button
                                @click="saveEditComment(comment.id)"
                                :disabled="!editingCommentBody.trim()"
                                class="btn-primary text-xs py-1.5 px-3 disabled:opacity-50"
                            >
                                保存
                            </button>
                            <button @click="cancelEditComment" class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700">
                                取消
                            </button>
                        </div>
                    </div>
                    <p v-else class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ comment.body }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { createAdminArticle, updateAdminArticle, getAdminArticle, getCategories, getAdminArticleComments, deleteComment, updateComment } from '../../api';
import CoverImageUpload from '../../components/CoverImageUpload.vue';
import QuillEditor from '../../components/QuillEditor.vue';

const router = useRouter();
const route = useRoute();

const isEdit = computed(() => !!route.params.id);
const error = ref('');
const submitting = ref(false);
const categories = ref([]);

const form = reactive({
    title: '',
    category_id: null,
    excerpt: '',
    body: '',
    cover_image: '',
    is_published: false,
});

const comments = ref([]);
const commentsLoading = ref(false);
const editingCommentId = ref(null);
const editingCommentBody = ref('');

const flatComments = computed(() => {
    function flatten(items, depth = 0) {
        let result = [];
        for (const item of items) {
            result.push({ ...item, depth });
            if (item.replies && item.replies.length > 0) {
                result = result.concat(flatten(item.replies, depth + 1));
            }
        }
        return result;
    }
    return flatten(comments.value);
});

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
        const response = await getAdminArticle(route.params.id);
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

async function loadComments() {
    if (!isEdit.value) return;
    commentsLoading.value = true;
    try {
        const response = await getAdminArticleComments(route.params.id);
        comments.value = response.data.data;
    } catch (e) {
        console.error('Failed to load comments:', e);
    } finally {
        commentsLoading.value = false;
    }
}

function startEditComment(comment) {
    editingCommentId.value = comment.id;
    editingCommentBody.value = comment.body;
}

function cancelEditComment() {
    editingCommentId.value = null;
    editingCommentBody.value = '';
}

async function saveEditComment(commentId) {
    if (!editingCommentBody.value.trim()) return;
    try {
        await updateComment(commentId, { body: editingCommentBody.value });
        editingCommentId.value = null;
        editingCommentBody.value = '';
        await loadComments();
    } catch (e) {
        alert(e.response?.data?.message || '编辑失败');
    }
}

async function handleDeleteComment(commentId) {
    if (!confirm('确定要删除此评论吗？')) return;
    try {
        await deleteComment(commentId);
        await loadComments();
    } catch (e) {
        alert(e.response?.data?.message || '删除失败');
    }
}

async function handleSubmit() {
    error.value = '';
    submitting.value = true;
    try {
        if (isEdit.value) {
            await updateAdminArticle(route.params.id, form);
        } else {
            await createAdminArticle(form);
        }
        router.push('/admin/articles');
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

function formatDate(dateStr) {
    if (!dateStr) return '';
    return new Date(dateStr).toLocaleString('zh-CN');
}

onMounted(() => {
    loadCategories();
    loadArticle();
    loadComments();
});
</script>
