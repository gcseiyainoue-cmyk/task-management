<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 RoutineControlBar.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / ルーティン管理用コントロールバー）
 * =====================================================================================
 * 【実務における設計思想】
 * ルーティンテンプレート一覧の検索やカテゴリ絞り込みを行うためのコントロールパネルです[cite: 5]。
 * Vue 3の `v-model` 双方向バインディング（`update:searchQuery` や `update:selectedCategoryFilter`）
 * を活用し、親コンポーネント側の状態を子コンポーネントから安全に更新できる設計にしています[cite: 5]。
 * また、`sticky` 配置によりスクロール時も画面上部に追従し、いつでも絞り込みを行える優れたUXを実現しています[cite: 5]。
 */

import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
defineProps({
    // 現在入力されている検索クエリ文字列（タスクのリアルタイム絞り込み検索に使用）
    searchQuery: String,
    // 現在選択されているカテゴリのフィルター条件（タスク一覧の絞り込み表示に使用）
    selectedCategoryFilter: String,
});

// 親コンポーネントへ双方向バインディング用のイベントを発火
defineEmits([
    'update:searchQuery',          // 検索キーワードの双方向バインディング更新通知[cite: 5]
    'update:selectedCategoryFilter', // カテゴリフィルターの双方向バインディング更新通知[cite: 5]
]);
</script>

<template>
    <!-- 【UX設計】sticky追従のコントロールパネル（スクロールしても常に上部に固定） -->
    <div class="sticky top-[144px] z-30 bg-white/95 backdrop-blur-md border border-slate-200/85 rounded-3xl p-4 my-2 shadow-md flex flex-col sm:flex-row items-center justify-between gap-3 transition-all">
        
        <!-- ─── 検索バー（リアルタイムキーワード絞り込み） ─── -->
        <div class="w-full sm:w-64 relative">
            <input 
                type="text" 
                :value="searchQuery" 
                @input="$emit('update:searchQuery', $event.target.value)"
                placeholder="ルーティン名で検索..."[cite: 5]
                class="w-full bg-slate-50 border border-slate-200 text-xs rounded-2xl pl-9 pr-3 py-2.5 text-slate-900 focus:bg-white transition shadow-inner"
            />
            <span class="absolute left-3 top-3 text-slate-400 text-xs">🔍</span>
        </div>

        <!-- ─── フィルター群 ─── -->
        <div class="flex items-center gap-2 w-full sm:w-auto justify-end flex-wrap overflow-x-auto pb-1 sm:pb-0">
            
            <!-- カテゴリフィルターセレクトボックス -->
            <select 
                :value="selectedCategoryFilter"
                @change="$emit('update:selectedCategoryFilter', $event.target.value)"
                class="text-xs bg-slate-50 border border-slate-200 rounded-2xl pl-3 pr-8 py-2.5 text-slate-700 cursor-pointer shadow-inner whitespace-nowrap"
            >
                <option value="all">📁 すべてのカテゴリ</option>
                <option v-for="(cat, key) in categoryTree" :key="key" :value="key">
                    {{ cat.icon }} {{ cat.label }}
                </option>
            </select>
        </div>
    </div>
</template>