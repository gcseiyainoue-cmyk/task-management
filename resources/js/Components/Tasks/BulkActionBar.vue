<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 BulkActionBar.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / 一括選択アクションバー）
 * =====================================================================================
 * 【実務における設計思想】
 * 複数タスクが選択された際に画面下部にフロート表示される一括操作用アクションバーです。
 * 選択件数の表示と、一括完了・未完了・期限/カテゴリ/優先度の変更、一括削除、選択解除の
 * 各種イベントを親コンポーネントへ通知（Data Down, Actions Up）する役割を担います。
 * ロジックを持たない純粋なUIパーツとして設計されています。
 */

// --- プロパティの定義（現在選択されているタスクの件数） ---
defineProps({
    selectedCount: Number,
});

// --- イベント定義（親コンポーネントへ一括操作のアクションを通知） ---
defineEmits([
    'complete', 'uncomplete', 'open-due-modal', 
    'open-category-modal', 'open-priority-modal', 'delete', 'clear'
]);
</script>

<template>
    <!-- 【フロート固定レイアウト】画面下部に固定配置され、レスポンシブに対応した一括操作ツールバー -->
    <div class="fixed bottom-20 left-4 right-4 sm:left-1/2 sm:-translate-x-1/2 sm:w-auto z-50 bg-slate-900/95 backdrop-blur-md text-white text-xs p-3.5 rounded-2xl shadow-2xl flex items-center gap-2 sm:gap-3 border border-slate-700 flex-wrap justify-center">
        <!-- 選択件数表示 -->
        <span class="font-bold text-amber-400 px-1">{{ selectedCount }}件選択中</span>
        <div class="h-4 w-px bg-slate-700 hidden sm:block"></div>
        
        <!-- 一括完了アクション -->
        <button @click="$emit('complete')" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            ✅ 完了
        </button>
        <!-- 一括未完了アクション -->
        <button @click="$emit('uncomplete')" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            ⏳ 未完了
        </button>

        <!-- 一括期限変更モーダル起動 -->
        <button @click="$emit('open-due-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>📅</span> 期限
        </button>

        <!-- 一括カテゴリ変更モーダル起動 -->
        <button @click="$emit('open-category-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>🏷️</span> カテゴリ
        </button>

        <!-- 一括優先度変更モーダル起動 -->
        <button @click="$emit('open-priority-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>⚡</span> 優先度
        </button>

        <!-- 一括削除アクション -->
        <button @click="$emit('delete')" class="bg-rose-950/80 hover:bg-rose-900 active:scale-95 text-rose-200 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            🗑️ 削除
        </button>
        <!-- 選択解除（閉じる）ボタン -->
        <button @click="$emit('clear')" class="text-slate-400 hover:text-white px-2 py-2 ml-1" title="閉じる">
            ✕
        </button>
    </div>
</template>