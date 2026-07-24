// resources/js/Composables/useSmartTaskCreate.js
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export function useSmartTaskCreate(emit) {
    const smartText = ref('');
    const isProcessing = ref(false);

    const handleSmartSubmit = () => {
        if (!smartText.value.trim()) return;

        isProcessing.value = true;

        router.post(route('tasks.store-bulk'), {
            raw_text: smartText.value
        }, {
            onSuccess: () => {
                isProcessing.value = false;
                smartText.value = '';
                emit('close');
            },
            onError: () => {
                isProcessing.value = false;
            }
        });
    };

    return {
        smartText,
        isProcessing,
        handleSmartSubmit,
    };
}