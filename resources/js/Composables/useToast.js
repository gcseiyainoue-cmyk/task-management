// @/Composables/useToast.js
import { ref } from 'vue';

export function useToast() {
    const toastMessage = ref(null);
    const showToast = (msg) => {
        toastMessage.value = msg;
        setTimeout(() => { toastMessage.value = null; }, 4000);
    };

    return {
        toastMessage,
        showToast,
    };
}