// @/Composables/useTaskFilterAndSort.js
import { ref, computed } from 'vue';
import { categoryTree } from '@/Constants/task';

export function useTaskFilterAndSort(props) {
    const searchQuery = ref('');
    const selectedCategoryFilter = ref('all');
    const sortBy = ref('due_date');
    const sortOrder = ref('asc');

    const toggleSortOrder = () => {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    };

    const filteredAndSortedTasks = computed(() => {
        let tasksToSort = [...props.filteredTasks];

        if (selectedCategoryFilter.value !== 'all') {
            tasksToSort = tasksToSort.filter(t => t.category === selectedCategoryFilter.value);
        }

        if (searchQuery.value.trim()) {
            const query = searchQuery.value.toLowerCase();
            tasksToSort = tasksToSort.filter(t => {
                const titleMatch = t.title.toLowerCase().includes(query);
                const dueDateMatch = t.due_date && t.due_date.toLowerCase().includes(query);
                
                const catInfo = categoryTree[t.category];
                const catLabel = catInfo ? catInfo.label.toLowerCase() : '';
                const catKey = t.category ? t.category.toLowerCase() : '';
                const subCatKey = t.sub_category ? t.sub_category.toLowerCase() : '';
                const inboxMatch = t.category === 'inbox' && '未分類'.includes(query);

                return titleMatch || dueDateMatch || catLabel.includes(query) || catKey.includes(query) || subCatKey.includes(query) || inboxMatch;
            });
        }

        const modifier = sortOrder.value === 'asc' ? 1 : -1;

        return tasksToSort.sort((a, b) => {
            if (sortBy.value === 'due_date') {
                if (!a.due_date) return 1;
                if (!b.due_date) return -1;
                const dateCompare = a.due_date.localeCompare(b.due_date);
                if (dateCompare !== 0) return dateCompare * modifier;

                const weights = { high: 3, medium: 2, low: 1 };
                return ((weights[b.priority] || 2) - (weights[a.priority] || 2)) * modifier;

            } else if (sortBy.value === 'priority') {
                const weights = { high: 3, medium: 2, low: 1 };
                const priorityCompare = (weights[b.priority] || 2) - (weights[a.priority] || 2);
                if (priorityCompare !== 0) return priorityCompare * modifier;
                return (a.due_date || '').localeCompare(b.due_date || '') * modifier;

            } else if (sortBy.value === 'created_at') {
                return (a.id - b.id) * modifier;
            }
            return 0;
        });
    });

    const activeTasks = computed(() => filteredAndSortedTasks.value.filter(t => !t.is_completed));
    const completedTasksList = computed(() => filteredAndSortedTasks.value.filter(t => t.is_completed));

    return {
        searchQuery,
        selectedCategoryFilter,
        sortBy,
        sortOrder,
        toggleSortOrder,
        activeTasks,
        completedTasksList,
    };
}