<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 Dashboard.vue
 * 【アーキテクチャ上の位置づけ】 統括レイヤー（コーディネーター / コントローラー）
 * =====================================================================================
 * 【実務における設計思想】
 * このファイル自身は「複雑な計算」や「見た目の細かいデザイン」を直接持ちません。
 * 裏側のロジック（Composables）と、表側の見た目（Components）をインポートし、
 * 「データとイベントのハブ（交差点）」としてのみ機能させることで、コード全体の見通しを爆発的に高めています。
 */

// --- 1. コアライブラリ・フレームワークのインポート ---
import { ref } from 'vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { categoryTree } from '@/Constants/task';

// --- 2. ビジネスロジック層（Composables）のインポート ---
import { useTaskHighlight } from '@/Composables/useTaskHighlight';       // タスク移動・新規追加時のハイライト・点滅アニメーション制御
import { useTaskFilterAndSort } from '@/Composables/useTaskFilterAndSort'; // 検索文字、カテゴリ絞り込み、ソート順の計算ロジック
import { useTaskSelection } from '@/Composables/useTaskSelection';       // 一括選択モード、選択中タスクID配列の状態管理
import { useTaskOperations } from '@/Composables/useTaskOperations';       // タスクのCRUD（作成・更新・削除）および一括API通信
import { useRoutineOperations } from '@/Composables/useRoutineOperations'; // 🔄 ルーティン操作用のCRUDおよび状態管理
import { useToast } from '@/Composables/useToast';                       // ユーザーへのフィードバック用トースト通知の表示・消去制御

// --- 3. UIコンポーネント層のインポート ---
import DesktopSidebar from '@/Components/Tasks/DesktopSidebar.vue';      // PC用サイドバー（カテゴリツリーや各種メニューを表示）
import MobileDrawer from '@/Components/Tasks/MobileDrawer.vue';          // モバイル用ドロワーメニュー（スマホ表示時のサイドバー代替）
import TaskControlBar from '@/Components/Tasks/TaskControlBar.vue';      // 検索窓・カテゴリ絞り込み・ソート切替を行うコントロールバー
import TaskListSection from '@/Components/Tasks/TaskListSection.vue';    // タスクのリスト一覧を表示するセクションコンポーネント
import RoutineManagerSection from '@/Components/Tasks/RoutineManagerSection.vue'; // ルーティン管理画面コンポーネント
import TaskFormModal from '@/Components/Tasks/TaskFormModal.vue';          // 新規タスク作成用のモーダルフォーム
import TaskActionModal from '@/Components/Tasks/TaskActionModal.vue';    // 個別タスクの期限・カテゴリ・優先度変更などの操作用モーダル
import BulkActionBar from '@/Components/Tasks/BulkActionBar.vue';          // 複数選択時のみ画面下部に浮上する一括操作バー
import MobileNav from '@/Components/Tasks/MobileNav.vue';                // モバイル専用のボトムナビゲーションバー

/**
 * -------------------------------------------------------------------------------------
 * 【Props定義】 バックエンド（Laravel）からのデータ受け取り
 * -------------------------------------------------------------------------------------
 */
const props = defineProps({
    /**
     * データベースから取得したすべてのタスク一覧
     */
    tasks: {
        type: Array,
        default: () => [],
    },
    /**
     * （予備・互換用）フィルター済みタスク配列
     */
    filteredTasks: {
        type: Array,
        default: () => [],
    },
    /**
     * 現在選択されているサイドバーのカテゴリキー（'inbox', 'today', 'routines' など）
     */
    currentCategory: {
        type: String,
        default: 'inbox',
    },
    /**
     * 現在のビュー種別
     */
    currentView: {
        type: String,
        default: null,
    },
    /**
     * バックエンドから受け取るルーティン一覧
     */
    routineTemplates: {
        type: Array,
        default: () => [],
    },
});

// --- 4. 各種Composableの実行とリアクティブ変数の分割代入 ---
const { toastMessage, showToast } = useToast();
const { newIds, blinkingMap, recentlyMovedMap, triggerMovedHighlight } = useTaskHighlight();

// 検索・絞り込み・ソートの計算結果および条件変更用関数を取得
const { 
    searchQuery,                // 検索窓に入力された文字列
    selectedCategoryFilter,     // セレクトボックス等で選ばれた詳細カテゴリ
    sortBy,                     // ソート基準（due_date, priority, title等）
    sortOrder,                  // 昇順（asc）か降順（desc）か
    toggleSortOrder,            // ソート順を反転させる関数
    activeTasks,                // 絞り込み・ソート済みの「未完了タスク」配列
    completedTasksList          // 絞り込み・ソート済みの「完了済みタスク」配列
} = useTaskFilterAndSort(props);

// 一括選択（チェックボックス複数選択）の状態とロジックを取得
const { 
    isSelectionMode,            // 一括選択モードが有効かどうか（Boolean）
    selectedTaskIds,            // 選択されているタスクのIDが格納される配列
    toggleSelectionMode,        // 選択モードのON/OFFを切り替える関数
    toggleTaskSelection,        // 個別タスクのチェック状態を反転させる関数
    toggleSelectActive,         // 未完了タスクを一括全選択/全解除する関数
    toggleSelectCompleted       // 完了済みタスクを一括全選択/全解除する関数
} = useTaskSelection(activeTasks, completedTasksList);

// --- 5. 画面ローカルのUI状態管理 ---
const isSidebarOpen = ref(false);   // モバイル画面でサイドバードロワーが開いているか
const isTaskModalOpen = ref(false); // 新規タスク追加モーダルが開いているか
const activeMenuTask = ref(null);   // 現在アクションメニューを開いている対象のタスクオブジェクト
const activeMenuType = ref(null);   // 開いているメニューの種類

// RoutineManagerSection内のルーティン作成モーダルを外部から操作するための参照
const routineManagerRef = ref(null);

/**
 * 【個別メニューオープン関数】
 */
const openMenuModal = (task, type, event) => {
    if (event) event.stopPropagation(); 
    activeMenuTask.value = task;
    activeMenuType.value = type;
};

/**
 * 【メニュークローズ関数】
 */
const closeMenuModal = () => {
    activeMenuTask.value = null;
    activeMenuType.value = null;
};

// --- 6. タスク操作（CRUD・一括処理）のバインド ---
const { 
    bulkUpdate,           
    toggleTask,           
    updateTitle,          
    updateCategoryAndSub, 
    updatePriority,       
    updateDueDate,        
    deleteTask: rawDeleteTask,           
    bulkDelete            
} = useTaskOperations(selectedTaskIds, isSelectionMode, triggerMovedHighlight, closeMenuModal, showToast);

/**
 * 【堅牢な削除ラッパー関数】
 * 引数として「タスクID（数値/文字列）」または「タスクオブジェクト（{ id: ... }）」のどちらが渡されても安全に処理します。
 */
const deleteTask = (taskOrId, skipConfirm = false) => {
    if (taskOrId !== undefined && taskOrId !== null) {
        rawDeleteTask(taskOrId, skipConfirm);
    }
};

// ルーティン操作用ロジックの取得
const { 
    storeRoutine, 
    updateRoutine, 
    toggleRoutine, 
    deleteRoutine,
    convertTaskToRoutine: convertRoutineAction,
    removeTaskRoutine: removeRoutineAction
} = useRoutineOperations(showToast);

/**
 * 【ルーティンの有効/一時停止を切り替える関数】
 */
const handleToggleRoutineActive = (task) => {
    const routineId = task.routine_template_id || task.routine_template?.id;
    if (!routineId) return;

    router.patch(route('routines.toggle', routineId), {}, {
        preserveScroll: true,
    });
};

// 【一括処理のエイリアス関数群】
const bulkComplete = (isCompleted) => bulkUpdate({ is_completed: isCompleted }, '選択したタスクの状態を更新しました');
const bulkUpdateDueDate = (dueDate) => bulkUpdate({ due_date: dueDate }, '期限を一括更新しました');
const bulkUpdateCategoryAndSub = (category, subCategory) => bulkUpdate({ category, sub_category: subCategory }, 'カテゴリを一括変更しました');
const bulkUpdatePriority = (priority) => bulkUpdate({ priority }, '優先度を一括変更しました');

/**
 * 【タスクをルーティンテンプレート化する関数】
 */
const convertTaskToRoutine = (task) => {
    convertRoutineAction(task, (taskId, skipConfirm) => {
        deleteTask(taskId, skipConfirm);
    });
};

/**
 * 【ルーティン解除・単発タスク化する関数】
 */
const removeTaskRoutine = (task) => {
    removeRoutineAction(task);
};

// --- 7. 新規タスク作成フォーム（useForm）の初期化 ---
const todayStr = new Date().toISOString().split('T')[0]; 

const activeCategoryKey = ['all', 'today', 'routines'].includes(props.currentCategory) ? 'inbox' : props.currentCategory;
const defaultSubKey = categoryTree[activeCategoryKey]?.defaultSub || 'general';

const form = useForm({
    title: '',              
    due_date: todayStr,     
    category: activeCategoryKey,      
    sub_category: defaultSubKey,      
    priority: 'medium',     
});

/**
 * 【新規タスク送信関数】
 */
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
};

/**
 * 【新規作成ボタン押下時の統合アクション】
 */
const handleMainCreateAction = () => {
    if (props.currentCategory === 'routines') {
        routineManagerRef.value?.openCreateModal?.();
    } else {
        isTaskModalOpen.value = true;
    }
};
</script>

<template>
    <Head :title="currentCategory === 'routines' ? 'ルーティン管理' : 'ダッシュボード'" />

    <AuthenticatedLayout>
        <!-- ─── トースト通知エリア ─── -->
        <Transition name="slide-up">
            <div v-if="toastMessage" class="fixed top-16 right-4 z-50 bg-slate-900 text-white text-xs px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-700">
                <span>✨</span>
                <span>{{ toastMessage }}</span>
            </div>
        </Transition>

        <!-- ─── ヘッダーセクション ─── -->
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span>{{ currentCategory === 'routines' ? '🔄' : '📋' }}</span> 
                    {{ currentCategory === 'routines' ? 'ルーティン管理' : 'タスクダッシュボード' }}
                </h2>
                <div class="flex items-center gap-2">
                    <button @click="isSidebarOpen = true" class="lg:hidden p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold shadow-xs active:scale-95 cursor-pointer">
                        📂 メニュー
                    </button>
                    <button 
                        @click="handleMainCreateAction" 
                        class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md transition active:scale-95 cursor-pointer flex items-center gap-1.5"
                    >
                        <span>+</span> {{ currentCategory === 'routines' ? '新規ルーティン' : '新規タスク' }}
                    </button>
                </div>
            </div>
        </template>

        <!-- ─── メインコンテンツ全体レイアウト ─── -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 lg:pb-12">
            <div class="flex gap-8 items-start">
                
                <!-- PC用サイドバー -->
                <DesktopSidebar 
                    :tasks="tasks" 
                    :current-category="currentCategory" 
                    :current-view="currentView" 
                    :today-str="todayStr" 
                    :routine-templates="routineTemplates" 
                />

                <!-- メインリスト領域 -->
                <div class="flex-1 min-w-0 space-y-4">

                    <template v-if="currentCategory === 'routines'">
                        <RoutineManagerSection 
                            ref="routineManagerRef"
                            :routine-templates="routineTemplates"
                            @store="storeRoutine"
                            @update="updateRoutine"
                            @toggle="toggleRoutine"
                            @delete="deleteRoutine"
                        />
                    </template>
                    
                    <template v-else>
                        <!-- 検索・フィルター・ソートコントロールバー -->
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

                        <!-- 完了済みタスク一覧セクション -->
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
                    </template>
                </div>
            </div>
        </div>

        <!-- ─── モバイル用ドロワーメニュー ─── -->
        <Transition name="slide-up">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs lg:hidden flex items-end">
                <div @click.stop class="bg-white w-full rounded-t-3xl p-6 space-y-4 max-h-[85vh] overflow-y-auto">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto"></div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-900">メニュー</span>
                        <button @click="isSidebarOpen = false" class="text-slate-400 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <MobileDrawer :tasks="tasks" :routine-templates="routineTemplates" :current-category="currentCategory" :today-str="todayStr" @close="isSidebarOpen = false" />
                </div>
            </div>
        </Transition>

        <!-- ─── 各種モーダル・フローティング操作バー群 ─── -->
        <TaskFormModal :is-open="isTaskModalOpen" :form="form" @close="isTaskModalOpen = false" @submit="submitTask" />

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
            @convert-to-routine="convertTaskToRoutine"
            @remove-routine="removeTaskRoutine"
            @toggle-routine-active="handleToggleRoutineActive"
            @delete="deleteTask"
        />

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

        <MobileNav 
            :current-category="currentCategory"
            :today-count="tasks.filter(t => t.due_date === todayStr).length"
            :inbox-count="tasks.filter(t => t.category === 'inbox').length"
            @open-menu="isSidebarOpen = true"
            @open-task-modal="handleMainCreateAction"
        />

    </AuthenticatedLayout>
</template>