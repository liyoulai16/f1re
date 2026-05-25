<template>
    <div class="min-h-screen flex flex-col">
        <Navbar v-if="!isAdminRoute" />
        <main class="flex-1">
            <router-view />
        </main>
        <footer v-if="!isAdminRoute" class="bg-white border-t border-gray-200 py-6">
            <div class="max-w-4xl mx-auto px-4 text-center text-gray-500 text-sm">
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

const route = useRoute();
const { checkAuth } = useAuth();

const isAdminRoute = computed(() => route.path.startsWith('/admin'));

onMounted(() => {
    checkAuth();
});
</script>
