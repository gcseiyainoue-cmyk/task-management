<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 MobileDrawer.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / モバイル用サイドバー・ドロワー）
 * =====================================================================================
 * 【実務における設計思想】
 * 先述のデスクトップ用サイドバー（DesktopSidebar.vue）とほぼ同等のビュー絞り込みおよびカテゴリ別フィルタリング機能を提供しつつ、
 * モバイル・タブレットの狭小画面（ドロワーやモーダル内）での使用に特化したレイアウト（グリッド配置やタッチ操作用の拡大サイズ・active:scale-95等）を実装しています。
 * 各リンククリック時には `@click="$emit('close')"` を経由して親のモーダル/ドロワーを即座に閉じる設計になっており、
 * スムーズな画面遷移とUXの向上を実現しています。
 */

import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（全タスクデータ、現在選択中のカテゴリ、本日の日付文字列） ---
defineProps({
    tasks: Array,
    currentCategory: String,
    todayStr: String,
});

// --- イベント定義（ナビゲーション選択時に親コンポーネントへドロワー閉鎖を通知） ---
defineEmits(['close']);
</script>

<template>
    <!-- 【モバイルドロワーレイアウト】縦方向のスペース効率を高め、タッチ操作しやすいパディングやグリッドを適用 -->
    <div class="space-y-4 pb-4">
        <!-- ─── クイックビュー（時間・フィルターによる絞り込み / グリッド形式） ─── -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="grid grid-cols-2 gap-1.5">
                <!-- 「今日」のタスクに絞り込むリンク -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { view: 'today' })" 
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-xl">📅</span>
                    <span class="text-[11px] font-bold">今日</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <!-- 「すべてのタスク」を表示するリンク -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { view: 'all' })" 
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-xl">📂</span>
                    <span class="text-[11px] font-bold">すべてのタスク</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- ─── カテゴリリストセクション ─── -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1.5">
                <!-- 定数（categoryTree）をループし、インボックス（未分類）以外の各カテゴリリンクを動的生成 -->
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    @click="$emit('close')"
                    :href="route('dashboard', { category: key })"
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-4 active:scale-95 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>

                <!-- 「未分類（inbox）」をカテゴリリスト最下部に区切り線付きで配置 -->
                <div class="pt-1.5 mt-1.5 border-t border-slate-100">
                    <Link 
                        @click="$emit('close')"
                        :href="route('dashboard', { category: 'inbox' })" 
                        :class="['p-3 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-4 active:scale-95 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                    >
                        <span class="flex items-center gap-2 text-xs font-bold">
                            <span>📥</span>
                            <span>未分類</span>
                        </span>
                        <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                            {{ tasks.filter(t => t.category === 'inbox').length }}
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>