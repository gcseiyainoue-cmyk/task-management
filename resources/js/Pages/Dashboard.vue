<script setup>
import { ref, computed, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

// コンポーネント・コンポーザブルのインポート
import { useTaskUtils } from '@/Composables/useTaskUtils';
import TaskSummary from '@/Components/TaskSummary.vue';
import TaskList from '@/Components/TaskList.vue';
import TaskTabs from '@/Components/TaskTabs.vue';

const props = defineProps({
    allTasks: Array,
    todayTasks: Array,
    overdueTasks: Array,
    weeklyTasks: Array,
    somedayTasks: Array,
    completedTasks: Array,
});

const { isToday, isExpired } = useTaskUtils();

// 状態管理
const activeTab = ref('all');
const hideCompleted = ref(false);
const searchQuery = ref('');
const sortBy = ref('due_date');
const todayDragTasks = ref([...props.todayTasks]);

// 【自動ソート機能】今日のタスクの更新を監視して完了済みを下に
watch(() => props.todayTasks, (newTasks) => {
    todayDragTasks.value = [...newTasks].sort((a, b) => {
        const aCompleted = a.status === 2;
        const bCompleted = b.status === 2;
        if (aCompleted && !bCompleted) return 1;
        if (!aCompleted && bCompleted) return -1;
        return 0;
    });
}, { deep: true, immediate: true });

// --- 算出プロパティ ---
const filteredTasks = computed(() => {
    let tasks = [...props.allTasks];
    if (hideCompleted.value) tasks = tasks.filter(task => task.status !== 2);
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        tasks = tasks.filter(task => 
            task.title.toLowerCase().includes(query) || 
            (task.description && task.description.toLowerCase().includes(query)) ||
            (task.category && task.category.toLowerCase().includes(query))
        );
    }
    tasks.sort((a, b) => {
        if (sortBy.value === 'due_date') return new Date(a.due_date || '9999-12-31') - new Date(b.due_date || '9999-12-31');
        if (sortBy.value === 'title') return a.title.localeCompare(b.title);
        return 0;
    });
    return tasks;
});

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

                <TaskTabs v-model:active-tab="activeTab" />

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200/60 p-6">
                    <TaskList v-if="activeTab === 'overdue'" :tasks="overdueTasks" @update-status="updateStatus" @delete-task="deleteTask" />
                    <TaskList v-if="activeTab === 'all'" :tasks="filteredTasks" @update-status="updateStatus" @delete-task="deleteTask" />
                    <TaskList 
                        v-if="activeTab === 'today'" 
                        v-model:tasks="todayDragTasks"
                        :is-draggable="true" 
                        :show-priority="true"
                        @reorder="onEndToday"
                        @update-status="updateStatus" 
                        @delete-task="deleteTask" 
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>