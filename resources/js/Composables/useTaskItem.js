// resources/js/Composables/useTaskItem.js
import { ref } from 'vue';
import { categoryTree, priorityConfig } from '@/Constants/task';

export function useTaskItem(props, emit) {
    const editingTaskId = ref(null);
    const editingTitle = ref('');

    // 段階的アニメーション用のステート（完了時・戻し時）
    const isCompleting = ref(false);
    const isRestoring = ref(false);

    const startEdit = (task) => {
        editingTaskId.value = task.id;
        editingTitle.value = task.title;
    };

    const saveEdit = (task) => {
        if (!editingTitle.value.trim() || editingTitle.value === task.title) {
            editingTaskId.value = null;
            return;
        }
        emit('action-handled', task);
        emit('update-title', task, editingTitle.value);
        editingTaskId.value = null;
    };

    const cancelEdit = () => {
        editingTaskId.value = null;
    };

    // 一括選択モード時のカード全体のクリック処理
    const handleCardClick = () => {
        if (props.isSelectionMode) {
            emit('select', props.task.id);
        }
    };

    // 完了/未完了の切り替え処理（1.5秒のリッチ演出）
    const handleToggle = () => {
        if (props.isSelectionMode) return;

        if (!props.task.is_completed) {
            isCompleting.value = true;
            setTimeout(() => {
                emit('toggle', props.task);
                setTimeout(() => {
                    isCompleting.value = false;
                }, 300);
            }, 1500);
        } else {
            isRestoring.value = true;
            setTimeout(() => {
                emit('toggle', props.task);
                setTimeout(() => {
                    isRestoring.value = false;
                }, 300);
            }, 1500);
        }
    };

    const getSubCategoryMeta = (category, subCategoryKey) => {
        const parent = categoryTree[category] || categoryTree.inbox;
        const found = parent.items.find(i => i.key === subCategoryKey);
        return found || parent.items[0];
    };

    const getDueDateBadgeClass = (dueDate, isCompleted) => {
        if (!dueDate || isCompleted) return 'bg-slate-50 border-slate-200 text-slate-500';
        const today = new Date().toISOString().split('T')[0];
        if (dueDate <= today) return 'bg-rose-50 border-rose-200 text-rose-700 font-bold';
        return 'bg-slate-50 border-slate-200 text-slate-700';
    };

    const formatCreatedAt = (dateStr) => {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return dateStr;

        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');

        return `${month}/${day} ${hours}:${minutes}`;
    };

    return {
        categoryTree,
        priorityConfig,
        editingTaskId,
        editingTitle,
        isCompleting,
        isRestoring,
        startEdit,
        saveEdit,
        cancelEdit,
        handleCardClick,
        handleToggle,
        getSubCategoryMeta,
        getDueDateBadgeClass,
        formatCreatedAt,
    };
}