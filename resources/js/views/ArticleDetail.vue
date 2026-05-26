<template>
    <div class="max-w-3xl mx-auto px-4 py-12">
        <div v-if="loading" class="flex justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>

        <div v-else-if="article">
            <router-link to="/" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 mb-8 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                返回首页
            </router-link>

            <article>
                <header class="mb-8">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4 leading-tight">
                        {{ article.title }}
                    </h1>
                    <div class="flex items-center text-sm text-gray-400 dark:text-gray-500">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ formatDate(article.published_at) }}
                        <span v-if="article.user" class="ml-3 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ article.user.name }}
                        </span>
                        <span v-if="article.category" class="ml-3 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 text-xs font-medium rounded-full">
                            {{ article.category.name }}
                        </span>
                    </div>
                </header>

                <!-- Cover Image -->
                <div v-if="article.cover_image" class="mb-8 rounded-xl overflow-hidden shadow-md">
                    <img
                        :src="article.cover_image"
                        :alt="article.title"
                        class="w-full h-64 sm:h-80 object-cover"
                        @error="coverError = true"
                    />
                    <div v-if="coverError" class="w-full h-64 sm:h-80 flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30">
                        <svg class="w-16 h-16 text-indigo-200 dark:text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <div class="prose max-w-none" v-html="article.body"></div>

                <!-- Like & Favorite Actions -->
                <div v-if="isAuthenticated" class="flex items-center space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button
                        @click="handleLike"
                        class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200"
                        :class="isLiked ? 'bg-red-50 dark:bg-red-900/20 text-red-500' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500'"
                    >
                        <svg class="w-5 h-5" :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ likesCount }}</span>
                    </button>
                    <button
                        @click="handleFavorite"
                        class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200"
                        :class="isFavorited ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-500' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 hover:text-yellow-500'"
                    >
                        <svg class="w-5 h-5" :fill="isFavorited ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ isFavorited ? '已收藏' : '收藏' }}</span>
                    </button>
                </div>
                <div v-else class="flex items-center space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center text-sm text-gray-400 dark:text-gray-500">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        {{ likesCount }}
                    </div>
                    <router-link to="/login" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        登录后可点赞和收藏
                    </router-link>
                </div>

                <!-- Comment Section -->
                <CommentSection :article-slug="route.params.slug" />
            </article>
        </div>

        <div v-else class="text-center py-12">
            <p class="text-gray-500 dark:text-gray-400 text-lg">文章未找到</p>
            <router-link to="/" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 mt-4 inline-block">
                返回首页
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { getArticle, toggleArticleLike, toggleArticleFavorite, getArticleInteractions } from '../api';
import { useAuth } from '../composables/useAuth';
import CommentSection from '../components/CommentSection.vue';

const route = useRoute();
const { isAuthenticated } = useAuth();
const article = ref(null);
const loading = ref(true);
const coverError = ref(false);
const isLiked = ref(false);
const isFavorited = ref(false);
const likesCount = ref(0);

async function fetchArticle(slug) {
    loading.value = true;
    coverError.value = false;
    try {
        const response = await getArticle(slug);
        article.value = response.data.data;
        likesCount.value = article.value.likes_count || 0;
        if (isAuthenticated.value) {
            fetchInteractions(slug);
        }
    } catch {
        article.value = null;
    } finally {
        loading.value = false;
    }
}

async function fetchInteractions(slug) {
    try {
        const response = await getArticleInteractions(slug);
        isLiked.value = response.data.is_liked;
        isFavorited.value = response.data.is_favorited;
    } catch {}
}

async function handleLike() {
    try {
        const response = await toggleArticleLike(route.params.slug);
        isLiked.value = response.data.is_liked;
        likesCount.value = response.data.likes_count;
    } catch {}
}

async function handleFavorite() {
    try {
        const response = await toggleArticleFavorite(route.params.slug);
        isFavorited.value = response.data.is_favorited;
    } catch {}
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('zh-CN', { year: 'numeric', month: 'long', day: 'numeric' });
}

onMounted(() => {
    fetchArticle(route.params.slug);
});

watch(() => route.params.slug, (newSlug) => {
    if (newSlug) fetchArticle(newSlug);
});
</script>
