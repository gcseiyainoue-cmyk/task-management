/**
 * =====================================================================================
 * 【ファイル名】 useTaskSelection.js
 * 【アーキテクチャ上の位置づけ】 コンポーザブル層（ビジネスロジック / タスクの選択・一括選択状態管理）
 * =====================================================================================
 * 【実務における設計思想】
 * 複数タスクに対する一括操作（一括削除、一括カテゴリ・重要度・期限変更など）を実現するための
 * 選択モードの状態（`isSelectionMode`）および選択中のタスクIDリスト（`selectedTaskIds`）を管理するビジネスロジックをカプセル化しています。
 * 未完了タスク群（`activeTasks`）や完了済みタスク群（`completedTasksList`）のリアクティブな参照を引数として受け取り、
 * セクションごとの「すべて選択 / 選択解除」トグルや個別の選択切替を効率的に処理することで、
 * プレゼンテーション層のコンポーネントを肥大化させずに高度な一括選択インタラクションを実現しています。
 */

import { ref } from 'vue';

/**
 * タスクの選択状態および一括選択ロジックを提供するComposable
 * @param {Object} activeTasks - 未完了タスクの配列を保持するリアクティブ参照
 * @param {Object} completedTasksList - 完了済みタスクの配列を保持するリアクティブ参照
 * @returns {Object} 選択モードや選択IDリスト、各種選択切り替えハンドラー群
 */
export function useTaskSelection(activeTasks, completedTasksList) {
    // --- ローカルステート（選択モードの有効フラグ、選択されたタスクIDの配列） ---
    const isSelectionMode = ref(false);
    const selectedTaskIds = ref([]);

    /**
     * 選択モード自体の有効・無効を切り替える関数
     * モード解除時は選択IDリストも自動的にリセットされます。
     */
    const toggleSelectionMode = () => {
        isSelectionMode.value = !isSelectionMode.value;
        if (!isSelectionMode.value) selectedTaskIds.value = [];
    };

    /**
     * 個別タスクの選択・非選択状態を切り替える関数
     * @param {Number|String} taskId - 対象のタスクID
     */
    const toggleTaskSelection = (taskId) => {
        const idx = selectedTaskIds.value.indexOf(taskId);
        if (idx > -1) selectedTaskIds.value.splice(idx, 1);
        else selectedTaskIds.value.push(taskId);
    };

    /**
     * 未完了タスクグループ全体の一括選択・解除を切り替える関数
     */
    const toggleSelectActive = () => {
        const activeIds = activeTasks.value.map(t => t.id);
        const allActiveSelected = activeIds.every(id => selectedTaskIds.value.includes(id));
        
        if (allActiveSelected) {
            selectedTaskIds.value = selectedTaskIds.value.filter(id => !activeIds.includes(id));
        } else {
            selectedTaskIds.value = Array.from(new Set([...selectedTaskIds.value, ...activeIds]));
        }
    };

    /**
     * 完了済みタスクグループ全体の一括選択・解除を切り替える関数
     */
    const toggleSelectCompleted = () => {
        const completedIds = completedTasksList.value.map(t => t.id);
        const allCompletedSelected = completedIds.every(id => selectedTaskIds.value.includes(id));
        
        if (allCompletedSelected) {
            selectedTaskIds.value = selectedTaskIds.value.filter(id => !completedIds.includes(id));
        } else {
            selectedTaskIds.value = Array.from(new Set([...selectedTaskIds.value, ...completedIds]));
        }
    };

    // --- 外部公開するステートおよびハンドラーの返却 ---
    return {
        isSelectionMode,
        selectedTaskIds,
        toggleSelectionMode,
        toggleTaskSelection,
        toggleSelectActive,
        toggleSelectCompleted,
    };
}