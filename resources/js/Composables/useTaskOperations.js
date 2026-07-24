/**
 * =====================================================================================
 * 【ファイル名】 useTaskOperations.js
 * 【アーキテクチャ上の位置づけ】 ビジネスロジック層（Composables / データ操作・API通信）
 * =====================================================================================
 * 【実務における設計思想】
 * UIコンポーネント（Index.vueなど）から「サーバー通信の詳細」や「確認ダイアログの制御」
 * を完全に切り離し、単一責任の原則（SRP）に基づいたCRUD・一括処理ロジックを集約しています。
 * Inertia.jsの `router` を活用し、画面のリロードを伴わない非同期通信とスクロール位置の維持を実現します。
 */

import { router } from '@inertiajs/vue3';

export function useTaskOperations(selectedTaskIds, isSelectionMode, triggerMovedHighlight, closeMenuModal, showToast) {
    
    /**
     * 【複数タスク一括更新関数】
     * 選択されている複数のタスクに対して、共通のペイロード（変更内容）を一度に送信します。
     * @param {Object} payload - サーバーへ送信する変更データのオブジェクト（例: { is_completed: true }）
     * @param {String} successMessage - 成功時にトースト通知に表示するメッセージ
     */
    const bulkUpdate = (payload, successMessage) => {
        // 【ガード句】選択中のタスクが0件の場合は処理を中断
        if (selectedTaskIds.value.length === 0) return;

        // 【UX安全網】一括変更時は意図せぬ誤操作を防ぐため、ブラウザ標準の確認ダイアログを挟みます
        if (!confirm(`選択した ${selectedTaskIds.value.length} 件のタスクを更新しますか？`)) {
            return;
        }

        // 【実務の重要テクニック】リアクティブ配列をそのまま非同期処理に渡すと、
        // 途中で選択解除された場合に参照が狂うため、スプレッド構文で現在のID群を「スナップショット（複製）」として固定します。
        const targetIds = [...selectedTaskIds.value];
        
        router.patch(route('tasks.bulk-update'), { ids: targetIds, ...payload }, {
            preserveScroll: true, // 【UX向上】通信完了後も現在のスクロール位置を維持
            onSuccess: () => {
                showToast(successMessage);
                
                // 完了状態の変更を伴う一括処理の場合、該当するタスク群にハイライト・点滅アニメーションを適用
                if (payload.is_completed !== undefined) {
                    targetIds.forEach(id => triggerMovedHighlight(id));
                }
                
                // 処理成功後は一括選択の状態をクリアし、選択モードを終了してモーダルを閉じます
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

        // InertiaのDELETEリクエストでは、データペイロードを明示的に `data` プロパティに包んで渡す必要があります
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
            onSuccess: () => triggerMovedHighlight(task.id) // リスト間で移動した際に視覚的なハイライトをトリガー
        });
    };

    /**
     * 【タスク名インライン編集更新関数】
     * リスト上でタスク名が直接書き換えられた際に、バックエンドへ新しいタイトルを保存します。
     */
    const updateTitle = (task, title) => router.patch(route('tasks.update', task.id), { title }, { preserveScroll: true });

    /**
     * 【個別：カテゴリ変更処理】
     * タスクのカテゴリおよびサブカテゴリを変更し、モーダルを閉じる一連のフローを実行します。
     */
    const updateCategoryAndSub = (task, category, subCategory) => {
        if (!confirm('このタスクのカテゴリを変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { category, sub_category: subCategory }, { preserveScroll: true });
        closeMenuModal();
    };

    /**
     * 【個別：重要度（優先度）変更処理】
     */
    const updatePriority = (task, priority) => {
        if (!confirm('このタスクの重要度を変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { priority }, { preserveScroll: true });
        closeMenuModal();
    };

    /**
     * 【個別：期限日変更処理】
     */
    const updateDueDate = (task, due_date) => {
        if (!confirm('このタスクの期限日を変更しますか？')) return;
        router.patch(route('tasks.update', task.id), { due_date }, { preserveScroll: true });
        closeMenuModal();
    };
    
    /**
     * 【単一タスク削除関数】
     */
    const deleteTask = (task) => {
        if (!confirm('このタスクを削除しますか？')) {
            return;
        }
        router.delete(route('tasks.destroy', task.id), { preserveScroll: true });
    };

    // コンポーネント側で利用できるようにすべての操作関数を外部へ公開します
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