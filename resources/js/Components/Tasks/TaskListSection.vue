<script setup>
import TaskItem from '@/Components/Tasks/TaskItem.vue';

defineProps({
    title: String,
    tasks: Array,
    isSelectionMode: Boolean,
    selectedTaskIds: Array,
    newIds: Array,
    recentlyMovedMap: Object,
    blinkingMap: Object,
    showSelectAllButton: Boolean,
    isAllSelected: Boolean,
});

defineEmits([
    'toggleSelectAll',
    'toggle',
    'select',
    'delete',
    'updateTitle',
    'openMenu'
]);
</script>

<template>
    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-sm space-y-3">
        <!-- ヘッダー（件数 ＆ 一括選択トグル） -->
        <div class="flex items-center justify-between text-xs font-bold text-slate-400 px-1 pb-1">
            <span>{{ title }} ({{ tasks.length }})</span>
            <button 
                v-if="isSelectionMode && showSelectAllButton && tasks.length > 0"
                @click="$emit('toggleSelectAll')"
                class="text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-2.5 py-1 rounded-lg transition text-[11px] cursor-pointer"
            >
                {{ isAllSelected ? '選択を解除' : 'すべて選択' }}
            </button>
        </div>

        <!-- タスクがない場合の空表示 -->
        <div v-if="tasks.length === 0" class="text-center py-16 text-slate-400 text-xs font-medium space-y-1">
            <p class="text-base">🎉</p>
            <p>表示するタスクはありません</p>
        </div>

        <!-- タスクカードリスト -->
        <TransitionGroup 
            name="task-list" 
            tag="div" 
            class="relative space-y-2.5"
        >
            <TaskItem 
                v-for="task in tasks" 
                :key="task.id"
                :task="task"
                :is-selection-mode="isSelectionMode"
                :is-selected="selectedTaskIds.includes(task.id)"
                :is-highlighted="newIds.includes(task.id) || recentlyMovedMap[task.id]"
                :is-flashing="blinkingMap[task.id] || recentlyMovedMap[task.id]"
                @toggle="$emit('toggle', $event)"
                @select="$emit('select', $event)"
                @delete="$emit('delete', $event)"
                @update-title="(t, title) => $emit('updateTitle', t, title)"
                @open-menu="(task, type, event) => $emit('openMenu', task, type, event)"
            />
        </TransitionGroup>
    </div>
</template>

<style scoped>
.task-list-move {
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.task-list-enter-active,
.task-list-leave-active {
    transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.task-list-enter-from {
    opacity: 0;
    transform: translateY(-16px) scale(0.96);
}
.task-list-leave-to {
    opacity: 0;
    transform: translateY(20px) scale(0.95);
}
.task-list-leave-active {
    position: absolute !important;
    width: 100%;
    z-index: 0;
}
</style>