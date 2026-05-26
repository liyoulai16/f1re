import { ref, watch } from 'vue';

const isDark = ref(false);

function applyTheme(dark) {
    if (dark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

function initDarkMode() {
    const stored = localStorage.getItem('theme');
    if (stored === 'dark') {
        isDark.value = true;
    } else if (stored === 'light') {
        isDark.value = false;
    } else {
        isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    applyTheme(isDark.value);
}

export function useDarkMode() {
    function toggleDark() {
        isDark.value = !isDark.value;
        localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
        applyTheme(isDark.value);
    }

    function setDark(value) {
        isDark.value = value;
        localStorage.setItem('theme', value ? 'dark' : 'light');
        applyTheme(value);
    }

    return { isDark, toggleDark, setDark, initDarkMode };
}
