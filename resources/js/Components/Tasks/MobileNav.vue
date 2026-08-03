<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 MobileNav.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / モバイル用ボトムナビゲーション）
 * =====================================================================================
 * 【実務における設計思想】
 * スマートフォンなどの狭小画面デバイスにおいて、親指が届きやすい画面下部に常時固定（fixed）される
 * ボトムナビゲーションバーです。
 * よく利用される主要なビュー（すべて、今日、未分類）へのダイレクトな遷移リンクに加え、
 * 中央には新規作成用のアクセントボタン（FAB風 ※タブに応じてタスクまたはルーティン作成を動的起動）、
 * さらにその他のカテゴリや詳細メニューを開くためのドロワー起動ボタンを配置しています。
 */

import { Link } from '@inertiajs/vue3';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
defineProps({
    // 現在選択されているカテゴリやビューの識別子（タブのアクティブ状態のスタイリング判定に使用）
    currentCategory: String,
    // 本日のタスク件数（今日タブのバッジカウンター表示に使用）
    todayCount: Number,
    // 未分類タスクの件数（未分類タブのバッジカウンター表示に使用）
    inboxCount: Number,
});

// --- イベント定義（メニューおよび作成モーダルオープン用の親イベント通知） ---
const emit = defineEmits([
    'open-menu',       // モバイル用メニュー（サイドバー/ドロワー）の展開を親へ通知
    'open-task-modal'  // タスクまたはルーティン作成モーダルのオープンを親へ通知
]);
</script>

<template>
    <!-- 【ボトムナビゲーションレイアウト】画面最下部に固定配置され、モバイルの親指操作に最適化されたタブバー -->
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/90 backdrop-blur-md border-t border-slate-200/80 px-4 py-2 lg:hidden flex items-center justify-around shadow-lg">
        <!-- 「すべてのタスク」への遷移リンク -->
        <Link 
            :href="route('dashboard', { view: 'all' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl', currentCategory === 'all' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📂</span>
            <span>すべて</span>
        </Link>

        <!-- 「今日」のタスクへの遷移リンク -->
        <Link 
            :href="route('dashboard', { view: 'today' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl relative', currentCategory === 'today' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📅</span>
            <span>今日</span>
            <span v-if="todayCount > 0" class="absolute -top-1 -right-1 bg-slate-900 text-white text-[9px] font-mono px-1.5 py-0.2 rounded-full">
                {{ todayCount }}
            </span>
        </Link>

        <!-- 新規作成ボタン（中央配置のアクセントボタン ※タブの状態に応じてタスク/ルーティン作成を起動） -->
        <button 
            @click="$emit('open-task-modal')"
            class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-900 text-white shadow-md active:scale-95 transition cursor-pointer hover:bg-slate-800"
        >
            <span class="text-xl font-normal leading-none">+</span>
        </button>

        <!-- 「未分類」への遷移リンク -->
        <Link 
            :href="route('dashboard', { category: 'inbox' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl relative', currentCategory === 'inbox' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📥</span>
            <span>未分類</span>
            <span v-if="inboxCount > 0" class="absolute -top-1 -right-1 bg-slate-900 text-white text-[9px] font-mono px-1.5 py-0.2 rounded-full">
                {{ inboxCount }}
            </span>
        </Link>

        <!-- メニューボタン（サイドバー/ドロワー展開用） -->
        <button 
            @click="$emit('open-menu')"
            class="flex flex-col items-center gap-0.5 text-[10px] font-bold text-slate-400 hover:text-slate-600 transition px-2 py-1 rounded-xl cursor-pointer"
        >
            <span class="text-base">🍔</span>
            <span>メニュー</span>
        </button>
    </div>
</template>