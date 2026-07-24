<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// 各セクションの開閉状態（検索・フィルターを開いた状態に設定）
const openSections = ref({
    filter: true,
    create: false,
    batch: false,
    menu: false,
});

const toggleSection = (key) => {
    openSections.value[key] = !openSections.value[key];
};
</script>

<template>
    <Head title="コード・UI対応ガイド" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span>🗺️</span> Index.vue コード・UI対応ガイド
                </h2>
                <Link 
                    :href="route('dashboard')" 
                    class="text-xs bg-slate-900 text-white px-3.5 py-2.5 rounded-xl font-bold hover:bg-slate-800 transition shadow-md active:scale-95"
                >
                    ダッシュボードへ戻る
                </Link>
            </div>
        </template>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20 space-y-6">
            
            <!-- イントロカード -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
                <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-white/10 px-3 py-1 rounded-full text-amber-300">
                    <span>💡</span> 実務アーキテクチャ設計リファレンス
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                    ダッシュボードの主要機能・UI対応リファレンス
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    実務の現場で重視される「関心の分離（単一責任の原則）」に基づいた、UI・ロジック・統括の3層構造を分かりやすく解説しています。
                </p>
            </div>

            <!-- アコーディオンリスト -->
            <div class="space-y-4">
                
                <!-- 01. 検索・絞り込み・ソート機能（実務3層構造解説） -->
                <div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden transition-all">
                    <button 
                        @click="toggleSection('filter')" 
                        class="w-full flex items-center justify-between p-6 sm:p-7 text-left hover:bg-slate-50/50 transition cursor-pointer"
                    >
                        <div class="flex items-center gap-3">
                            <span class="text-lg">🔍</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base">1. 一覧の検索・カテゴリ絞り込み・ソート機能</h3>
                                <p class="text-xs text-slate-500 mt-0.5">実務の定番「3層構造（UI・ロジック・統括）」による設計</p>
                            </div>
                        </div>
                        <span class="text-slate-400 font-bold text-sm transform transition-transform" :class="{ 'rotate-180': openSections.filter }">
                            ▼
                        </span>
                    </button>

                    <div v-show="openSections.filter" class="px-6 pb-7 pt-2 border-t border-slate-100 space-y-6">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            実務開発では、巨大なコード（スパゲッティコード）を防ぐため、役割を明確に分ける<strong>「関心の分離」</strong>が徹底されています。この検索・絞り込み機能は、以下の3つのファイルが連携して美しく動作しています。
                        </p>

                        <!-- 3層構造の役割カード -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                            <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-1">
                                    <span>🧑‍🍳</span> 1. TaskControlBar.vue
                                </div>
                                <div class="text-[11px] text-amber-700 font-bold">【ホール担当（UI）】</div>
                                <p class="text-slate-600 leading-relaxed">
                                    ユーザーの入力や選択を受け付け、<code>defineEmits</code> で親へ通知することに専念。
                                </p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-1">
                                    <span>⚙️</span> 2. useTaskFilterAndSort.js
                                </div>
                                <div class="text-[11px] text-blue-700 font-bold">【調理場担当（ロジック）】</div>
                                <p class="text-slate-600 leading-relaxed">
                                    画面を持たず、検索・絞り込み・ソートの「計算」だけに集中する純粋なJavaScript。
                                </p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl space-y-1.5">
                                <div class="font-bold text-slate-900 flex items-center gap-1">
                                    <span>👔</span> 3. Index.vue
                                </div>
                                <div class="text-[11px] text-emerald-700 font-bold">【店長（統括・司令塔）】</div>
                                <p class="text-slate-600 leading-relaxed">
                                    UIとロジックをインポートし、<code>v-model</code> 等でデータをシームレスに結合する。
                                </p>
                            </div>
                        </div>

                        <!-- UIイメージ -->
                        <div class="bg-slate-100/80 border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-2">
                            <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                <span class="text-xs">🖥️</span> 画面上の対応箇所イメージ (TaskControlBar.vue)
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs">
                                <div class="bg-slate-50/80 border border-slate-200 px-3.5 py-2 rounded-xl text-slate-400 flex items-center gap-2 flex-1">
                                    <span>🔍</span>
                                    <span>タスク・期日・カテゴリで検索...</span>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                                    <div class="bg-slate-50/80 border border-slate-200 px-3 py-2 rounded-xl text-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                                        <span>📁 すべてのカテゴリ</span>
                                        <span class="text-slate-400 text-[10px]">▼</span>
                                    </div>
                                    <div class="bg-slate-50/80 border border-slate-200 px-3 py-2 rounded-xl text-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                                        <span>期日順</span>
                                        <span class="text-slate-400 text-[10px]">▼</span>
                                    </div>
                                    <div class="bg-slate-50/80 border border-slate-200 px-3 py-2 rounded-xl text-slate-700 flex items-center gap-1.5 whitespace-nowrap">
                                        <span>昇順 ⬆️</span>
                                    </div>
                                    <div class="bg-white border border-slate-200 text-slate-800 px-3.5 py-2 rounded-xl font-bold shadow-xs whitespace-nowrap">
                                        一括選択
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 該当コード：TaskControlBar.vue -->
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">該当コード 1：TaskControlBar.vue (UI層)</span>
                            <div class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner leading-relaxed">
<pre><code>&lt;script setup&gt;
import { categoryTree } from '@/Constants/task';

defineProps({
    searchQuery: String,
    selectedCategoryFilter: String,
    sortBy: String,
    sortOrder: String,
    isSelectionMode: Boolean,
});

defineEmits([
    'update:searchQuery',
    'update:selectedCategoryFilter',
    'update:sortBy',
    'toggleSortOrder',
    'toggleSelectionMode'
]);
&lt;/script&gt;</code></pre>
                            </div>
                        </div>

                        <!-- 該当コード：Index.vue 連携部分 -->
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">該当コード 2：Index.vue ＆ Composable 連携 (統括・ロジック層)</span>
                            <div class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner leading-relaxed">
<pre><code>// --- 1. ロジックの呼び出し (useTaskFilterAndSort.js) ---
const { 
    searchQuery, 
    selectedCategoryFilter, 
    sortBy, 
    sortOrder, 
    toggleSortOrder, 
    activeTasks, 
    completedTasksList 
} = useTaskFilterAndSort(props);

// --- 2. UIコンポーネントへのバインド (Index.vue) ---
&lt;TaskControlBar 
    v-model:search-query="searchQuery"
    v-model:selected-category-filter="selectedCategoryFilter"
    v-model:sort-by="sortBy"
    :sort-order="sortOrder"
    :is-selection-mode="isSelectionMode"
    @toggle-sort-order="toggleSortOrder"
    @toggle-selection-mode="toggleSelectionMode"
/&gt;</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 02. 新規タスク追加モーダル -->
                <div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden transition-all">
                    <button 
                        @click="toggleSection('create')" 
                        class="w-full flex items-center justify-between p-6 sm:p-7 text-left hover:bg-slate-50/50 transition cursor-pointer"
                    >
                        <div class="flex items-center gap-3">
                            <span class="text-lg">✨</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base">2. 新規タスク追加モーダル ＆ フォーム制御</h3>
                                <p class="text-xs text-slate-500 mt-0.5">useFormを用いた非同期作成とトースト通知の連携</p>
                            </div>
                        </div>
                        <span class="text-slate-400 font-bold text-sm transform transition-transform" :class="{ 'rotate-180': openSections.create }">
                            ▼
                        </span>
                    </button>

                    <div v-show="openSections.create" class="px-6 pb-7 pt-2 border-t border-slate-100 space-y-6">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            <code>isTaskModalOpen</code> の切り替えによりモーダルを開閉します。<code>useForm</code> で初期値を設定し、<code>submitTask</code> でサーバーへ非同期送信します。
                        </p>

                        <!-- UIイメージ -->
                        <div class="bg-slate-100/80 border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-3">
                            <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                <span>🖥️</span> 画面上の対応箇所イメージ（新規タスク追加モーダル）
                            </div>
                            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-md space-y-4 max-w-md mx-auto text-xs">
                                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                    <span class="font-bold text-slate-900">✨ タスクを追加</span>
                                    <span class="text-slate-400 font-bold">✕</span>
                                </div>
                                <div class="space-y-1">
                                    <div class="text-[10px] font-bold text-slate-500">タスク名</div>
                                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-200 text-slate-400">例：週報を作成する</div>
                                </div>
                                <div class="bg-slate-900 text-white text-center py-2.5 rounded-xl font-bold">タスクを追加する</div>
                            </div>
                        </div>

                        <!-- 該当コード -->
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">該当コード (Index.vue 抜粋)</span>
                            <div class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner leading-relaxed">
<pre><code>const isTaskModalOpen = ref(false);
const todayStr = new Date().toISOString().split('T')[0];

const form = useForm({
    title: '',
    due_date: todayStr,
    category: activeCategoryKey,
    sub_category: defaultSubKey,
    priority: 'medium',
});

const submitTask = () => {
    if (!form.title.trim()) return;
    form.post(route('tasks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast('タスクを追加しました');
            form.reset('title');
            isTaskModalOpen.value = false;
        }
    });
};</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 03. 一括選択・一括アクション機能 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden transition-all">
                    <button 
                        @click="toggleSection('batch')" 
                        class="w-full flex items-center justify-between p-6 sm:p-7 text-left hover:bg-slate-50/50 transition cursor-pointer"
                    >
                        <div class="flex items-center gap-3">
                            <span class="text-lg">☑️</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base">3. 一括選択・一括アクション機能</h3>
                                <p class="text-xs text-slate-500 mt-0.5">useTaskSelection と BulkActionBar による一括処理制御</p>
                            </div>
                        </div>
                        <span class="text-slate-400 font-bold text-sm transform transition-transform" :class="{ 'rotate-180': openSections.batch }">
                            ▼
                        </span>
                    </button>

                    <div v-show="openSections.batch" class="px-6 pb-7 pt-2 border-t border-slate-100 space-y-6">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            <code>useTaskSelection</code> コンポーザブルによって選択モードやタスクIDの配列（<code>selectedTaskIds</code>）を管理し、選択時に <code>BulkActionBar</code> を表示して一括操作を実行します。
                        </p>

                        <!-- UIイメージ -->
                        <div class="bg-slate-100/80 border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-2">
                            <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                <span>🖥️</span> 画面上の対応箇所イメージ
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between max-w-md mx-auto text-xs">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" checked class="rounded border-slate-300">
                                    <span class="font-bold text-slate-700">2件選択中</span>
                                </div>
                                <div class="flex gap-2">
                                    <span class="bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-lg font-bold">一括完了</span>
                                    <span class="bg-rose-100 text-rose-800 px-2.5 py-1 rounded-lg font-bold">一括削除</span>
                                </div>
                            </div>
                        </div>

                        <!-- 該当コード -->
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">該当コード (Index.vue 抜粋)</span>
                            <div class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner leading-relaxed">
<pre><code>import { useTaskSelection } from '@/Composables/useTaskSelection';
import { useTaskOperations } from '@/Composables/useTaskOperations';

const { 
    isSelectionMode, 
    selectedTaskIds, 
    toggleSelectionMode, 
    toggleTaskSelection, 
    toggleSelectActive, 
    toggleSelectCompleted 
} = useTaskSelection(activeTasks, completedTasksList);

const { bulkDelete } = useTaskOperations(...);</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 04. 個別タスクの操作メニュー -->
                <div class="bg-white border border-slate-200/80 rounded-3xl shadow-sm overflow-hidden transition-all">
                    <button 
                        @click="toggleSection('menu')" 
                        class="w-full flex items-center justify-between p-6 sm:p-7 text-left hover:bg-slate-50/50 transition cursor-pointer"
                    >
                        <div class="flex items-center gap-3">
                            <span class="text-lg">⚡</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base">4. 個別タスクの操作メニューとモーダル制御</h3>
                                <p class="text-xs text-slate-500 mt-0.5">activeMenuTask と TaskActionModal による柔軟なアクション</p>
                            </div>
                        </div>
                        <span class="text-slate-400 font-bold text-sm transform transition-transform" :class="{ 'rotate-180': openSections.menu }">
                            ▼
                        </span>
                    </button>

                    <div v-show="openSections.menu" class="px-6 pb-7 pt-2 border-t border-slate-100 space-y-6">
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            各タスクのメニューボタンから <code>openMenuModal</code> を呼び出し、対象タスクとメニュータイプを <code>activeMenuTask</code> および <code>activeMenuType</code> に格納して <code>TaskActionModal</code> に渡します。
                        </p>

                        <!-- UIイメージ -->
                        <div class="bg-slate-100/80 border border-slate-200 rounded-2xl p-4 sm:p-5 space-y-2">
                            <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                <span>🖥️</span> 画面上の対応箇所イメージ
                            </div>
                            <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs flex items-center justify-between max-w-sm mx-auto text-xs">
                                <span class="font-bold text-slate-800">実家の両親へ近況連絡の電話</span>
                                <div class="bg-slate-100 text-slate-600 px-2 py-1 rounded-lg font-mono font-bold">⋮</div>
                            </div>
                        </div>

                        <!-- 該当コード -->
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">該当コード (Index.vue 抜粋)</span>
                            <div class="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner leading-relaxed">
<pre><code>const activeMenuTask = ref(null);
const activeMenuType = ref(null);

const openMenuModal = (task, type, event) => {
    if (event) event.stopPropagation();
    activeMenuTask.value = task;
    activeMenuType.value = type;
};

const closeMenuModal = () => {
    activeMenuTask.value = null;
    activeMenuType.value = null;
};</code></pre>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- フッターアクション -->
            <div class="text-center pt-6">
                <Link 
                    :href="route('dashboard')" 
                    class="inline-flex items-center gap-2 bg-slate-900 text-white font-bold text-xs sm:text-sm px-8 py-4 rounded-2xl hover:bg-slate-800 transition shadow-lg active:scale-95"
                >
                    <span>🚀</span> ダッシュボードへ戻る
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>