/**
 * =====================================================================================
 * 【ファイル名】 useTaskFilterAndSort.js
 * 【アーキテクチャ上の位置づけ】 ビジネスロジック層（Composables / 検索・絞り込み・ソート計算）
 * =====================================================================================
 * 【実務における設計思想】
 * UIコンポーネントから「膨大なタスク配列のフィルタリング処理」や「複雑な並び替えルール」を完全に分離し、
 * リアクティブな算出プロパティ（computed）としてカプセル化しています。
 * ユーザーの入力（検索窓・セレクトボックス・ソート順）の変化に完全に追従し、
 * パフォーマンスを維持しながら洗練されたデータビューを提供します。
 */

import { ref, computed } from 'vue';
import { categoryTree } from '@/Constants/task';

export function useTaskFilterAndSort(props) {
    // --- 1. 検索・絞り込み・ソートのローカル状態（リアクティブ変数） ---
    const searchQuery = ref('');            // 検索窓に入力された文字列
    const selectedCategoryFilter = ref('all'); // セレクトボックス等で選ばれた詳細カテゴリ絞り込み
    const sortBy = ref('due_date');         // ソート基準（'due_date', 'priority', 'created_at'）
    const sortOrder = ref('asc');           // ソート順（昇順: 'asc' または 降順: 'desc'）

    /**
     * 【ソート順反転関数】
     * 昇順と降順を切り替えるトグル処理を実行します。
     */
    const toggleSortOrder = () => {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    };

    /**
     * 【メインのフィルタリング＆ソート算出プロパティ】
     * データのコピー、カテゴリ絞り込み、多重条件検索、複数軸のソートをチェーンして実行します。
     */
    const filteredAndSortedTasks = computed(() => {
        // 【実務の重要テクニック】propsの直接変異（ミューテーション）を避けるため、スプレッド構文で安全な浅いコピーを作成します
        let tasksToSort = [...props.filteredTasks];

        // ─── Step 1: カテゴリによる絞り込み ───
        if (selectedCategoryFilter.value !== 'all') {
            tasksToSort = tasksToSort.filter(t => t.category === selectedCategoryFilter.value);
        }

        // ─── Step 2: キーワード検索による絞り込み ───
        if (searchQuery.value.trim()) {
            const query = searchQuery.value.toLowerCase();
            tasksToSort = tasksToSort.filter(t => {
                const titleMatch = t.title.toLowerCase().includes(query);
                const dueDateMatch = t.due_date && t.due_date.toLowerCase().includes(query);
                
                const catInfo = categoryTree[t.category];
                const catLabel = catInfo ? catInfo.label.toLowerCase() : '';
                const catKey = t.category ? t.category.toLowerCase() : '';
                const subCatKey = t.sub_category ? t.sub_category.toLowerCase() : '';
                
                // UX配慮：カテゴリが 'inbox' の場合は「未分類」という日本語キーワードでの検索にもヒットさせる
                const inboxMatch = t.category === 'inbox' && '未分類'.includes(query);

                return titleMatch || dueDateMatch || catLabel.includes(query) || catKey.includes(query) || subCatKey.includes(query) || inboxMatch;
            });
        }

        // ─── Step 3: ソート順の適用方向（昇順なら1、降順なら-1） ───
        const modifier = sortOrder.value === 'asc' ? 1 : -1;

        // ─── Step 4: 複数条件に基づく並び替え（ソート） ───
        return tasksToSort.sort((a, b) => {
            if (sortBy.value === 'due_date') {
                // 日付が存在しないタスクはリストの末尾に回す安全ガード
                if (!a.due_date) return 1;
                if (!b.due_date) return -1;
                
                const dateCompare = a.due_date.localeCompare(b.due_date);
                if (dateCompare !== 0) return dateCompare * modifier;

                // 【第2ソートキー】日付が同じ場合は、優先度の高さ（high > medium > low）をサブ条件として比較
                const weights = { high: 3, medium: 2, low: 1 };
                return ((weights[b.priority] || 2) - (weights[a.priority] || 2)) * modifier;

            } else if (sortBy.value === 'priority') {
                const weights = { high: 3, medium: 2, low: 1 };
                const priorityCompare = (weights[b.priority] || 2) - (weights[a.priority] || 2);
                if (priorityCompare !== 0) return priorityCompare * modifier;

                // 【第2ソートキー】優先度が同じ場合は、期限日をサブ条件として比較
                return (a.due_date || '').localeCompare(b.due_date || '') * modifier;

            } else if (sortBy.value === 'created_at') {
                // 登録順（IDの大小）でソート
                return (a.id - b.id) * modifier;
            }
            return 0;
        });
    });

    /**
     * 【未完了タスク配列の切り出し】
     * フィルタ・ソート済みの全リストから、まだ完了していないタスクのみを抽出します。
     */
    const activeTasks = computed(() => filteredAndSortedTasks.value.filter(t => !t.is_completed));

    /**
     * 【完了済みタスク配列の切り出し】
     * フィルタ・ソート済みの全リストから、完了済みのタスクのみを抽出します。
     */
    const completedTasksList = computed(() => filteredAndSortedTasks.value.filter(t => t.is_completed));

    // コンポーネント側で必要となる状態と算出プロパティをすべて外部へ公開します
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