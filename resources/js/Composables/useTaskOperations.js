// @/Composables/useTaskOperations.js
import { router } from '@inertiajs/vue3';

export function useTaskOperations(selectedTaskIds, isSelectionMode, triggerMovedHighlight, closeMenuModal, showToast) {
    
    const bulkUpdate = (payload, successMessage) => {
        if (selectedTaskIds.value.length === 0) return;

        if (!confirm(`選択した ${selectedTaskIds.value.length} 件のタスクを更新しますか？`)) {
            return;
        }

        const targetIds = [...selectedTaskIds.value];
        
        router.patch(route('tasks.bulk-update'), { ids: targetIds, ...payload }, {
            preserveScroll: true,
            onSuccess: () => {
                showToast(successMessage);
                if (payload.is_completed !== undefined) {
                    targetIds.forEach(id => triggerMovedHighlight(id));
                }
                selectedTaskIds.value = [];
                isSelectionMode.value = false;
                closeMenuModal();
            }
        });
    };

    const bulkDelete = () => {
        if (selectedTaskIds.value.length === 0) return;

        if (!confirm(`選択した ${selectedTaskIds.value.length} 件のタスクを削除しますか？`)) {
            return;
        }

        const targetIds = [...selectedTaskIds.value];

        router.delete(route('tasks.bulk-destroy'), {
            data: { ids: targetIds },
            preserveScroll: true,
            onSuccess: () => {
                showToast('選択したタスクを削除しました');
                selectedTaskIds.value = [];
                isSelectionMode.value = false;
                closeMenuModal();
            }
        });
    };

    const toggleTask = (task) => {
        router.patch(route('tasks.update', task.id), { is_completed: !task.is_completed }, { 
            preserveScroll: true,
            onSuccess: () => triggerMovedHighlight(task.id)
        });
    };

    const updateTitle = (task, title) => router.patch(route('tasks.update', task.id), { title }, { preserveScroll: true });

    // 💡 個別：カテゴリ変更時の確認
    const updateCategoryAndSub = (task, category, subCategory) => {
        if (!confirm('このタスクのカテゴリを変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { category, sub_category: subCategory }, { preserveScroll: true });
        closeMenuModal();
    };

    // 💡 個別：重要度変更時の確認
    const updatePriority = (task, priority) => {
        if (!confirm('このタスクの重要度を変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { priority }, { preserveScroll: true });
        closeMenuModal();
    };

    // 💡 個別：期限日変更時の確認
    const updateDueDate = (task, due_date) => {
        if (!confirm('このタスクの期限日を変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { due_date }, { preserveScroll: true });
        closeMenuModal();
    };
    
    const deleteTask = (task) => {
        if (!confirm('このタスクを削除しますか？')) {
            return;
        }
        router.delete(route('tasks.destroy', task.id), { preserveScroll: true });
    };

    return {
        bulkUpdate,
        bulkDelete,
        toggleTask,
        updateTitle,
        updateCategoryAndSub,
        updatePriority,
        updateDueDate,
        deleteTask,
    };
}