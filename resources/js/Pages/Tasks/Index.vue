<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { categoryTree } from '@/Constants/task';

// --- Composables ---
import { useTaskHighlight } from '@/Composables/useTaskHighlight';
import { useTaskFilterAndSort } from '@/Composables/useTaskFilterAndSort';
import { useTaskSelection } from '@/Composables/useTaskSelection';
import { useTaskOperations } from '@/Composables/useTaskOperations';
import { useToast } from '@/Composables/useToast';

// --- Components ---
import DesktopSidebar from '@/Components/Tasks/DesktopSidebar.vue';
import MobileDrawer from '@/Components/Tasks/MobileDrawer.vue';
import TaskControlBar from '@/Components/Tasks/TaskControlBar.vue';
import TaskListSection from '@/Components/Tasks/TaskListSection.vue';
import TaskFormModal from '@/Components/Tasks/TaskFormModal.vue';
import TaskActionModal from '@/Components/Tasks/TaskActionModal.vue';
import BulkActionBar from '@/Components/Tasks/BulkActionBar.vue';
import MobileNav from '@/Components/Tasks/MobileNav.vue';

const props = defineProps({
    tasks: Array,          
    filteredTasks: Array,  
    currentCategory: String, 
});

const { toastMessage, showToast } = useToast();
const { newIds, blinkingMap, recentlyMovedMap, triggerMovedHighlight } = useTaskHighlight();

const { 
    searchQuery, 
    selectedCategoryFilter, 
    sortBy, 
    sortOrder, 
    toggleSortOrder, 
    activeTasks, 
    completedTasksList 
} = useTaskFilterAndSort(props);

const { 
    isSelectionMode, 
    selectedTaskIds, 
    toggleSelectionMode, 
    toggleTaskSelection, 
    toggleSelectActive, 
    toggleSelectCompleted 
} = useTaskSelection(activeTasks, completedTasksList);

const isSidebarOpen = ref(false);
const isTaskModalOpen = ref(false);
const activeMenuTask = ref(null);
const activeMenuType = ref(null);

const openMenuModal = (task, type, event) => {
    if (event) event.stopPropagation();
    activeMenuTask.value = task;
    activeMenuType.value = type;
};

const closeMenuModal = () => {
    activeMenuTask.value = null;
    activeMenuType.value = null;
};

const { 
    bulkUpdate, 
    toggleTask, 
    updateTitle, 
    updateCategoryAndSub, 
    updatePriority, 
    updateDueDate, 
    deleteTask,
    bulkDelete 
} = useTaskOperations(selectedTaskIds, isSelectionMode, triggerMovedHighlight, closeMenuModal, showToast);

const bulkComplete = (isCompleted) => bulkUpdate({ is_completed: isCompleted }, '選択したタスクの状態を更新しました');
const bulkUpdateDueDate = (dueDate) => bulkUpdate({ due_date: dueDate }, '期限を一括更新しました');
const bulkUpdateCategoryAndSub = (category, subCategory) => bulkUpdate({ category, sub_category: subCategory }, 'カテゴリを一括変更しました');
const bulkUpdatePriority = (priority) => bulkUpdate({ priority }, '優先度を一括変更しました');

const todayStr = new Date().toISOString().split('T')[0];
const activeCategoryKey = ['all', 'today'].includes(props.currentCategory) ? 'inbox' : props.currentCategory;
const defaultSubKey = categoryTree[activeCategoryKey]?.defaultSub || 'general';

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
};
</script>

<template>
    <Head title="Tasks Dashboard" />

    <AuthenticatedLayout>
        <!-- トースト通知 -->
        <Transition name="slide-up">
            <div v-if="toastMessage" class="fixed top-16 right-4 z-50 bg-slate-900 text-white text-xs px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-700">
                <span>✨</span>
                <span>{{ toastMessage }}</span>
            </div>
        </Transition>

        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span>📋</span> タスクダッシュボード
                </h2>
                <div class="flex items-center gap-2">
                    <button @click="isSidebarOpen = true" class="lg:hidden p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold shadow-xs active:scale-95 cursor-pointer">
                        📂 メニュー
                    </button>
                    <button @click="isTaskModalOpen = true" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md transition active:scale-95 cursor-pointer flex items-center gap-1.5">
                        <span>+</span> 新規タスク
                    </button>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 lg:pb-12">
            <div class="flex gap-8 items-start">
                
                <!-- PC用サイドバー -->
                <DesktopSidebar :tasks="tasks" :current-category="currentCategory" :today-str="todayStr" />

                <!-- メインコンテンツ領域 -->
                <div class="flex-1 min-w-0 space-y-4">
                    
                    <!-- 検索・フィルター・ソートバー -->
                    <TaskControlBar 
                        v-model:search-query="searchQuery"
                        v-model:selected-category-filter="selectedCategoryFilter"
                        v-model:sort-by="sortBy"
                        :sort-order="sortOrder"
                        :is-selection-mode="isSelectionMode"
                        @toggle-sort-order="toggleSortOrder"
                        @toggle-selection-mode="toggleSelectionMode"
                    />

                    <!-- 未完了タスク一覧 -->
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

                    <!-- 完了済みタスク一覧 -->
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

        <!-- モバイル用ドロワー -->
        <Transition name="slide-up">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs lg:hidden flex items-end">
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

        <!-- モーダル・ナビゲーション類 -->
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
            @open-task-modal="isTaskModalOpen = true"
        />

    </AuthenticatedLayout>
</template>