<template>
    <div class="quill-editor-wrapper">
        <QuillEditorComp
            ref="quillRef"
            theme="snow"
            :content="modelValue"
            content-type="html"
            :placeholder="placeholder"
            :toolbar="toolbarOptions"
            @update:content="handleUpdate"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { QuillEditor as QuillEditorComp } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { uploadEditorImage } from '../api';

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: '输入内容...' },
});

const emit = defineEmits(['update:modelValue']);

const quillRef = ref(null);

const toolbarOptions = [
    [{ header: [1, 2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ color: [] }, { background: [] }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['blockquote', 'code-block'],
    ['link', 'image'],
    ['clean'],
];

function handleUpdate(content) {
    emit('update:modelValue', content === '<p><br></p>' ? '' : content);
}
</script>

<style scoped>
.quill-editor-wrapper :deep(.ql-toolbar) {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    background: #f9fafb;
    border-color: #e5e7eb;
}

.quill-editor-wrapper :deep(.ql-toolbar button) {
    display: inline-block;
    width: 28px;
    height: 24px;
}

.quill-editor-wrapper :deep(.ql-toolbar svg) {
    display: inline;
    height: 100%;
    width: auto;
    max-width: none;
    max-height: none;
}

.quill-editor-wrapper :deep(.ql-container) {
    min-height: 300px;
    font-size: 15px;
    border-bottom-left-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
    border-color: #e5e7eb;
}

.quill-editor-wrapper :deep(.ql-editor) {
    min-height: 300px;
    line-height: 1.8;
}

.quill-editor-wrapper :deep(.ql-editor.ql-blank::before) {
    font-style: normal;
    color: #9ca3af;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-toolbar) {
    background: #1f2937;
    border-color: #4b5563;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-container) {
    background: #1f2937;
    border-color: #4b5563;
    color: #f3f4f6;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-editor.ql-blank::before) {
    color: #6b7280;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-stroke) {
    stroke: #d1d5db;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-fill) {
    fill: #d1d5db;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-picker-label) {
    color: #d1d5db;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-picker-options) {
    background: #374151;
    border-color: #4b5563;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-picker-item) {
    color: #d1d5db;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-snow .ql-tooltip) {
    background: #374151;
    border-color: #4b5563;
    color: #d1d5db;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-snow .ql-tooltip input[type=text]) {
    background: #1f2937;
    border-color: #4b5563;
    color: #f3f4f6;
}

:is(.dark) .quill-editor-wrapper :deep(.ql-editor p) {
    color: #f3f4f6;
}
</style>
