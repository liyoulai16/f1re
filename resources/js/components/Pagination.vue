<template>
    <div v-if="totalPages > 0" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-4 animate-fade-in-up">
        <!-- Left: info and per-page selector -->
        <div class="flex items-center gap-4 text-sm text-gray-500">
            <span>共 <span class="font-medium text-gray-700">{{ total }}</span> 条</span>
            <div class="flex items-center gap-2">
                <span>每页</span>
                <select
                    :value="perPage"
                    @change="handlePerPageChange"
                    class="px-2 py-1 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300 hover:border-indigo-300 cursor-pointer"
                >
                    <option v-for="opt in perPageOptions" :key="opt" :value="opt">{{ opt }}</option>
                </select>
                <span>条</span>
            </div>
        </div>

        <!-- Center: page buttons -->
        <nav class="flex items-center space-x-1">
            <!-- Previous -->
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-2 rounded-lg text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-300 hover:-translate-x-0.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Page numbers -->
            <template v-for="page in displayedPages" :key="page">
                <span v-if="page === '...'" class="px-2 py-2 text-gray-400">...</span>
                <button
                    v-else
                    @click="goToPage(page)"
                    :class="page === currentPage
                        ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200 scale-105'
                        : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-600'"
                    class="w-9 h-9 rounded-lg font-medium text-sm transition-all duration-300"
                >
                    {{ page }}
                </button>
            </template>

            <!-- Next -->
            <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-2 rounded-lg text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 disabled:opacity-40 disabled:cursor-not-allowed transition-all duration-300 hover:translate-x-0.5"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </nav>

        <!-- Right: jump to page -->
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <span>跳至</span>
            <input
                v-model="jumpPage"
                type="number"
                :min="1"
                :max="totalPages"
                @keyup.enter="handleJump"
                class="w-14 px-2 py-1 border border-gray-200 rounded-lg text-sm text-center focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all duration-300 hover:border-indigo-300"
            />
            <span>页</span>
            <button
                @click="handleJump"
                class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-all duration-300 font-medium hover:shadow-sm"
            >
                GO
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    currentPage: { type: Number, required: true },
    totalPages: { type: Number, required: true },
    total: { type: Number, required: true },
    perPage: { type: Number, default: 10 },
    perPageOptions: { type: Array, default: () => [5, 10, 15, 20, 30, 50] },
});

const emit = defineEmits(['page-change', 'per-page-change']);

const jumpPage = ref('');

const displayedPages = computed(() => {
    const total = props.totalPages;
    const current = props.currentPage;
    const pages = [];

    if (total <= 7) {
        for (let i = 1; i <= total; i++) pages.push(i);
        return pages;
    }

    pages.push(1);

    if (current > 3) {
        pages.push('...');
    }

    const start = Math.max(2, current - 1);
    const end = Math.min(total - 1, current + 1);

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    if (current < total - 2) {
        pages.push('...');
    }

    pages.push(total);

    return pages;
});

function goToPage(page) {
    if (page < 1 || page > props.totalPages || page === props.currentPage) return;
    emit('page-change', page);
}

function handlePerPageChange(e) {
    emit('per-page-change', parseInt(e.target.value));
}

function handleJump() {
    const page = parseInt(jumpPage.value);
    if (!page || page < 1 || page > props.totalPages) return;
    goToPage(page);
    jumpPage.value = '';
}
</script>
