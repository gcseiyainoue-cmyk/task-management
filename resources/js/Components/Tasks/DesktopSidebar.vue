<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 DesktopSidebar.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / サイドバーナビゲーション）
 * =====================================================================================
 * 【実務における設計思想】
 * Inertia.jsのLinkコンポーネントを用いたSPAルーティングによって、
 * 「今日」「すべてのタスク」といった時間・ステータス別のビュー絞り込みや、
 * 定義済みのカテゴリツリー（@/Constants/task）に基づくカテゴリ別フィルタリング機能を提供します。
 * 各ビューやカテゴリに属するタスクの件数をリアルタイムに動的集計してバッジ表示することで、
 * ユーザーが俯瞰しやすく操作性の高いダッシュボードサイドバーを実現しています。
 */

import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（全タスクデータ、現在選択中のカテゴリ、本日の日付文字列） ---
defineProps({
    tasks: Array,
    currentCategory: String,
    todayStr: String,
});
</script>

<template>
    <!-- 【サイドバーレイアウト】デスクトップ表示時に固定（sticky）配置され、ビューとカテゴリの切り替えリンク群を提供 -->
    <aside class="w-64 shrink-0 hidden lg:block space-y-6 bg-white border border-slate-200/80 rounded-3xl p-5 my-2 shadow-sm sticky top-[144px]">
        <!-- ─── ビュー（時間・フィルターによる絞り込み）セクション ─── -->
        <div class="space-y-2">
            <div class="text-[11px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="space-y-1">
                <!-- 「今日」のタスクに絞り込むリンク -->
                <Link 
                    :href="route('dashboard', { view: 'today' })" 
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📅</span> 今日</span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <!-- 「すべてのタスク」を表示するリンク -->
                <Link 
                    :href="route('dashboard', { view: 'all' })" 
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📂</span> すべてのタスク</span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- ─── カテゴリ（分類）セクション ─── -->
        <div class="space-y-2">
            <div class="text-[11px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1">
                <!-- 定数（categoryTree）をループし、インボックス（未分類）以外の各カテゴリリンクを動的生成 -->
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    :href="route('dashboard', { category: key })"
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>

                <!-- 「未分類（inbox）」をカテゴリリストの一番下に区切り線付きで独立配置 -->
                <div class="pt-2 mt-2 border-t border-slate-100">
                    <Link 
                        :href="route('dashboard', { category: 'inbox' })" 
                        :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                    >
                        <span class="flex items-center gap-2 text-xs font-bold"><span>📥</span> 未分類</span>
                        <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                            {{ tasks.filter(t => t.category === 'inbox').length }}
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </aside>
</template>