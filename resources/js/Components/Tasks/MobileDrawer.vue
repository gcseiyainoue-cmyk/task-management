<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 MobileDrawer.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / モバイル用サイドバー・ドロワー）
 * =====================================================================================
 * 【実務における設計思想】
 * 先述のデスクトップ用サイドバー（DesktopSidebar.vue）とほぼ同等のビュー絞り込みおよびカテゴリ別フィルタリング機能に加え、
 * ルートや管理機能への導線（ルーティン管理など）を整理して配置しています。
 */

import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
defineProps({
    // 全タスクデータの配列（各カテゴリやビューごとの件数バッジを動的に算出するために使用）
    tasks: Array,
    // 登録されているルーティンテンプレートの配列（ルーティン管理タブの件数表示に使用）
    routineTemplates: Array,
    // 現在選択されているビューまたはカテゴリの識別子（アクティブ状態のスタイリング判定に使用）
    currentCategory: String,
    // 本日の日付を表す文字列（'YYYY-MM-DD'形式。今日のタスク件数をフィルタリングするために使用）
    todayStr: String,
});

// --- イベント定義（ナビゲーション選択時に親コンポーネントへドロワー閉鎖を通知） ---
const emit = defineEmits([
    'close' // ナビゲーションリンク選択時にドロワーを閉じるための通知
]);
</script>

<template>
    <!-- 【モバイルドロワーレイアウト】縦方向のスペース効率を高め、タッチ操作しやすいパディングやグリッドを適用 -->
    <div class="space-y-4 pb-4">
        
        <!-- ─── クイックビュー（時間・フィルターによる絞り込み / グリッド形式） ─── -->
        <div class="space-y-2">
            <div class="text-[10px] font-bold text-slate-400 px-1.5 uppercase tracking-wider">ビュー</div>
            <div class="grid grid-cols-2 gap-2">
                <!-- 「今日」のタスクに絞り込むリンク -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { view: 'today' })" 
                    :class="['p-3.5 rounded-2xl text-xs font-semibold transition-all flex flex-col items-center justify-center gap-1.5 text-center active:scale-95 border cursor-pointer shadow-2xs', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-700 border-slate-200/85']"
                >
                    <span class="text-xl">📅</span>
                    <span class="text-[11px] font-bold">今日</span>
                    <span class="font-mono text-[9px] px-2 py-0.5 rounded-full font-medium" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <!-- 「すべてのタスク」を表示するリンク -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { view: 'all' })" 
                    :class="['p-3.5 rounded-2xl text-xs font-semibold transition-all flex flex-col items-center justify-center gap-1.5 text-center active:scale-95 border cursor-pointer shadow-2xs', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-700 border-slate-200/85']"
                >
                    <span class="text-xl">📂</span>
                    <span class="text-[11px] font-bold">すべてのタスク</span>
                    <span class="font-mono text-[9px] px-2 py-0.5 rounded-full font-medium" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- ─── 管理・設定セクション（ルーティンリンク） ─── -->
        <div class="space-y-2">
            <div class="text-[10px] font-bold text-slate-400 px-1.5 uppercase tracking-wider">管理</div>
            <Link 
                @click="$emit('close')"
                :href="route('dashboard', { view: 'routines' })" 
                :class="['p-3.5 rounded-2xl text-xs font-semibold transition-all flex items-center justify-between px-4 active:scale-95 border cursor-pointer shadow-2xs', currentCategory === 'routines' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-700 border-slate-200/85']"
            >
                <span class="flex items-center gap-2.5 text-xs font-bold">
                    <span class="text-base">🔄</span>
                    <span>ルーティン管理</span>
                </span>
                <span class="font-mono text-[9px] px-2 py-0.5 rounded-full font-medium" :class="currentCategory === 'routines' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                    {{ routineTemplates ? routineTemplates.length : 0 }}
                </span>
            </Link>
        </div>

        <!-- ─── カテゴリリストセクション ─── -->
        <div class="space-y-2">
            <div class="text-[10px] font-bold text-slate-400 px-1.5 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-2">
                <!-- 定数（categoryTree）をループし、インボックス（未分類）以外の各カテゴリリンクを動的生成 -->
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    @click="$emit('close')"
                    :href="route('dashboard', { category: key })"
                    :class="['p-3.5 rounded-2xl text-xs font-semibold transition-all flex items-center justify-between px-4 active:scale-95 border cursor-pointer shadow-2xs', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-700 border-slate-200/85']"
                >
                    <span class="flex items-center gap-2.5 text-xs font-bold">
                        <span class="text-base">{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[9px] px-2 py-0.5 rounded-full font-medium" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>

                <!-- 「未分類（inbox）」をカテゴリリスト最下部に区切り線付きで配置 -->
                <div class="pt-2 mt-2 border-t border-slate-100">
                    <Link 
                        @click="$emit('close')"
                        :href="route('dashboard', { category: 'inbox' })" 
                        :class="['p-3.5 rounded-2xl text-xs font-semibold transition-all flex items-center justify-between px-4 active:scale-95 border cursor-pointer shadow-2xs', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50/80 hover:bg-slate-100 text-slate-700 border-slate-200/85']"
                    >
                        <span class="flex items-center gap-2.5 text-xs font-bold">
                            <span class="text-base">📥</span>
                            <span>未分類</span>
                        </span>
                        <span class="font-mono text-[9px] px-2 py-0.5 rounded-full font-medium" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                            {{ tasks.filter(t => t.category === 'inbox').length }}
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>