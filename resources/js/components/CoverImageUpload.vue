<template>
    <div class="space-y-3">
        <label class="block text-sm font-medium text-gray-700">封面图片</label>

        <!-- Mode toggle -->
        <div class="flex gap-2">
            <button
                type="button"
                @click="mode = 'url'"
                :class="mode === 'url' ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                class="px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200"
            >
                网络链接
            </button>
            <button
                type="button"
                @click="mode = 'upload'"
                :class="mode === 'upload' ? 'bg-indigo-100 text-indigo-700 border-indigo-300' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'"
                class="px-3 py-1.5 text-xs font-medium rounded-lg border transition-all duration-200"
            >
                本地上传
            </button>
        </div>

        <!-- URL input mode -->
        <div v-if="mode === 'url'">
            <input
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                type="text"
                placeholder="输入图片URL，如 https://example.com/image.jpg"
                class="input-animated"
            />
        </div>

        <!-- File upload mode -->
        <div v-else>
            <div
                class="relative border-2 border-dashed border-gray-200 rounded-xl p-6 text-center transition-all duration-300 hover:border-indigo-300 hover:bg-indigo-50/30 cursor-pointer"
                :class="{ 'border-indigo-400 bg-indigo-50/50': dragover }"
                @dragover.prevent="dragover = true"
                @dragleave="dragover = false"
                @drop.prevent="handleDrop"
                @click="$refs.fileInput.click()"
            >
                <input
                    ref="fileInput"
                    type="file"
                    accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                    class="hidden"
                    @change="handleFileSelect"
                />
                <div v-if="uploading" class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-2"></div>
                    <span class="text-sm text-gray-500">上传中...</span>
                </div>
                <div v-else class="flex flex-col items-center">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-500">点击或拖拽图片到此处上传</span>
                    <span class="text-xs text-gray-400 mt-1">支持 JPG、PNG、GIF、WebP，最大 5MB</span>
                </div>
            </div>
            <div v-if="uploadError" class="text-red-500 text-xs mt-1">{{ uploadError }}</div>
        </div>

        <!-- Preview -->
        <div v-if="modelValue" class="relative">
            <div class="rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                <img
                    :src="modelValue"
                    alt="封面预览"
                    class="w-full h-48 object-cover"
                    @error="imageError = true"
                    @load="imageError = false"
                />
                <div v-if="imageError" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                    <div class="text-center">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs text-gray-400">图片加载失败</span>
                    </div>
                </div>
            </div>
            <button
                type="button"
                @click="$emit('update:modelValue', '')"
                class="absolute top-2 right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-md"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { uploadImage } from '../api';

defineProps({
    modelValue: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue']);

const mode = ref('url');
const uploading = ref(false);
const uploadError = ref('');
const dragover = ref(false);
const imageError = ref(false);

async function doUpload(file) {
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        uploadError.value = '请选择图片文件';
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        uploadError.value = '图片大小不能超过 5MB';
        return;
    }

    uploadError.value = '';
    uploading.value = true;
    try {
        const response = await uploadImage(file);
        emit('update:modelValue', response.data.url);
    } catch (e) {
        uploadError.value = e.response?.data?.message || '上传失败，请重试';
    } finally {
        uploading.value = false;
    }
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    doUpload(file);
    e.target.value = '';
}

function handleDrop(e) {
    dragover.value = false;
    const file = e.dataTransfer.files[0];
    doUpload(file);
}
</script>
