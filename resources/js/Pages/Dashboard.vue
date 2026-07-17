<script setup>
import { ref, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import { useTaskUtils } from '@/Composables/useTaskUtils';
import TaskSummary from '@/Components/TaskSummary.vue';
import TaskList from '@/Components/TaskList.vue';
import TaskTabs from '@/Components/TaskTabs.vue';
import TaskEditModal from '@/Components/TaskEditModal.vue';

const props = defineProps({
    allTasks: Array,
    todayTasks: Array,
    overdueTasks: Array,
    weeklyTasks: Array,
    somedayTasks: Array,
    completedTasks: Array,
});

const isEditModalOpen = ref(false);
const editingTask = ref(null);

const openEditModal = (task) => {
    editingTask.value = task;
    isEditModalOpen.value = true;
};

const { isToday, isExpired } = useTaskUtils();

const activeTab = ref('all');
const hideCompleted = ref(false);
const searchQuery = ref('');
const sortBy = ref('due_date');
const todayDragTasks = ref([...props.todayTasks]);

watch(() => props.todayTasks, (newTasks) => {
    todayDragTasks.value = [...newTasks].sort((a, b) => {
        const aCompleted = a.status === 2;
        const bCompleted = b.status === 2;
        if (aCompleted && !bCompleted) return 1;
        if (!aCompleted && bCompleted) return -1;
        return 0;
    });
}, { deep: true, immediate: true });

// --- 共通フィルタリングロジック ---
const applyFilters = (tasks) => {
    let result = [...tasks];
    
    if (hideCompleted.value) result = result.filter(t => t.status !== 2);
    
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(t => 
            t.title.toLowerCase().includes(query) || 
            (t.description && t.description.toLowerCase().includes(query)) ||
            (t.category && t.category.toLowerCase().includes(query))
        );
    }
    
    result.sort((a, b) => {
        if (sortBy.value === 'due_date') return new Date(a.due_date || '9999-12-31') - new Date(b.due_date || '9999-12-31');
        if (sortBy.value === 'title') return a.title.localeCompare(b.title);
        if (sortBy.value === 'category') return (a.category || '未分類').localeCompare(b.category || '未分類');
        return 0;
    });
    
    return result;
};

// --- 各タブのフィルタ済みタスク ---
const filteredAll = computed(() => applyFilters(props.allTasks));
const filteredToday = computed(() => applyFilters(todayDragTasks.value));
const filteredOverdue = computed(() => applyFilters(props.overdueTasks));
const filteredWeekly = computed(() => applyFilters(props.weeklyTasks));
const filteredSomeday = computed(() => applyFilters(props.somedayTasks));
const filteredCompleted = computed(() => applyFilters(props.completedTasks));

// --- その他算出プロパティ ---
const upcomingTasks = computed(() => {
    const today = new Date();
    const threeDaysLater = new Date();
    threeDaysLater.setDate(today.getDate() + 3);
    return props.allTasks.filter(task => {
        if (!task.due_date || task.status === 2) return false;
        const dueDate = new Date(task.due_date);
        return dueDate > today && dueDate <= threeDaysLater;
    });
});

const globalSummary = computed(() => {
    const activeTasks = props.allTasks.filter(t => t.status !== 2);
    const categoryMap = {};
    activeTasks.forEach(t => { categoryMap[t.category || '未分類'] = (categoryMap[t.category || '未分類'] || 0) + 1; });
    return { 
        uncompletedCount: activeTasks.length, 
        expiredCount: activeTasks.filter(t => isExpired(t.due_date)).length, 
        upcomingCount: upcomingTasks.value.length, 
        categoryMap 
    };
});

const todaySummary = computed(() => {
    const total = todayDragTasks.value.length;
    const completed = todayDragTasks.value.filter(t => t.status === 2).length;
    return { total, completed, remaining: total - completed, percentage: total > 0 ? Math.round((completed / total) * 100) : 0 };
});

const chartData = computed(() => {
    if (activeTab.value === 'today') {
        return { labels: ['完了', '残り'], datasets: [{ data: [todaySummary.value.completed, todaySummary.value.remaining], backgroundColor: ['#10b981', '#f1f5f9'] }] };
    } else {
        const categories = Object.keys(globalSummary.value.categoryMap);
        const data = Object.values(globalSummary.value.categoryMap);
        const colors = ['#6366f1', '#3b82f6', '#ec4899', '#f59e0b', '#10b981', '#64748b'];
        return { 
            labels: categories.length > 0 ? categories : ['未完了タスクなし'], 
            datasets: [{ data: data.length > 0 ? data : [1], backgroundColor: data.length > 0 ? colors.slice(0, categories.length) : ['#e2e8f0'] }] 
        };
    }
});

// --- アクション ---
const onEndToday = () => {
    router.patch(route('tasks.reorder'), {
        tasks: todayDragTasks.value.map((task, index) => ({ id: task.id, sort_order: index }))
    }, { preserveScroll: true });
};

const updateStatus = (task) => {
    router.patch(route('tasks.update', task.id), { status: (task.status + 1) % 3 });
};

const deleteTask = (taskId) => {
    if (confirm('本当にこのタスクを削除しますか？')) router.delete(route('tasks.destroy', taskId));
};

const selectedTasks = ref([]); // 選択されたIDを格納

// 選択状態を切り替える関数
const toggleSelect = (taskId) => {
    const index = selectedTasks.value.indexOf(taskId);
    if (index > -1) {
        selectedTasks.value.splice(index, 1);
    } else {
        selectedTasks.value.push(taskId);
    }
};

// 一括完了処理
const bulkUpdateStatus = (status) => {
    router.patch(route('tasks.bulk-update'), {
        ids: selectedTasks.value,
        status: status
    }, {
        onSuccess: () => selectedTasks.value = []
    });
};

// 一括削除処理
const bulkDelete = () => {
    if (confirm(`${selectedTasks.value.length} 個のタスクを削除しますか？`)) {
        router.delete(route('tasks.bulk-destroy'), {
            data: { ids: selectedTasks.value },
            onSuccess: () => selectedTasks.value = []
        });
    }
};

const newDueDate = ref('');

const bulkUpdateDueDate = () => {
    if (!newDueDate.value) return alert('日付を選択してください');
    
    router.patch(route('tasks.bulk-update'), {
        ids: selectedTasks.value,
        due_date: newDueDate.value
    }, {
        onSuccess: () => {
            selectedTasks.value = [];
            newDueDate.value = ''; // リセット
        }
    });
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-lg text-slate-800 leading-tight">Dashboard</h2>
                <Link :href="route('tasks.create')" class="bg-slate-800 text-white px-4 py-2 rounded-md hover:bg-slate-700 transition text-sm font-medium">＋ タスクを作成</Link>
            </div>
        </template>

        <div class="py-12 bg-slate-100/80 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <TaskSummary :active-tab="activeTab" :global-summary="globalSummary" :today-summary="todaySummary" :chart-data="chartData" />

                <!-- コントロールエリア -->
                <div class="flex flex-col gap-4 px-2">
                    <div class="flex justify-between items-center">
                        <TaskTabs v-model:active-tab="activeTab" />
                        <div class="flex items-center gap-4">
                            <label class="flex items-center text-sm text-slate-600 cursor-pointer hover:text-slate-800 transition">
                                <input type="checkbox" v-model="hideCompleted" class="rounded border-slate-300 text-slate-800 shadow-sm focus:ring-slate-500 mr-2">
                                完了済みを隠す
                            </label>
                            <select v-model="sortBy" class="text-sm border-slate-300 rounded-md focus:ring-slate-500 focus:border-slate-500">
                                <option value="due_date">期限順</option>
                                <option value="title">タイトル順</option>
                                <option value="category">カテゴリ順</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <input v-model="searchQuery" type="text" placeholder="タスクを検索..." class="flex-1 text-sm border-slate-300 rounded-md focus:ring-slate-500 focus:border-slate-500">
                        <select v-model="searchQuery" class="text-sm border-slate-300 rounded-md focus:ring-slate-500 focus:border-slate-500 w-40">
                            <option value="">全カテゴリ</option>
                            <option v-for="category in Object.keys(globalSummary.categoryMap)" :key="category" :value="category">{{ category }}</option>
                        </select>
                    </div>
                </div>

                <!-- 1. Grid構造でスムーズに展開・格納（レイアウトシフト防止） -->
                <div 
                    class="grid transition-all duration-300 ease-in-out"
                    :class="selectedTasks.length > 0 ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'"
                >
                    <!-- 2. overflow-hidden がないと閉じた時に中身がはみ出てガタつきます -->
                    <div class="overflow-hidden">
                        <!-- 3. 余白(mb-4)はここ。閉じたときはこれも一緒に消えるため、不自然な隙間が残りません -->
                        <div class="p-4 mb-4 bg-indigo-50 border border-indigo-200 rounded-xl flex items-center justify-between flex-wrap gap-4">
                            
                            <span class="text-sm text-indigo-800 font-medium">
                                {{ selectedTasks.length }} 個のタスクを選択中
                            </span>
                            
                            <!-- 操作エリア：ボタンの強弱を明確に -->
                            <div class="flex items-center gap-6 flex-wrap">
                                
                                <!-- 【主アクション】期限更新：目立つように -->
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="date" 
                                        v-model="newDueDate" 
                                        class="text-sm border-indigo-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                    <button 
                                        @click="bulkUpdateDueDate" 
                                        class="px-4 py-1.5 bg-indigo-600 text-white font-semibold text-sm rounded-md shadow-sm hover:bg-indigo-700 transition"
                                    >
                                        期限を更新
                                    </button>
                                </div>

                                <!-- 区切り線 -->
                                <div class="h-8 w-px bg-indigo-200"></div>

                                <!-- 【副アクション】ステータス管理：控えめに -->
                                <div class="flex gap-4">
                                    <button 
                                        @click="bulkUpdateStatus(0)" 
                                        class="px-3 py-1.5 text-indigo-600 border border-indigo-200 bg-white text-sm rounded-md hover:bg-indigo-50 transition"
                                    >
                                        ⚪ 未着手に変更
                                    </button>
                                    <button 
                                        @click="bulkUpdateStatus(1)" 
                                        class="px-3 py-1.5 text-indigo-600 border border-indigo-200 bg-white text-sm rounded-md hover:bg-indigo-50 transition"
                                    >
                                        ⚡ 進行中に変更
                                    </button>
                                    <button 
                                        @click="bulkUpdateStatus(2)" 
                                        class="px-3 py-1.5 text-indigo-600 border border-indigo-200 bg-white text-sm rounded-md hover:bg-indigo-50 transition"
                                    >
                                        ✅ 完了に変更
                                    </button>
                                    <button 
                                        @click="bulkDelete" 
                                        class="px-3 py-1.5 text-rose-500 hover:text-rose-600 text-sm font-medium transition"
                                    >
                                        削除する
                                    </button>
                                    <button 
                                        @click="selectedTasks = []" 
                                        class="px-3 py-1.5 text-slate-500 hover:text-slate-700 text-sm underline underline-offset-4 decoration-slate-300"
                                    >
                                        選択解除
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- タスクリスト表示エリア -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200/60 p-6">
                    <TaskList 
                        v-if="activeTab === 'overdue'" 
                        :tasks="filteredOverdue" 
                        :selected-tasks="selectedTasks"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                        @edit-task="openEditModal" 
                        @toggle-select="toggleSelect"
                    />
                    <TaskList 
                        v-if="activeTab === 'all'" 
                        :tasks="filteredAll" 
                        :selected-tasks="selectedTasks"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                        @edit-task="openEditModal" 
                        @toggle-select="toggleSelect"
                    />
                    <TaskList 
                        v-if="activeTab === 'today'" 
                        :tasks="filteredToday"
                        :selected-tasks="selectedTasks"
                        @update:tasks="todayDragTasks = $event"
                        :is-draggable="!searchQuery && !hideCompleted" 
                        :show-priority="true"
                        @reorder="onEndToday"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                        @edit-task="openEditModal"
                        @toggle-select="toggleSelect"
                    />
                    <TaskList 
                        v-if="activeTab === 'weekly'" 
                        :tasks="filteredWeekly" 
                        :selected-tasks="selectedTasks"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                        @edit-task="openEditModal" 
                        @toggle-select="toggleSelect"
                    />
                    <TaskList 
                        v-if="activeTab === 'someday'" 
                        :tasks="filteredSomeday" 
                        :selected-tasks="selectedTasks"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                        @edit-task="openEditModal" 
                        @toggle-select="toggleSelect"
                    />
                    <TaskList 
                        v-if="activeTab === 'completed'" 
                        :tasks="filteredCompleted" 
                        :selected-tasks="selectedTasks"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                        @edit-task="openEditModal" 
                        @toggle-select="toggleSelect"
                    />
                </div>
            </div>
        </div>

        <!-- 編集モーダル -->
        <TaskEditModal 
            :show="isEditModalOpen" 
            :task="editingTask" 
            @close="isEditModalOpen = false" 
        />
    </AuthenticatedLayout>
</template>