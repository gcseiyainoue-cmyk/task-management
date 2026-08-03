<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 RoutineListSection.vue
 * 【アーキテクチャ上の位置づけ】 フロントエンド層（Vue 3 SFC / ルーティンテンプレート一覧表示コンポーネント）
 * =====================================================================================
 * 【実務における設計思想】
 * 登録されているルーティンテンプレートの一覧をカード形式でリスト表示します。
 * 各テンプレートのカテゴリ、サブカテゴリ、優先度、および頻度（何日毎・曜日毎）のメタデータを
 * 定数定義から安全に解決して視覚化するほか、有効/無効の切り替え、編集、削除などの
 * 各種操作イベントを親コンポーネントへ安全に伝播させます。
 */

import { categoryTree, priorityConfig } from '@/Constants/task';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
defineProps({
    // 登録されているルーティンテンプレートの配列（ルーティン一覧の表示や項目ごとの描画に使用）
    routineTemplates: Array,
    // ルーティンテンプレートの総件数（ヘッダーやカウンターでの件数表示に使用）
    totalCount: Number,
});

// 親コンポーネントへ発火する各種イベントの定義
const emit = defineEmits([
    'toggle', // ルーティンテンプレートの有効/停止状態の切り替えを親へ通知
    'edit',   // ルーティンテンプレートの編集モーダル表示を親へ通知
    'delete'  // ルーティンテンプレートの削除処理を親へ通知
]);

/**
 * カテゴリキーから対応するメタ情報（ラベル、アイコン、バッジスタイル）を取得する関数
 */
const getCategoryMeta = (catKey) => {
    return categoryTree[catKey] || { 
        label: catKey, 
        icon: '📂', 
        badgeClass: 'bg-slate-100 text-slate-700 border-slate-200' 
    };
};

/**
 * サブカテゴリキーから対応するメタ情報（ラベル、アイコン）を取得する関数
 */
const getSubCategoryMeta = (catKey, subKey) => {
    const category = categoryTree[catKey];
    if (!category || !category.items) return { label: subKey, icon: '📌' };
    const sub = category.items.find(item => item.key === subKey);
    return sub || { label: subKey, icon: '📌' };
};

/**
 * 優先度に応じたメタ情報（ラベル、バッジスタイル）を取得する関数
 */
const getPriorityMeta = (priority) => {
    return priorityConfig[priority] || priorityConfig.medium;
};

/**
 * 頻度タイプに応じた表示用メタ情報（ラベル、アイコン）を取得する関数
 */
const getFrequencyMeta = (routine) => {
    // 古いデータなどで frequency_type が空欄（null）の場合は interval として扱う
    const type = routine.frequency_type || 'interval';

    if (type === 'day_of_week') {
        const days = ['日', '月', '火', '水', '木', '金', '土'];
        const dayName = days[routine.day_of_week] ?? '-';
        return { label: `毎週${dayName}曜`, icon: '📅' };
    } else {
        const daysCount = routine.interval_days ?? 1;
        return { label: `${daysCount}日ごと`, icon: '🔄' };
    }
};
</script>

<template>
    <div class="bg-white rounded-3xl border border-slate-200/80 p-6 space-y-6 shadow-xs">
        <!-- ヘッダー領域（タイトルと全件数バッジ） -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="p-1 rounded-lg bg-indigo-50 text-indigo-600">🔄</span> 
                    <span>ルーティンテンプレート管理</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">定期的に自動生成されるタスクのテンプレートを管理します。</p>
            </div>
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">
                全 {{ totalCount }} 件
            </span>
        </div>

        <!-- 該当データが空の場合の表示 -->
        <div v-if="routineTemplates.length === 0" class="text-center py-12 text-slate-400 text-xs">
            条件に一致するルーティンテンプレートはありません。
        </div>

        <!-- ルーティンテンプレート一覧 -->
        <div v-else class="space-y-3">
            <div 
                v-for="routine in routineTemplates" 
                :key="routine.id" 
                class="group relative rounded-2xl p-4 sm:p-4.5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] border flex flex-col gap-3 sm:gap-0 sm:flex-row sm:items-center sm:justify-between overflow-hidden transform-gpu bg-white border-slate-200/80 hover:shadow-md"
            >
                <!-- ─── メインコンテンツ領域（左側：タイトル・ステータス・カテゴリ） ─── -->
                <div class="flex items-center gap-3 min-w-0 flex-1 w-full">
                    <div class="min-w-0 flex-1 space-y-1">
                        <!-- タイトル ＆ ルーティン有効/停止ステータスバッジ -->
                        <div class="flex items-center gap-2 flex-wrap min-w-0">
                            <span class="text-xs sm:text-sm font-bold leading-normal tracking-tight text-slate-900 py-0.5 break-words min-w-0">
                                {{ routine.title }}
                            </span>

                            <!-- 有効 / 停止中バッジ -->
                            <span 
                                :class="[
                                    'flex-shrink-0 inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border',
                                    routine.is_active 
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
                                        : 'bg-slate-100 text-slate-500 border-slate-200'
                                ]"
                            >
                                {{ routine.is_active ? '✨ 有効' : '⏸ 停止中' }}
                            </span>
                        </div>

                        <!-- カテゴリ & サブカテゴリバッジ -->
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <div 
                                :class="[
                                    'inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-xl border shadow-2xs whitespace-nowrap shrink-0',
                                    getCategoryMeta(routine.category).badgeClass || categoryTree.inbox.badgeClass
                                ]"
                            >
                                <span>{{ getCategoryMeta(routine.category).icon || '📥' }}</span>
                                <span class="text-slate-700">{{ getCategoryMeta(routine.category).label || '未分類' }}</span>
                                <span class="text-slate-300 font-light">/</span>
                                <span>{{ getSubCategoryMeta(routine.category, routine.sub_category).icon }}</span>
                                <span class="text-slate-600">{{ getSubCategoryMeta(routine.category, routine.sub_category).label }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ─── メタデータ領域（右側：優先度・頻度・各種アクションボタン） ─── -->
                <div class="flex items-center justify-between sm:justify-end gap-1.5 pt-2 sm:pt-0 border-t border-slate-100 sm:border-t-0 shrink-0 w-full sm:w-auto">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <!-- 優先度表示バッジ -->
                        <div 
                            :class="[
                                'px-2.5 py-1 rounded-xl flex items-center gap-1 shadow-2xs border text-[11px] font-bold whitespace-nowrap shrink-0',
                                getPriorityMeta(routine.priority)?.badgeClass || priorityConfig.medium.badgeClass
                            ]"
                        >
                            <span>⚡</span>
                            <span>{{ getPriorityMeta(routine.priority)?.label }}</span>
                        </div>

                        <!-- 頻度表示バッジ（何日毎 / 曜日毎） -->
                        <div class="px-2.5 py-1 rounded-xl bg-indigo-50/80 text-indigo-700 border border-indigo-200/80 flex items-center gap-1 shadow-2xs text-[11px] font-bold whitespace-nowrap shrink-0">
                            <span>{{ getFrequencyMeta(routine).icon }}</span>
                            <span>{{ getFrequencyMeta(routine).label }}</span>
                        </div>

                        <!-- 停止 / 有効化切り替えボタン -->
                        <button 
                            @click.stop="$emit('toggle', routine)"
                            :class="[
                                'px-2.5 py-1 rounded-xl transition cursor-pointer flex items-center gap-1 active:scale-95 shadow-2xs border text-[11px] font-bold whitespace-nowrap shrink-0',
                                routine.is_active 
                                    ? 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' 
                                    : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                            ]"
                        >
                            <span>{{ routine.is_active ? '⏸' : '▶' }}</span>
                            <span>{{ routine.is_active ? '停止' : '有効化' }}</span>
                        </button>

                        <!-- 編集ボタン -->
                        <button 
                            @click.stop="$emit('edit', routine)"
                            class="px-2.5 py-1 rounded-xl bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100 transition cursor-pointer flex items-center gap-1 active:scale-95 shadow-2xs text-[11px] font-bold whitespace-nowrap shrink-0"
                        >
                            <span>✏️</span>
                            <span>編集</span>
                        </button>
                    </div>

                    <!-- 削除ボタン（ホバー時にのみ表示） -->
                    <button 
                        @click.stop="$emit('delete', routine.id)"
                        class="text-slate-300 hover:text-rose-600 sm:opacity-0 sm:group-hover:opacity-100 transition p-1.5 cursor-pointer rounded-xl hover:bg-rose-50 shrink-0 ml-auto sm:ml-0"
                        title="ルーティンを削除"
                    >
                        ✕
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>