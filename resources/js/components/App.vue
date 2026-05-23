<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />
        <main class="flex-1">
            <router-view />
        </main>
        <footer class="bg-white border-t border-gray-200 py-6">
            <div class="max-w-4xl mx-auto px-4 text-center text-gray-500 text-sm">
                &copy; 2024 My Blog. Built with Laravel &amp; Vue.
            </div>
        </footer>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import Navbar from './Navbar.vue';
import { useAuth } from '../composables/useAuth';

const { checkAuth } = useAuth();

onMounted(() => {
    checkAuth();
});
</script>
