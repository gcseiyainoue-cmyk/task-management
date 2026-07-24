// @/Composables/useTaskHighlight.js
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useTaskHighlight() {
    const page = usePage();
    const newIds = ref([]);
    const blinkingMap = ref({});
    const recentlyMovedMap = ref({});

    const handleFlashNewTasks = () => {
        const flashIds = page.props.flash?.new_task_ids;
        if (Array.isArray(flashIds) && flashIds.length > 0) {
            newIds.value = flashIds;
            flashIds.forEach(id => {
                blinkingMap.value[id] = true;
                setTimeout(() => {
                    blinkingMap.value[id] = false;
                }, 20000);
            });
        }
    };

    const triggerMovedHighlight = (taskId) => {
        recentlyMovedMap.value[taskId] = true;
        setTimeout(() => {
            recentlyMovedMap.value[taskId] = false;
        }, 20000);
    };

    onMounted(() => {
        handleFlashNewTasks();
    });

    watch(() => page.props.flash?.new_task_ids, (newVal) => {
        if (newVal) handleFlashNewTasks();
    }, { deep: true });

    return {
        newIds,
        blinkingMap,
        recentlyMovedMap,
        triggerMovedHighlight,
    };
}