/**
 * =====================================================================================
 * 【ファイル名】 useTaskOperations.js
 * 【アーキテクチャ上の位置づけ】 ビジネスロジック層（Composables / データ操作・API通信）
 * =====================================================================================
 * 【実務における設計思想】
 * UIコンポーネント（Dashboard.vueなど）から「サーバー通信の詳細」や「確認ダイアログの制御」
 * を完全に切り離し、単一責任の原則（SRP）に基づいたCRUD・一括処理ロジックを集約しています。
 * Inertia.jsの `router` を活用し、画面のリロードを伴わない非同期通信とスクロール位置の維持を実現します。
 */

import { router } from '@inertiajs/vue3';

/**
 * タスクに関するデータ操作およびAPI通信ロジックを提供するComposable
 * @param {Object} selectedTaskIds - 選択中のタスクIDリストのリアクティブ参照
 * @param {Object} isSelectionMode - 選択モードの有効状態を示すリアクティブ参照
 * @param {Function} triggerMovedHighlight - タスク移動時のハイライトトリガー関数
 * @param {Function} closeMenuModal - メニューモーダルを閉じるための関数
 * @param {Function} showToast - トースト通知を表示するための関数
 * @returns {Object} 各種タスク操作および一括処理のハンドラー関数群
 */
export function useTaskOperations(selectedTaskIds, isSelectionMode, triggerMovedHighlight, closeMenuModal, showToast) {
    
    /**
     * 【複数タスク一括更新関数】
     * 選択されている複数のタスクに対して、共通のペイロード（変更内容）を一度に送信します。
     * @param {Object} payload - サーバーへ送信する変更データのオブジェクト（例: { is_completed: true }）
     * @param {String} successMessage - 成功時にトースト通知に表示するメッセージ
     */
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

    /**
     * 【複数タスク一括削除関数】
     * 選択されている複数のタスクを一括してデータベースから削除します。
     */
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

    /**
     * 【単一タスクの完了/未完了トグル関数】
     * チェックボックスのクリック等により、タスクの完了状態を反転させてサーバーへ即時同期します。
     * @param {Object} task - 操作対象のタスクオブジェクト
     */
    const toggleTask = (task) => {
        router.patch(route('tasks.update', task.id), { is_completed: !task.is_completed }, { 
            preserveScroll: true,
            onSuccess: () => triggerMovedHighlight(task.id)
        });
    };

    /**
     * 【タスク名インライン編集更新関数】
     * @param {Object} task - 対象のタスクオブジェクト
     * @param {String} title - 新しいタスク名
     */
    const updateTitle = (task, title) => router.patch(route('tasks.update', task.id), { title }, { preserveScroll: true });

    /**
     * 【個別：カテゴリ変更処理】
     * @param {Object} task - 対象のタスクオブジェクト
     * @param {String} category - 新しい親カテゴリキー
     * @param {String} subCategory - 新しいサブカテゴリキー
     */
    const updateCategoryAndSub = (task, category, subCategory) => {
        if (!confirm('このタスクのカテゴリを変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { category, sub_category: subCategory }, { preserveScroll: true });
        closeMenuModal();
    };

    /**
     * 【個別：重要度（優先度）変更処理】
     * @param {Object} task - 対象のタスクオブジェクト
     * @param {String} priority - 新しい優先度（'high', 'medium', 'low'）
     */
    const updatePriority = (task, priority) => {
        if (!confirm('このタスクの重要度を変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { priority }, { preserveScroll: true });
        closeMenuModal();
    };

    /**
     * 【個別：期限日変更処理】
     * @param {Object} task - 対象のタスクオブジェクト
     * @param {String} due_date - 新しい期限日文字列
     */
    const updateDueDate = (task, due_date) => {
        if (!confirm('このタスクの期限日を変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { due_date }, { preserveScroll: true });
        closeMenuModal();
    };
    
    /**
     * 【単一タスク削除関数（ルーティン判定・専用ダイアログ対応版）】
     * @param {Object|Number|String} taskOrId - タスクオブジェクト、またはタスクID
     * @param {Boolean} [skipConfirm=false] - 確認ダイアログをスキップするかどうか
     * @returns {Promise<Boolean>} 削除成功時はtrue、キャンセル時はfalseを返すPromise
     */
    const deleteTask = (taskOrId, skipConfirm = false) => {
        return new Promise((resolve, reject) => {
            // オブジェクトとして渡されているか判定し、プロパティを抽出する
            const task = (typeof taskOrId === 'object' && taskOrId !== null) ? taskOrId : null;
            const id = task ? task.id : taskOrId;
            const routineTemplateId = task ? task.routine_template_id : null;
            
            if (!id) {
                console.error('削除対象のIDが特定できません:', taskOrId);
                return reject(new Error('Invalid task ID'));
            }

            if (!skipConfirm) {
                let message = 'このタスクを削除しますか？';
                
                // ▼ ここでルーティン由来のタスクか判定してメッセージを切り替える
                if (routineTemplateId) {
                    message = '【確認】このタスクはルーティンから生成されています。\n\n大元のルーティン設定も一緒に削除され、今後自動生成されなくなりますが、本当に削除しますか？';
                }

                if (!confirm(message)) {
                    return resolve(false);
                }
            }

            router.delete(route('tasks.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    showToast('タスクを削除しました');
                    resolve(true);
                },
                onError: (errors) => {
                    console.error('Delete task error:', errors);
                    showToast('削除に失敗しました');
                    reject(errors);
                }
            });
        });
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