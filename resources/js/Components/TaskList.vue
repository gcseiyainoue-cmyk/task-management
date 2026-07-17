<script setup>
import { computed } from 'vue';
import draggable from 'vuedraggable';
import TaskCard from '@/Components/TaskCard.vue';

const props = defineProps({
    tasks: Array,
    isDraggable: { type: Boolean, default: false },
    showPriority: { type: Boolean, default: false }
});

const emit = defineEmits(['update-status', 'delete-task', 'update:tasks', 'reorder']);

// v-model用
const modelValue = computed({
    get: () => props.tasks,
    set: (value) => emit('update:tasks', value)
});
</script>

<template>
    <!-- ドラッグ可能な場合 -->
    <draggable 
        v-if="isDraggable" 
        v-model="modelValue" 
        item-key="id" 
        class="space-y-3"
        @end="$emit('reorder')"
    >
        <template #item="{ element: task, index }">
            <TaskCard 
                :task="task" 
                :show-priority="showPriority" 
                :priority-index="index" 
                @update-status="$emit('update-status', task)" 
                @delete-task="$emit('delete-task', task.id)" 
            />
        </template>
    </draggable>

    <!-- 通常リストの場合 -->
    <div v-else class="space-y-3">
        <TaskCard 
            v-for="task in tasks" 
            :key="task.id" 
            :task="task" 
            @update-status="$emit('update-status', task)" 
            @delete-task="$emit('delete-task', task.id)" 
        />
    </div>
</template>