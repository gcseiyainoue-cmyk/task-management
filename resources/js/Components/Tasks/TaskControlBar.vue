<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 TaskControlBar.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / 検索・フィルタ・操作コントロールバー）
 * =====================================================================================
 * 【実務における設計思想】
 * タスクの絞り込み（検索、カテゴリ）、並び替え（ソート基準・昇降順）、および一括選択モードの切り替え
 * という「ユーザーのビュー操作を集約したコントロールパネル」です。
 * 状態を直接保持せず、すべての変更を `defineEmits` を通じて親コンポーネント（Index.vue）または
 * ロジック層へ伝達する「単一方向データフロー（Data Down, Actions Up）」を厳格に守っています。
 */

import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
defineProps({
    // 現在入力されている検索クエリ文字列（タスクのリアルタイム絞り込み検索に使用）
    searchQuery: String,
    // 現在選択されているカテゴリのフィルター条件（タスク一覧の絞り込み表示に使用）
    selectedCategoryFilter: String,
    // 現在適用されているソート基準（期限や作成日、優先度などの並び替え軸を指定するために使用）
    sortBy: String,
    // 現在適用されているソート順序（昇順 'asc' または降順 'desc' を指定するために使用）
    sortOrder: String,
    // 一括選択モードが有効かどうかを示すフラグ（複数選択用のチェックボックスや一括操作バーの表示制御に使用）
    isSelectionMode: Boolean,
});

// --- イベント定義（親へ通知するアクション群） ---
// Vue 3の v-model パターン（update:xxx）とカスタムアクションを明確に分離しています
defineEmits([
    'update:searchQuery',            // 検索キーワードの更新通知（v-model用）
    'update:selectedCategoryFilter', // 選択中カテゴリフィルターの更新通知（v-model用）
    'update:sortBy',                 // ソート基準の更新通知（v-model用）
    'toggleSortOrder',               // 昇順/降順の切り替え通知
    'toggleSelectionMode'            // 一括選択モードの有効/無効切り替え通知
]);
</script>

<template>
    <!-- 【UX設計】スクロール追従（sticky）を採用し、リストが長くなってもいつでも検索・絞り込みができるように配置 -->
    <div class="sticky top-[144px] z-30 bg-white/95 backdrop-blur-md border border-slate-200/85 rounded-3xl p-4 my-2 shadow-md flex flex-col sm:flex-row items-center justify-between gap-3 transition-all">
        
        <!-- ─── 検索バー ─── -->
        <div class="w-full sm:w-64 relative">
            <input 
                type="text" 
                :value="searchQuery" 
                @input="$emit('update:searchQuery', $event.target.value)"
                placeholder="タスク・期日・カテゴリで検索..." 
                class="w-full bg-slate-50 border border-slate-200 text-xs rounded-2xl pl-9 pr-3 py-2.5 text-slate-900 focus:bg-white transition shadow-inner"
            />
            <span class="absolute left-3 top-3 text-slate-400 text-xs">🔍</span>
        </div>

        <!-- ─── フィルター・操作ボタン群 ─── -->
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

            <!-- ソート基準セレクトボックス -->
            <select 
                :value="sortBy"
                @change="$emit('update:sortBy', $event.target.value)"
                class="text-xs bg-slate-50 border border-slate-200 rounded-2xl pl-3 pr-8 py-2.5 text-slate-700 cursor-pointer shadow-inner whitespace-nowrap"
            >
                <option value="due_date">期日順</option>
                <option value="created_at">作成日順</option>
                <option value="priority">優先度順</option>
            </select>

            <!-- 昇順・降順切り替えボタン -->
            <button 
                @click="$emit('toggleSortOrder')"
                title="昇順/降順を切り替え"
                class="text-xs px-3.5 py-2.5 font-bold rounded-2xl border bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-700 transition active:scale-95 cursor-pointer flex items-center gap-1 shadow-2xs whitespace-nowrap"
            >
                <span>{{ sortOrder === 'asc' ? '昇順 ⬆️' : '降順 ⬇️' }}</span>
            </button>

            <!-- 一括選択モード切替ボタン（アクティブ時はダーク色で視覚的に強調） -->
            <button 
                @click="$emit('toggleSelectionMode')" 
                :class="['text-xs px-3.5 py-2.5 font-bold rounded-2xl border transition active:scale-95 shadow-2xs cursor-pointer whitespace-nowrap', isSelectionMode ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100']"
            >
                {{ isSelectionMode ? '選択終了' : '一括選択' }}
            </button>
        </div>
    </div>
</template>