/**
 * =====================================================================================
 * 【ファイル名】 useTaskHighlight.js
 * 【アーキテクチャ上の位置づけ】 コンポーザブル層（ビジネスロジック / タスクのハイライト・アニメーション状態管理）
 * =====================================================================================
 * 【実務における設計思想】
 * Inertia.jsのフラッシュメッセージ（`page.props.flash.new_task_ids`）を監視し、
 * 新規作成されたタスクや、ステータス変更等により移動したタスクに対して視覚的なハイライト
 * （点滅・背景色変更など）を適用・制御するためのビジネスロジックをカプセル化しています。
 * `onMounted` および `watch` を用いてページ遷移やデータ更新時のフラッシュデータを検知し、
 * 一定時間（20秒間）経過後に自動でハイライト状態を解除するタイマー処理を管理することで、
 * ユーザーエクスペリエンス（UX）を向上させる洗練されたインタラクションを実現しています。
 */

import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useTaskHighlight() {
    // --- インステート・ページ情報の取得 ---
    const page = usePage();
    const newIds = ref([]);
    const blinkingMap = ref({});
    const recentlyMovedMap = ref({});

    // --- 新規作成されたタスクのフラッシュハイライト処理 ---
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

    // --- タスク移動時のハイライトトリガー処理 ---
    const triggerMovedHighlight = (taskId) => {
        recentlyMovedMap.value[taskId] = true;
        setTimeout(() => {
            recentlyMovedMap.value[taskId] = false;
        }, 20000);
    };

    // --- ライフサイクルフック：マウント時に初期フラッシュをチェック ---
    onMounted(() => {
        handleFlashNewTasks();
    });

    // --- ウォッチャー：フラッシュメッセージの更新を監視してハイライトを再発火 ---
    watch(() => page.props.flash?.new_task_ids, (newVal) => {
        if (newVal) handleFlashNewTasks();
    }, { deep: true });

    // --- 外部公開するステートおよびハンドラーの返却 ---
    return {
        newIds,
        blinkingMap,
        recentlyMovedMap,
        triggerMovedHighlight,
    };
}