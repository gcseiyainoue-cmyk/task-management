<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 TaskListSection.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / タスクリストセクション・グループ）
 * =====================================================================================
 * 【実務における設計思想】
 * タスクのグループ（未完了セクションや期限切れセクションなど）単位でリストを表示するコンポーネントです。
 * ヘッダー部分にはセクション名と動的なタスク件数、および一括選択モード時の「すべて選択 / 選択解除」ボタンを配置し、
 * リスト部分にはVueの `<TransitionGroup>` を採用してタスクの追加・削除・移動（並び替え）時における
 * スムーズで洗練されたイージングアニメーション（FLIPアニメーション等）を実現しています。
 * 子コンポーネントである `TaskItem` へ必要な状態（選択状態、ハイライト状態、点滅状態など）を的確に伝播させ、
 * 各種イベントも適切に親コンポーネントへバブリングします。
 */

import TaskItem from '@/Components/Tasks/TaskItem.vue';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
defineProps({
    // セクションの見出し文字列（タスクリストのグループ名やカテゴリ名を表示するために使用）
    title: String,
    // 表示対象のタスクデータの配列（セクション内に属するタスク一覧をレンダリングするために使用）
    tasks: {
        type: Array,
        default: () => [],
    },
    // 一括選択モードが有効かどうかを示すフラグ（各タスク行のチェックボックス表示や一括操作機能を制御するために使用）
    isSelectionMode: {
        type: Boolean,
        default: false,
    },
    // 現在選択されているタスクのID群の配列（一括操作の対象管理やチェックボックスの選択状態を判定するために使用）
    selectedTaskIds: {
        type: Array,
        default: () => [],
    },
    // 新規追加されたタスクのID群の配列（新しく追加されたタスクを視覚的に強調表示するために使用）
    newIds: {
        type: Array,
        default: () => [],
    },
    // 最近移動されたタスクのIDをキーに持つマップ（移動アニメーションやハイライト状態を制御するために使用）
    recentlyMovedMap: {
        type: Object,
        default: () => ({}),
    },
    // 点滅演出の対象となるタスクのIDをキーに持つマップ（ユーザーに注意喚起が必要なタスクを点滅させるために使用）
    blinkingMap: {
        type: Object,
        default: () => ({}),
    },
    // 「すべて選択」ボタンを表示するかどうかを示すフラグ（一括選択時の全選択・全解除アクションの導線を制御するために使用）
    showSelectAllButton: {
        type: Boolean,
        default: false,
    },
    // 現在のセクション内のタスクがすべて選択されているかどうかを示すフラグ（「すべて選択」ボタンのチェック状態や表示切り替えに使用）
    isAllSelected: {
        type: Boolean,
        default: false,
    },
});

// --- イベント定義（一括選択切替、単体選択、削除、タイトル更新、メニューオープン等の通知） ---
defineEmits([
    'toggleSelectAll',   // 一括選択/解除の切り替え
    'toggle',            // タスクの完了/未完了の切り替え
    'select',            // タスクの個別の選択/非選択の切り替え
    'delete',            // タスクの削除
    'updateTitle',       // タスクタイトルのインライン更新
    'openMenu'           // 各種操作メニュー（アクションモーダル）のオープン
]);
</script>

<template>
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-sm space-y-3">
        <!-- ─── セクションヘッダー（件数 ＆ 一括選択トグルボタン） ─── -->
        <div class="flex items-center justify-between text-xs font-bold text-slate-400 px-1 pb-1">
            <span>{{ title }} ({{ tasks.length }})</span>
            <button 
                v-if="isSelectionMode && showSelectAllButton && tasks.length > 0"
                @click="$emit('toggleSelectAll')"
                class="text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-lg transition text-[11px] cursor-pointer"
            >
                {{ isAllSelected ? '選択を解除' : 'すべて選択' }}
            </button>
        </div>

        <!-- ─── タスクが空の場合のプレースホルダー表示 ─── -->
        <div v-if="tasks.length === 0" class="text-center py-16 text-slate-400 text-xs font-medium space-y-1">
            <p class="text-base">🎉</p>
            <p>表示するタスクはありません</p>
        </div>

        <!-- ─── タスクカードリスト（トランジションアニメーション付き） ─── -->
        <TransitionGroup 
            name="task-list" 
            tag="div" 
            class="relative space-y-2.5"
        >
            <TaskItem 
                v-for="task in tasks" 
                :key="task.id"
                :task="task"
                :is-selection-mode="isSelectionMode"
                :is-selected="selectedTaskIds?.includes(task.id)"
                :is-highlighted="newIds?.includes(task.id) || recentlyMovedMap?.[task.id]"
                :is-flashing="blinkingMap?.[task.id] || recentlyMovedMap?.[task.id]"
                @toggle="$emit('toggle', $event)"
                @select="$emit('select', $event)"
                @delete="$emit('delete', $event)"
                @update-title="(t, title) => $emit('updateTitle', t, title)"
                @open-menu="(task, type, event) => $emit('openMenu', task, type, event)"
            />
        </TransitionGroup>
    </div>
</template>

<style scoped>
/* --- リストトランジション・アニメーション定義 --- */
.task-list-move {
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.task-list-enter-active,
.task-list-leave-active {
    transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.task-list-enter-from {
    opacity: 0;
    transform: translateY(-16px) scale(0.96);
}
.task-list-leave-to {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}
.task-list-leave-active {
    position: absolute !important;
    width: 100%;
    z-index: 0;
}
</style>