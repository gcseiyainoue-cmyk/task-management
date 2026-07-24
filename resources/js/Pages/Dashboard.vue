<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 Index.vue
 * 【アーキテクチャ上の位置づけ】 統括レイヤー（コーディネーター / コントローラー）
 * =====================================================================================
 * 【実務における設計思想】
 * このファイル自身は「複雑な計算」や「見た目の細かいデザイン」を直接持ちません。
 * 裏側のロジック（Composables）と、表側の見た目（Components）をインポートし、
 * 「データとイベントのハブ（交差点）」としてのみ機能させることで、コード全体の見通しを爆発的に高めています。
 */

// --- 1. コアライブラリ・フレームワークのインポート ---
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { categoryTree } from '@/Constants/task';

// --- 2. ビジネスロジック層（Composables）のインポート ---
// 1ファイルに何千行も書くのを防ぎ、機能ごとに完全分離された再利用可能なJavaScriptモジュール群です。
import { useTaskHighlight } from '@/Composables/useTaskHighlight';       // タスク移動・新規追加時のハイライト・点滅アニメーション制御
import { useTaskFilterAndSort } from '@/Composables/useTaskFilterAndSort'; // 検索文字、カテゴリ絞り込み、ソート順の計算ロジック
import { useTaskSelection } from '@/Composables/useTaskSelection';       // 一括選択モード、選択中タスクID配列の状態管理
import { useTaskOperations } from '@/Composables/useTaskOperations';     // タスクのCRUD（作成・更新・削除）および一括API通信
import { useToast } from '@/Composables/useToast';                       // ユーザーへのフィードバック用トースト通知の表示・消去制御

// --- 3. UIコンポーネント層のインポート ---
// 画面をパーツ単位に細かく分割し、部品として組み立てることで保守性を最大化しています。
import DesktopSidebar from '@/Components/Tasks/DesktopSidebar.vue';     // PC用サイドバー（カテゴリツリーや各種メニューを表示）
import MobileDrawer from '@/Components/Tasks/MobileDrawer.vue';         // モバイル用ドロワーメニュー（スマホ表示時のサイドバー代替）
import TaskControlBar from '@/Components/Tasks/TaskControlBar.vue';     // 検索窓・カテゴリ絞り込み・ソート切替を行うコントロールバー
import TaskListSection from '@/Components/Tasks/TaskListSection.vue';   // タスクのリスト一覧を表示するセクションコンポーネント
import TaskFormModal from '@/Components/Tasks/TaskFormModal.vue';       // 新規タスク作成用のモーダルフォーム
import TaskActionModal from '@/Components/Tasks/TaskActionModal.vue';   // 個別タスクの期限・カテゴリ・優先度変更などの操作用モーダル
import BulkActionBar from '@/Components/Tasks/BulkActionBar.vue';       // 複数選択時のみ画面下部に浮上する一括操作バー
import MobileNav from '@/Components/Tasks/MobileNav.vue';             // モバイル専用のボトムナビゲーションバー

/**
 * -------------------------------------------------------------------------------------
 * 【Props定義】 バックエンド（Laravel）からのデータ受け取り
 * -------------------------------------------------------------------------------------
 * Inertia.jsの仕組みにより、Laravelのコントローラーから渡されたデータが自動的にこのpropsに格納されます。
 * 型（Array, String）を厳密に定義することで、予期せぬデータ混入によるバグを早期に検知します。
 */
const props = defineProps({
    tasks: Array,            // データベースから取得したすべてのタスク一覧
    filteredTasks: Array,    // （予備・互換用）フィルター済みタスク配列
    currentCategory: String, // 現在選択されているサイドバーのカテゴリキー（'inbox', 'today' など）
});

// --- 4. 各種Composableの実行とリアクティブ変数の分割代入 ---
// 各Composableが持つ独自のリアクティブな状態と操作関数を、このコンポーネントで利用できるように取得します。
const { toastMessage, showToast } = useToast();
const { newIds, blinkingMap, recentlyMovedMap, triggerMovedHighlight } = useTaskHighlight();

// 検索・絞り込み・ソートの計算結果および条件変更用関数を取得
const { 
    searchQuery,            // 検索窓に入力された文字列
    selectedCategoryFilter, // セレクトボックス等で選ばれた詳細カテゴリ
    sortBy,                 // ソート基準（due_date, priority, title等）
    sortOrder,              // 昇順（asc）か降順（desc）か
    toggleSortOrder,        // ソート順を反転させる関数
    activeTasks,            // 絞り込み・ソート済みの「未完了タスク」配列
    completedTasksList      // 絞り込み・ソート済みの「完了済みタスク」配列
} = useTaskFilterAndSort(props);

// 一括選択（チェックボックス複数選択）の状態とロジックを取得
const { 
    isSelectionMode,        // 一括選択モードが有効かどうか（Boolean）
    selectedTaskIds,        // 選択されているタスクのIDが格納される配列（例: [12, 15, 20]）
    toggleSelectionMode,    // 選択モードのON/OFFを切り替える関数
    toggleTaskSelection,    // 個別タスクのチェック状態を反転させる関数
    toggleSelectActive,     // 未完了タスクを一括全選択/全解除する関数
    toggleSelectCompleted   // 完了済みタスクを一括全選択/全解除する関数
} = useTaskSelection(activeTasks, completedTasksList);

// --- 5. 画面ローカルのUI状態管理（モーダルやドロワーの開閉フラグ） ---
const isSidebarOpen = ref(false);   // モバイル画面でサイドバードロワーが開いているか（true/false）
const isTaskModalOpen = ref(false); // 新規タスク追加モーダルが開いているか（true/false）
const activeMenuTask = ref(null);   // 現在アクションメニュー（三点リーダー等）を開いている対象のタスクオブジェクト
const activeMenuType = ref(null);   // 開いているメニューの種類（例: 'due', 'category', 'bulkDue' など）

/**
 * 【個別メニューオープン関数】
 * 各タスクのメニューボタンが押されたときに、対象タスクとメニューの種類をセットします。
 * @param {Object} task - 操作対象のタスクデータ
 * @param {String} type - 開くメニューの種類を識別する文字列
 * @param {Event} event - ブラウザのクリックイベントオブジェクト
 */
const openMenuModal = (task, type, event) => {
    // 【実務の重要テクニック】イベントバブリング（親要素へのクリック伝播）を阻止します。
    // これを怠ると、ボタンを押した瞬間に「行全体のクリックイベント」まで誤発作してしまいます。
    if (event) event.stopPropagation(); 
    activeMenuTask.value = task;
    activeMenuType.value = type;
};

/**
 * 【メニュークローズ関数】
 * モーダルやポップアップメニューを閉じ、保持していた状態をクリア（初期化）します。
 */
const closeMenuModal = () => {
    activeMenuTask.value = null;
    activeMenuType.value = null;
};

// --- 6. タスク操作（CRUD・一括処理）のバインド ---
const { 
    bulkUpdate,           // 複数タスク一括更新の共通API通信関数
    toggleTask,           // タスクの完了/未完了を切り替える関数
    updateTitle,          // タスク名をインライン編集して保存する関数
    updateCategoryAndSub, // カテゴリとサブカテゴリを個別に更新する関数
    updatePriority,       // 優先度を更新する関数
    updateDueDate,        // 期限日を更新する関数
    deleteTask,           // 単一タスクを削除する関数
    bulkDelete            // 選択された複数タスクを一括削除する関数
} = useTaskOperations(selectedTaskIds, isSelectionMode, triggerMovedHighlight, closeMenuModal, showToast);

// 【一括処理のエイリアス関数群】
// UIコンポーネントからシンプルに呼び出せるよう、共通の bulkUpdate に具体的な変更データとメッセージを渡してラップしています。
const bulkComplete = (isCompleted) => bulkUpdate({ is_completed: isCompleted }, '選択したタスクの状態を更新しました');
const bulkUpdateDueDate = (dueDate) => bulkUpdate({ due_date: dueDate }, '期限を一括更新しました');
const bulkUpdateCategoryAndSub = (category, subCategory) => bulkUpdate({ category, sub_category: subCategory }, 'カテゴリを一括変更しました');
const bulkUpdatePriority = (priority) => bulkUpdate({ priority }, '優先度を一括変更しました');

// --- 7. 新規タスク作成フォーム（useForm）の初期化 ---
const todayStr = new Date().toISOString().split('T')[0]; // 本日の日付を 'YYYY-MM-DD' 形式の文字列で取得

// 現在のカテゴリが 'all' や 'today' の場合は新規作成時のデフォルトとして 'inbox' を割り当てる安全ガード処理
const activeCategoryKey = ['all', 'today'].includes(props.currentCategory) ? 'inbox' : props.currentCategory;

// 該当カテゴリに紐づくデフォルトのサブカテゴリを取得（存在しない場合は 'general'）
const defaultSubKey = categoryTree[activeCategoryKey]?.defaultSub || 'general';

/**
 * 【Inertia useForm の定義】
 * フォームの入力値管理だけでなく、サーバー送信時のローディング状態（processing）や、
 * バリデーションエラー（errors）の自動保持を裏側で全自動で行ってくれる強力なフックです。
 */
const form = useForm({
    title: '',              // タスク名（最初は空文字）
    due_date: todayStr,     // 期限日（デフォルトで今日の日付をセット）
    category: activeCategoryKey,       // カテゴリ
    sub_category: defaultSubKey,       // サブカテゴリ
    priority: 'medium',     // 優先度（デフォルトは中）
});

/**
 * 【新規タスク送信関数】
 * フォームに入力されたデータをサーバーへ非同期送信します。
 */
const submitTask = () => {
    // 【ガード句】タイトルが空、またはスペースのみの場合は処理を中断
    if (!form.title.trim()) return; 

    form.post(route('tasks.store'), {
        preserveScroll: true, // 【UX向上】送信後に画面が一番上に強制スクロールされるのを防ぎ、現在の位置を維持する
        onSuccess: () => {
            showToast('タスクを追加しました'); // 成功トーストを表示
            form.reset('title');       // 入力されたタイトル文字だけをリセット
            isTaskModalOpen.value = false; // モーダルを閉じる
        }
    });
};
</script>

<template>
    <!-- ブラウザのタブタイトルを設定する Inertia のコンポーネント -->
    <Head title="Tasks Dashboard" />

    <AuthenticatedLayout>
        <!-- ─── トースト通知エリア（画面右上からスライドイン表示） ─── -->
        <Transition name="slide-up">
            <div v-if="toastMessage" class="fixed top-16 right-4 z-50 bg-slate-900 text-white text-xs px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-700">
                <span>✨</span>
                <span>{{ toastMessage }}</span>
            </div>
        </Transition>

        <!-- ─── ヘッダーセクション（画面最上部のタイトルとメインアクションボタン） ─── -->
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span>📋</span> タスクダッシュボード
                </h2>
                <div class="flex items-center gap-2">
                    <!-- モバイル専用：サイドバードロワーを開くボタン（PC画面では非表示: lg:hidden） -->
                    <button @click="isSidebarOpen = true" class="lg:hidden p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold shadow-xs active:scale-95 cursor-pointer">
                        📂 メニュー
                    </button>
                    <!-- 新規タスク追加モーダルを開くボタン -->
                    <button @click="isTaskModalOpen = true" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md transition active:scale-95 cursor-pointer flex items-center gap-1.5">
                        <span>+</span> 新規タスク
                    </button>
                </div>
            </div>
        </template>

        <!-- ─── メインコンテンツ全体レイアウト ─── -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 lg:pb-12">
            <div class="flex gap-8 items-start">
                
                <!-- PC用サイドバー（カテゴリツリー・メニュー一覧を表示） -->
                <DesktopSidebar :tasks="tasks" :current-category="currentCategory" :today-str="todayStr" />

                <!-- メインリスト領域（幅いっぱいに広がり、内部の要素を縦に積み上げる） -->
                <div class="flex-1 min-w-0 space-y-4">
                    
                    <!-- 検索・フィルター・ソートコントロールバー（UI層へのデータバインディング） -->
                    <TaskControlBar 
                        v-model:search-query="searchQuery"
                        v-model:selected-category-filter="selectedCategoryFilter"
                        v-model:sort-by="sortBy"
                        :sort-order="sortOrder"
                        :is-selection-mode="isSelectionMode"
                        @toggle-sort-order="toggleSortOrder"
                        @toggle-selection-mode="toggleSelectionMode"
                    />

                    <!-- 未完了タスク一覧セクション -->
                    <TaskListSection 
                        title="未完了タスク"
                        :tasks="activeTasks"
                        :is-selection-mode="isSelectionMode"
                        :selected-task-ids="selectedTaskIds"
                        :new-ids="newIds"
                        :recently-moved-map="recentlyMovedMap"
                        :blinking-map="blinkingMap"
                        :show-select-all-button="true"
                        :is-all-selected="activeTasks.length > 0 && activeTasks.every(t => selectedTaskIds.includes(t.id))"
                        @toggle-select-all="toggleSelectActive"
                        @toggle="toggleTask"
                        @select="toggleTaskSelection"
                        @delete="deleteTask"
                        @update-title="updateTitle"
                        @open-menu="openMenuModal"
                    />

                    <!-- 完了済みタスク一覧セクション（完了タスクが0件の場合はDOMから非表示にする） -->
                    <TaskListSection 
                        v-if="completedTasksList.length > 0"
                        title="完了済み"
                        :tasks="completedTasksList"
                        :is-selection-mode="isSelectionMode"
                        :selected-task-ids="selectedTaskIds"
                        :new-ids="newIds"
                        :recently-moved-map="recentlyMovedMap"
                        :blinking-map="blinkingMap"
                        :show-select-all-button="true"
                        :is-all-selected="completedTasksList.length > 0 && completedTasksList.every(t => selectedTaskIds.includes(t.id))"
                        class="opacity-75"
                        @toggle-select-all="toggleSelectCompleted"
                        @toggle="toggleTask"
                        @select="toggleTaskSelection"
                        @delete="deleteTask"
                        @update-title="updateTitle"
                        @open-menu="openMenuModal"
                    />

                </div>
            </div>
        </div>

        <!-- ─── モバイル用ドロワーメニュー（画面下部からスライドアップするモーダル背景） ─── -->
        <Transition name="slide-up">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs lg:hidden flex items-end">
                <!-- @click.stop により、内側の要素をクリックしても背景の閉じ処理が誤作動しないようにガード -->
                <div @click.stop class="bg-white w-full rounded-t-3xl p-6 space-y-4 max-h-[85vh] overflow-y-auto">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto"></div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-900">メニュー</span>
                        <button @click="isSidebarOpen = false" class="text-slate-400 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <MobileDrawer :tasks="tasks" :current-category="currentCategory" :today-str="todayStr" @close="isSidebarOpen = false" />
                </div>
            </div>
        </Transition>

        <!-- ─── 各種モーダル・フローティング操作バー群 ─── -->
        
        <!-- 1. 新規タスク作成モーダル -->
        <TaskFormModal :is-open="isTaskModalOpen" :form="form" @close="isTaskModalOpen = false" @submit="submitTask" />

        <!-- 2. 個別タスク・一括操作用アクションモーダル（期限変更、カテゴリ変更、優先度変更など） -->
        <TaskActionModal 
            :active-menu-task="activeMenuTask"
            :active-menu-type="activeMenuType"
            @close="closeMenuModal"
            @update-category="updateCategoryAndSub"
            @update-priority="updatePriority"
            @update-due="updateDueDate"
            @bulk-update-due="bulkUpdateDueDate"
            @bulk-update-category="bulkUpdateCategoryAndSub"
            @bulk-update-priority="bulkUpdatePriority"
        />

        <!-- 3. 複数選択時のみ画面下部に浮上する一括アクションバー -->
        <BulkActionBar 
            v-if="selectedTaskIds.length > 0"
            :selected-count="selectedTaskIds.length"
            @complete="bulkComplete(true)"
            @uncomplete="bulkComplete(false)"
            @open-due-modal="openMenuModal({ id: 'bulk' }, 'bulkDue', $event)"
            @open-category-modal="openMenuModal({ id: 'bulk' }, 'bulkCategory', $event)"
            @open-priority-modal="openMenuModal({ id: 'bulk' }, 'bulkPriority', $event)"
            @delete="bulkDelete"
            @clear="selectedTaskIds = []; isSelectionMode = false;"
        />

        <!-- 4. モバイル専用ボトムナビゲーションバー（スマホ画面での常時表示ナビ） -->
        <MobileNav 
            :current-category="currentCategory"
            :today-count="tasks.filter(t => t.due_date === todayStr).length"
            :inbox-count="tasks.filter(t => t.category === 'inbox').length"
            @open-menu="isSidebarOpen = true"
            @open-task-modal="isTaskModalOpen = true"
        />

    </AuthenticatedLayout>
</template>