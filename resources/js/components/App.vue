<template>
    <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <Navbar v-if="!isAdminRoute" />
        <main class="flex-1">
            <router-view />
        </main>
        <footer v-if="!isAdminRoute" class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-6 transition-colors">
            <div class="max-w-4xl mx-auto px-4 text-center text-gray-500 dark:text-gray-400 text-sm">
                &copy; 2024 My Blog. Built with Laravel &amp; Vue.
            </div>
        </footer>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from './Navbar.vue';
import { useAuth } from '../composables/useAuth';
import { useDarkMode } from '../composables/useDarkMode';

const route = useRoute();
const { checkAuth } = useAuth();
const { initDarkMode } = useDarkMode();

const isAdminRoute = computed(() => route.path.startsWith('/admin'));

onMounted(() => {
    initDarkMode();
    checkAuth();
});
</script>
