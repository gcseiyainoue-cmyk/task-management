/**
 * =====================================================================================
 * 【ファイル名】 useTaskItem.js
 * 【アーキテクチャ上の位置づけ】 ビジネスロジック層（Composables / 個別タスクのインタラクション・アニメーション制御）
 * =====================================================================================
 * 【実務における設計思想】
 * TaskItem.vueから「インライン編集の状態管理」「完了・復元時のリッチなアニメーション（遅延制御）」
 * 「期日の期限切れ判定」「日付やメタデータのフォーマット処理」といったUI固有のローカルロジックを分離しています。
 * Vue 3のComposableパターンを活用することで、コンポーネントの肥大化を防ぎ、
 * ユーザー体験（UX）を高めるための演出ロジックをカプセル化しています。
 */

import { ref } from 'vue';
import { categoryTree, priorityConfig } from '@/Constants/task';

/**
 * 個別タスクのインタラクションおよびアニメーション制御を提供するComposable
 * @param {Object} props - コンポーネントのpropsオブジェクト（task, isSelectionModeを含む）
 * @param {Function} emit - イベント発火用のVue emit関数
 * @returns {Object} 編集状態、アニメーション状態、および各種ハンドラーやフォーマット関数
 */
export function useTaskItem(props, emit) {
    // --- 1. ローカル状態（リアクティブ変数） ---
    const editingTaskId = ref(null); // 現在インライン編集中のタスクID
    const editingTitle = ref('');    // 編集中のタイトル文字列

    // 【UX演出用】段階的アニメーション制御のためのフラグステート（完了時・未完了へ戻す時）
    const isCompleting = ref(false);
    const isRestoring = ref(false);

    /**
     * 【編集開始処理】
     * 指定されたタスクの編集モードに入り、現在のタイトルを入力欄にセットします。
     * @param {Object} task - 編集対象のタスクオブジェクト
     */
    const startEdit = (task) => {
        editingTaskId.value = task.id;
        editingTitle.value = task.title;
    };

    /**
     * 【編集保存処理】
     * 入力内容のバリデーションを行い、変更がある場合のみ親コンポーネントへ保存イベントを発火します。
     * @param {Object} task - 対象のタスクオブジェクト
     */
    const saveEdit = (task) => {
        // 【ガード句】空文字の場合や、変更前とタイトルが同じ場合はそのまま編集モードを終了
        if (!editingTitle.value.trim() || editingTitle.value === task.title) {
            editingTaskId.value = null;
            return;
        }
        emit('action-handled', task);
        emit('update-title', task, editingTitle.value);
        editingTaskId.value = null;
    };

    /**
     * 【編集キャンセル処理】
     */
    const cancelEdit = () => {
        editingTaskId.value = null;
    };

    /**
     * 【一括選択モード時のカードクリック処理】
     * 選択モードが有効な場合、カード全体をクリックすることでチェックボックスのON/OFFをトグルします。
     */
    const handleCardClick = () => {
        if (props.isSelectionMode) {
            emit('select', props.task.id);
        }
    };

    /**
     * 【完了 / 未完了の切り替え処理】
     * 単なる即時反映ではなく、アニメーションや視覚的演出（1.5秒のディレイ）を挟んでから実際のデータ更新を走らせます。
     */
    const handleToggle = () => {
        if (props.isSelectionMode) return;

        if (!props.task.is_completed) {
            // 完了演出の開始
            isCompleting.value = true;
            setTimeout(() => {
                emit('toggle', props.task);
                setTimeout(() => {
                    isCompleting.value = false;
                }, 300);
            }, 1500);
        } else {
            // 未完了に戻す演出の開始
            isRestoring.value = true;
            setTimeout(() => {
                emit('toggle', props.task);
                setTimeout(() => {
                    isRestoring.value = false;
                }, 300);
            }, 1500);
        }
    };

    /**
     * 【サブカテゴリメタデータ取得関数】
     * 親カテゴリとサブカテゴリのキーから、対応するアイコンやラベルを安全に引き当てます。
     * @param {String} category - 親カテゴリのキー
     * @param {String} subCategoryKey - サブカテゴリのキー
     * @returns {Object} 対応するメタデータオブジェクト
     */
    const getSubCategoryMeta = (category, subCategoryKey) => {
        const parent = categoryTree[category] || categoryTree.inbox;
        const found = parent.items.find(i => i.key === subCategoryKey);
        return found || parent.items[0];
    };

    /**
     * 【期日バッジのクラス判定関数】
     * 期限日と現在日を比較し、期限切れ（当日含む）のタスクには警告用のスタイルを返します。
     * @param {String} dueDate - 期限日文字列
     * @param {Boolean} isCompleted - 完了状態かどうか
     * @returns {String} 適用するCSSクラス文字列
     */
    const getDueDateBadgeClass = (dueDate, isCompleted) => {
        if (!dueDate || isCompleted) return 'bg-slate-50 border-slate-200 text-slate-500';
        const today = new Date().toISOString().split('T')[0];
        if (dueDate <= today) return 'bg-rose-50 border-rose-200 text-rose-700 font-bold';
        return 'bg-slate-50 border-slate-200 text-slate-700';
    };

    /**
     * 【作成日時フォーマット関数】
     * バックエンドからのISO日時文字列を、UIで見やすい形式（MM/DD HH:mm）に変換します。
     * @param {String} dateStr - ISO日時文字列
     * @returns {String} フォーマット済み日時文字列
     */
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

    // UIコンポーネント側で必要となる状態および操作関数をすべて外部へ公開します
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