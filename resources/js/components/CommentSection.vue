<template>
    <div class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-12">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">
            评论区
            <span v-if="totalCount > 0" class="text-base font-normal text-gray-400 dark:text-gray-500 ml-2">{{ totalCount }} 条评论</span>
        </h3>

        <!-- Comment Form -->
        <div v-if="isAuthenticated" class="mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
                <textarea
                    v-model="newComment"
                    rows="3"
                    placeholder="写下你的评论..."
                    class="input-animated resize-none"
                ></textarea>
                <div class="flex justify-end mt-3">
                    <button
                        @click="submitComment"
                        :disabled="!newComment.trim() || submitting"
                        class="btn-primary text-sm py-2 px-4 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ submitting ? '发送中...' : '发表评论' }}
                    </button>
                </div>
            </div>
        </div>
        <div v-else class="mb-8 bg-gray-50 dark:bg-gray-800 rounded-xl p-5 text-center border border-gray-100 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                请先
                <router-link to="/login" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">登录</router-link>
                后发表评论
            </p>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
        </div>

        <!-- Comments List -->
        <div v-else class="space-y-4">
            <div v-if="comments.length === 0" class="text-center py-8 text-gray-400 dark:text-gray-500">
                暂无评论，来说点什么吧
            </div>

            <CommentItem
                v-for="comment in comments"
                :key="comment.id"
                :comment="comment"
                :depth="0"
                :article-slug="articleSlug"
                @refresh="fetchComments"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAuth } from '../composables/useAuth';
import { getArticleComments, createComment } from '../api';
import CommentItem from './CommentItem.vue';

const props = defineProps({
    articleSlug: {
        type: String,
        required: true,
    },
});

const { isAuthenticated } = useAuth();

const comments = ref([]);
const loading = ref(true);
const newComment = ref('');
const submitting = ref(false);

const totalCount = computed(() => {
    function countAll(items) {
        let count = 0;
        for (const item of items) {
            count++;
            if (item.replies && item.replies.length > 0) {
                count += countAll(item.replies);
            }
        }
        return count;
    }
    return countAll(comments.value);
});

async function fetchComments() {
    loading.value = true;
    try {
        const response = await getArticleComments(props.articleSlug);
        comments.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch comments:', error);
    } finally {
        loading.value = false;
    }
}

async function submitComment() {
    if (!newComment.value.trim()) return;
    submitting.value = true;
    try {
        await createComment(props.articleSlug, { body: newComment.value });
        newComment.value = '';
        await fetchComments();
    } catch (error) {
        alert(error.response?.data?.message || '评论发送失败');
    } finally {
        submitting.value = false;
    }
}

onMounted(() => {
    fetchComments();
});
</script>
