<template>
    <div :class="depth > 0 ? 'ml-6 border-l-2 border-indigo-100 dark:border-indigo-800 pl-4' : ''">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
            <!-- Comment Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold mr-2.5">
                        {{ comment.user?.name?.charAt(0) }}
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ comment.user?.name }}</span>
                        <span v-if="replyToName" class="text-xs text-gray-400 dark:text-gray-500 mx-1">回复</span>
                        <span v-if="replyToName" class="text-xs text-indigo-500 dark:text-indigo-400 font-medium">@{{ replyToName }}</span>
                        <span class="text-xs text-gray-400 dark:text-gray-500 ml-2">{{ relativeTime(comment.created_at) }}</span>
                    </div>
                </div>
                <button
                    v-if="canDelete"
                    @click="handleDelete"
                    class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                >
                    删除
                </button>
            </div>

            <!-- Comment Body -->
            <p class="text-sm text-gray-700 dark:text-gray-300 mt-3 leading-relaxed whitespace-pre-wrap">{{ comment.body }}</p>

            <!-- Reply Button -->
            <div v-if="isAuthenticated && depth < 3" class="mt-3">
                <button
                    @click="toggleReply"
                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium transition-colors"
                >
                    回复
                </button>
            </div>

            <!-- Inline Reply Form -->
            <div v-if="showReplyForm" class="mt-4">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <textarea
                        v-model="replyContent"
                        rows="2"
                        :placeholder="`回复 ${comment.user?.name}...`"
                        class="input-animated resize-none text-sm"
                    ></textarea>
                    <div class="flex items-center gap-2 mt-2">
                        <button
                            @click="submitReply"
                            :disabled="!replyContent.trim() || submitting"
                            class="btn-primary text-xs py-1.5 px-3 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ submitting ? '发送中...' : '发送' }}
                        </button>
                        <button
                            @click="toggleReply"
                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
                        >
                            取消
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nested Replies -->
        <div v-if="comment.replies && comment.replies.length > 0" class="mt-3 space-y-3">
            <CommentItem
                v-for="reply in comment.replies"
                :key="reply.id"
                :comment="reply"
                :depth="depth + 1"
                :article-slug="articleSlug"
                :reply-to-name="comment.user?.name"
                @refresh="$emit('refresh')"
            />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuth } from '../composables/useAuth';
import { createComment, deleteComment } from '../api';

const props = defineProps({
    comment: { type: Object, required: true },
    depth: { type: Number, default: 0 },
    articleSlug: { type: String, required: true },
    replyToName: { type: String, default: '' },
});

const emit = defineEmits(['refresh']);

const { user, isAuthenticated } = useAuth();

const showReplyForm = ref(false);
const replyContent = ref('');
const submitting = ref(false);

const canDelete = ref(
    user.value && (user.value.id === props.comment.user_id || user.value.role === 'admin')
);

function toggleReply() {
    showReplyForm.value = !showReplyForm.value;
    replyContent.value = '';
}

async function submitReply() {
    if (!replyContent.value.trim()) return;
    submitting.value = true;
    try {
        await createComment(props.articleSlug, {
            body: replyContent.value,
            parent_id: props.comment.id,
        });
        replyContent.value = '';
        showReplyForm.value = false;
        emit('refresh');
    } catch (error) {
        alert(error.response?.data?.message || '回复发送失败');
    } finally {
        submitting.value = false;
    }
}

async function handleDelete() {
    if (!confirm('确定要删除此评论吗？')) return;
    try {
        await deleteComment(props.comment.id);
        emit('refresh');
    } catch (error) {
        alert(error.response?.data?.message || '删除失败');
    }
}

function relativeTime(dateStr) {
    if (!dateStr) return '';
    const now = new Date();
    const date = new Date(dateStr);
    const diff = Math.floor((now - date) / 1000);

    if (diff < 60) return '刚刚';
    if (diff < 3600) return `${Math.floor(diff / 60)}分钟前`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}小时前`;
    if (diff < 2592000) return `${Math.floor(diff / 86400)}天前`;
    return date.toLocaleDateString('zh-CN');
}
</script>
