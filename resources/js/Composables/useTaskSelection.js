// @/Composables/useTaskSelection.js
import { ref } from 'vue';

export function useTaskSelection(activeTasks, completedTasksList) {
    const isSelectionMode = ref(false);
    const selectedTaskIds = ref([]);

    const toggleSelectionMode = () => {
        isSelectionMode.value = !isSelectionMode.value;
        if (!isSelectionMode.value) selectedTaskIds.value = [];
    };

    const toggleTaskSelection = (taskId) => {
        const idx = selectedTaskIds.value.indexOf(taskId);
        if (idx > -1) selectedTaskIds.value.splice(idx, 1);
        else selectedTaskIds.value.push(taskId);
    };

    const toggleSelectActive = () => {
        const activeIds = activeTasks.value.map(t => t.id);
        const allActiveSelected = activeIds.every(id => selectedTaskIds.value.includes(id));
        
        if (allActiveSelected) {
            selectedTaskIds.value = selectedTaskIds.value.filter(id => !activeIds.includes(id));
        } else {
            selectedTaskIds.value = Array.from(new Set([...selectedTaskIds.value, ...activeIds]));
        }
    };

    const toggleSelectCompleted = () => {
        const completedIds = completedTasksList.value.map(t => t.id);
        const allCompletedSelected = completedIds.every(id => selectedTaskIds.value.includes(id));
        
        if (allCompletedSelected) {
            selectedTaskIds.value = selectedTaskIds.value.filter(id => !completedIds.includes(id));
        } else {
            selectedTaskIds.value = Array.from(new Set([...selectedTaskIds.value, ...completedIds]));
        }
    };

    return {
        isSelectionMode,
        selectedTaskIds,
        toggleSelectionMode,
        toggleTaskSelection,
        toggleSelectActive,
        toggleSelectCompleted,
    };
}