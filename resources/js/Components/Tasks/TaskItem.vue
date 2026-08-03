<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 TaskItem.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / 個別タスクカード）
 * =====================================================================================
 * 【実務における設計思想】
 * 各タスクの表示、インライン編集、完了/未完了の状態遷移アニメーション、一括選択モード時のチェック制御、
 * および各メタデータ（カテゴリ、重要度、期日、ルーティン状態）の変更メニュー呼び出しを担うコンポーネントです。
 * スッキリとしたスリムなアイコン配置により、視覚的なややこしさを解消しています。
 */

import { categoryTree, priorityConfig } from '@/Constants/task';
import { useTaskItem } from '@/Composables/useTaskItem';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
const props = defineProps({
    // 各タスクの詳細データオブジェクト（タスク名や期日、ステータスなどの情報を表示・操作するために使用）
    task: Object,
    // 一括選択モードが有効かどうかを示すフラグ（各タスク行に選択用チェックボックスを表示制御するために使用）
    isSelectionMode: Boolean,
    // このタスクが現在選択されているかどうかを示すフラグ（チェックボックスの選択状態や行の背景色変更に使用）
    isSelected: Boolean,
    // このタスクがハイライト表示されるべきかどうかを示すフラグ（特定の条件に合致したタスクを視覚的に強調するために使用）
    isHighlighted: Boolean,
    // このタスクが点滅アニメーションの対象かどうかを示すフラグ（更新や操作が行われたタスクをユーザーに注意喚起するために使用）
    isFlashing: Boolean,
});

// --- イベント定義（親やロジック層へ通知するアクション群） ---
const emit = defineEmits([
    'toggle',         // タスクの完了/未完了の切り替え通知
    'select',         // 一括選択モード時のチェック状態切り替え通知
    'delete',         // タスクの削除通知
    'update-title',   // インライン編集によるタイトル更新通知
    'open-menu',      // 各種メタデータ変更用メニュー（アクションモーダル）のオープン通知
    'action-handled'  // アクション処理完了の通知
]);

// --- ロジック層（useTaskItem）からの状態・操作関数のインポート ---
const {
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
} = useTaskItem(props, emit);
</script>

<template>
    <!-- 【動的クラス制御】選択状態、完了アニメーション、新着ハイライト、通常時などのUI状態に応じたスタイルを動的に切り替え -->
    <div 
        @click="handleCardClick"
        :class="[
            'group relative rounded-2xl p-4 sm:p-4.5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] border flex flex-col gap-3 sm:gap-0 sm:flex-row sm:items-center sm:justify-between overflow-hidden transform-gpu',
            
            // 一括選択モード時のスタイル
            isSelectionMode && isSelected 
                ? 'bg-indigo-50/90 border-indigo-400 ring-2 ring-indigo-300/70 shadow-sm cursor-pointer' 
                : '',
            isSelectionMode && !isSelected 
                ? 'bg-slate-50/50 border-indigo-200/80 hover:bg-indigo-50/40 hover:border-indigo-300 cursor-pointer' 
                : '',

            // 完了/復元アニメーション実行中の特殊ボーダー・リング
            isCompleting ? 'border-emerald-300/80 ring-2 ring-emerald-200/60 shadow-2xs' : '',
            isRestoring ? 'border-slate-300/80 ring-2 ring-slate-200/60 shadow-2xs' : '',

            // 新着タスクハイライト演出
            isHighlighted && isFlashing ? 'bg-amber-100/90 border-amber-400 animate-pulse shadow-md' : '',
            isHighlighted && !isFlashing ? 'bg-amber-50/90 border-amber-300 ring-2 ring-amber-200/50' : '',

            // 通常時（完了済みの場合は透明度を下げ、未完了時は標準表示）
            !isSelectionMode && !isCompleting && !isRestoring && !isHighlighted && task.is_completed ? 'bg-slate-50/60 border-slate-200/60 opacity-80 hover:opacity-100 hover:shadow-md' : '',
            !isSelectionMode && !isCompleting && !isRestoring && !isHighlighted && !task.is_completed ? 'bg-white border-slate-200/80 hover:shadow-md' : ''
        ]"
    >
        <!-- ─── 1. タスク完了時の一連の演出オーバーレイ ─── -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-400 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div 
                v-if="isCompleting" 
                class="absolute inset-0 z-30 flex items-center justify-center bg-emerald-50/70 backdrop-blur-[1px] rounded-2xl pointer-events-none border border-emerald-200/40 overflow-hidden"
            >
                <div class="absolute inset-0 bg-emerald-200/20 rounded-full animate-ripple"></div>
                <div class="relative z-10 flex items-center gap-3">
                    <div class="p-1 rounded-full bg-emerald-100/80 text-emerald-600 shadow-xs">
                        <svg class="w-6 h-6 stroke-emerald-600" fill="none" stroke-width="3.5" viewBox="0 0 24 24">
                            <path 
                                class="animate-draw-check" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                d="M4.5 12.75l6 6 9-13.5" 
                            />
                        </svg>
                    </div>
                    <span class="text-sm sm:text-base font-extrabold text-emerald-800 tracking-wide animate-fade-slide">完了しました</span>
                </div>
            </div>
        </Transition>

        <!-- ─── 2. 未完了に戻した際の演出オーバーレイ ─── -->
        <Transition
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-400 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div 
                v-if="isRestoring" 
                class="absolute inset-0 z-30 flex items-center justify-center bg-slate-100/70 backdrop-blur-[1px] rounded-2xl pointer-events-none border border-slate-200/40"
            >
                <div class="flex items-center gap-2.5">
                    <div class="p-1 rounded-full bg-slate-200/80 text-slate-600 animate-spin-reverse">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 016 6v3" />
                        </svg>
                    </div>
                    <span class="text-sm sm:text-base font-extrabold text-slate-700 tracking-wide">未完了に戻しました</span>
                </div>
            </div>
        </Transition>

        <!-- ─── メインコンテンツ領域（左側） ─── -->
        <div class="flex items-center gap-3 min-w-0 flex-1 w-full">
            <!-- 一括選択モード時のチェックボックス -->
            <Transition name="fade">
                <div v-if="isSelectionMode" class="flex items-center justify-center shrink-0">
                    <input 
                        type="checkbox" 
                        :checked="isSelected"
                        @click.stop
                        @change="$emit('select', task.id)"
                        class="rounded-lg border-indigo-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5 cursor-pointer transition shrink-0"
                    />
                </div>
            </Transition>

            <!-- ステータス切替ボタン（完了 / 未完了） -->
            <button 
                @click.stop="handleToggle"
                :disabled="isSelectionMode"
                :title="task.is_completed ? 'クリックで未完了に戻す' : 'クリックでタスク完了にする'"
                :class="[
                    'group/status relative inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-[11px] font-bold border transition-all duration-300 shrink-0 whitespace-nowrap cursor-pointer shadow-2xs active:scale-95',
                    isCompleting ? 'bg-emerald-600 border-emerald-600 text-white' : '',
                    isRestoring ? 'bg-slate-600 border-slate-600 text-white' : '',
                    task.is_completed && !isCompleting && !isRestoring ? 'bg-emerald-600 border-emerald-600 text-white hover:bg-slate-700 hover:border-slate-700' : '',
                    !task.is_completed && !isCompleting && !isRestoring ? 'bg-slate-100 border-slate-300 text-slate-700 hover:bg-emerald-600 hover:border-emerald-600 hover:text-white hover:scale-105 hover:shadow-md hover:shadow-emerald-200/60 hover:ring-2 hover:ring-emerald-300/50' : '',
                    isSelectionMode ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
                ]"
            >
                <template v-if="!task.is_completed && !isCompleting && !isRestoring">
                    <span class="transition-transform duration-200 group-hover/status:scale-110 flex items-center">
                        <span class="group-hover/status:hidden text-slate-400">□</span>
                        <svg class="hidden group-hover/status:inline w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </span>
                    <span>
                        <span class="group-hover/status:hidden">未完了</span>
                        <span class="hidden group-hover/status:inline">完了にする</span>
                    </span>
                </template>
                <template v-else-if="isCompleting || isRestoring">
                    <span>{{ isCompleting ? '✓' : '↩' }}</span>
                    <span>{{ isCompleting ? '完了' : '戻す' }}</span>
                </template>
                <template v-else>
                    <span class="group-hover/status:hidden">✨ 完了</span>
                    <span class="hidden group-hover/status:inline">↩ 未完了に戻す</span>
                </template>
            </button>

            <!-- タスクタイトルとカテゴリバッジのコンテナ -->
            <div class="min-w-0 flex-1 space-y-1">
                <!-- 編集モード時のインライン入力フィールド -->
                <div v-if="editingTaskId === task.id">
                    <input 
                        type="text" 
                        v-model="editingTitle" 
                        @keyup.enter="saveEdit(task)"
                        @keyup.esc="cancelEdit"
                        @blur="saveEdit(task)"
                        autofocus
                        class="w-full text-xs sm:text-sm font-bold border-slate-900 rounded-xl py-1.5 px-3 text-slate-900 shadow-inner bg-white"
                    />
                </div>
                <!-- 通常表示時のタイトル ＆ ルーティンバッジ -->
                <div 
                    v-else 
                    @click.stop="isSelectionMode ? handleCardClick() : startEdit(task)"
                    class="flex items-center gap-2 flex-wrap min-w-0"
                >
                    <span :class="[
                        'text-xs sm:text-sm font-bold leading-normal tracking-tight transition-all duration-700 ease-out py-0.5 break-words min-w-0 cursor-pointer',
                        (task.is_completed || isCompleting) && !isRestoring ? 'line-through text-slate-400 opacity-70' : 'text-slate-900 hover:text-indigo-600'
                    ]">
                        {{ task.title }}
                    </span>

                    <!-- タイトル横のアイコン風ルーティンバッジ -->
                    <span 
                        v-if="task.routine_template_id" 
                        :class="[
                            'flex-shrink-0 inline-flex items-center px-1.5 py-0.5 rounded-md text-[10px] font-bold border',
                            task.routine_template?.is_active === true 
                                ? 'bg-indigo-50 text-indigo-600 border-indigo-200' 
                                : 'bg-slate-100 text-slate-500 border-slate-200'
                        ]"
                        :title="task.routine_template?.is_active === true ? 'ルーティンから生成されたタスク' : '停止中のルーティン'"
                    >
                        🔄 {{ task.routine_template?.is_active === true ? 'ルーティン' : 'ルーティン (停止中)' }}
                    </span>
                </div>

                <!-- カテゴリバッジ -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button 
                        @click.stop="$emit('open-menu', task, 'category', $event)"
                        :class="['inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-xl border transition cursor-pointer active:scale-95 shadow-2xs whitespace-nowrap shrink-0', categoryTree[task.category]?.badgeClass || categoryTree.inbox.badgeClass]"
                    >
                        <span>{{ categoryTree[task.category]?.icon || '📥' }}</span>
                        <span class="text-slate-700">{{ categoryTree[task.category]?.label || '未分類' }}</span>
                        <span class="text-slate-300 font-light">/</span>
                        <span>{{ getSubCategoryMeta(task.category, task.sub_category).icon }}</span>
                        <span class="text-slate-600">{{ getSubCategoryMeta(task.category, task.sub_category).label }}</span>
                        <span class="text-[9px] ml-0.5 text-slate-400 opacity-70">▼</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ─── メタデータ領域（右側） ─── -->
        <div class="flex items-center justify-between sm:justify-end gap-1.5 pt-2 sm:pt-0 border-t border-slate-100 sm:border-t-0 shrink-0 w-full sm:w-auto">
            <div class="flex items-center gap-1.5 flex-wrap">
                <!-- 優先度変更ボタン -->
                <button 
                    @click.stop="$emit('open-menu', task, 'priority', $event)"
                    :class="['px-2.5 py-1 rounded-xl transition cursor-pointer flex items-center gap-1 active:scale-95 shadow-2xs border text-[11px] font-bold whitespace-nowrap shrink-0', priorityConfig[task.priority]?.badgeClass || priorityConfig.medium.badgeClass]"
                >
                    <span>⚡</span>
                    <span>{{ priorityConfig[task.priority]?.label }}</span>
                    <span class="text-[9px] opacity-60">▼</span>
                </button>

                <!-- 期日変更ボタン -->
                <button 
                    @click.stop="$emit('open-menu', task, 'due', $event)"
                    :class="['border rounded-xl px-2.5 py-1 transition flex items-center gap-1 cursor-pointer font-bold active:scale-95 shadow-2xs text-[11px] whitespace-nowrap shrink-0', getDueDateBadgeClass(task.due_date, task.is_completed)]"
                >
                    <span>📅</span>
                    <span>期限: {{ task.due_date }}</span>
                    <span class="text-[9px] opacity-60">▼</span>
                </button>

                <!-- 🔄 ルーティンボタン -->
                <button 
                    @click.stop="$emit('open-menu', task, 'routine', $event)"
                    :class="[
                        'px-2.5 py-1 rounded-xl transition cursor-pointer flex items-center gap-1 active:scale-95 shadow-2xs border text-[11px] font-bold whitespace-nowrap shrink-0',
                        task.routine_template_id && task.routine_template?.is_active === true
                            ? 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100' 
                            : task.routine_template_id && task.routine_template?.is_active === false
                            ? 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200'
                            : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100 hover:text-indigo-600'
                    ]"
                    :title="task.routine_template_id ? (task.routine_template?.is_active === true ? 'ルーティン設定中（クリックで解除）' : 'ルーティン停止中') : 'タスクをルーティン化する'"
                >
                    <span>🔄</span>
                    <span>ルーティン</span>
                </button>
            </div>

            <!-- 削除ボタン -->
            <button 
                @click.stop="$emit('delete', task)"
                class="text-slate-300 hover:text-rose-600 sm:opacity-0 sm:group-hover:opacity-100 transition p-1.5 cursor-pointer rounded-xl hover:bg-rose-50 shrink-0 ml-auto sm:ml-0"
            >
                ✕
            </button>
        </div>
    </div>
</template>

<style scoped>
@keyframes drawCheck {
    0% { stroke-dasharray: 30; stroke-dashoffset: 30; }
    100% { stroke-dasharray: 30; stroke-dashoffset: 0; }
}

@keyframes rippleEffect {
    0% { transform: scale(0.2); opacity: 0.8; }
    100% { transform: scale(2.5); opacity: 0; }
}

@keyframes spinReverse {
    0% { transform: rotate(180deg); }
    100% { transform: rotate(0deg); }
}

.animate-draw-check { animation: drawCheck 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards 0.1s; }
.animate-ripple { animation: rippleEffect 1.2s ease-out forwards; }
.animate-spin-reverse { animation: spinReverse 0.5s ease-out forwards; }
.animate-fade-slide { animation: fadeSlide 0.4s ease-out forwards; }

@keyframes fadeSlide {
    0% { opacity: 0; transform: translateY(4px); }
    100% { opacity: 1; transform: translateY(0); }
}
</style>