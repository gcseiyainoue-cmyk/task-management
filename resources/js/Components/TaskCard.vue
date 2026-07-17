<script setup>
import { Link } from '@inertiajs/vue3';
import { useTaskUtils } from '@/Composables/useTaskUtils';

const props = defineProps({
    task: Object,
    showPriority: { type: Boolean, default: false }, // 「今日のタスク」用
    priorityIndex: { type: Number, default: null }, // 順位
});

const emit = defineEmits(['update-status', 'delete-task']);

const { isToday, isExpired, getStatusBadge, getCardClass, getPriorityBadgeClass } = useTaskUtils();

const updateStatus = () => emit('update-status', props.task);
const deleteTask = () => emit('delete-task', props.task.id);
</script>

<template>
    <div 
        class="p-4 border rounded-lg transition duration-200 flex flex-col md:flex-row md:items-center justify-between gap-4"
        :class="getCardClass(task)"
    >
        <div class="flex-1 flex items-start gap-4">
            <!-- 優先度バッジ（今日のタスク用） -->
            <div 
                v-if="showPriority"
                class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full text-xs border shadow-sm"
                :class="getPriorityBadgeClass(priorityIndex)"
            >
                {{ priorityIndex + 1 }}
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <!-- ステータス切替 -->
                    <button 
                        @click="updateStatus"
                        type="button"
                        class="px-2 py-0.5 text-[11px] font-semibold rounded-full border transition hover:opacity-80 focus:outline-none" 
                        :class="getStatusBadge(task.status).class"
                    >
                        {{ getStatusBadge(task.status).text }}
                    </button>
                    <!-- カテゴリ -->
                    <span v-if="task.category" class="text-[11px] font-medium bg-slate-50 text-slate-500 px-2 py-0.5 rounded border border-slate-200" :class="{ 'opacity-50': task.status === 2 }">
                        {{ task.category }}
                    </span>
                    <!-- タイトル -->
                    <h4 class="text-sm font-bold text-slate-700" :class="{ 'line-through text-slate-400': task.status === 2 }">
                        {{ task.title }}
                    </h4>
                </div>
                <p v-if="task.description" class="text-xs mt-1 leading-relaxed" :class="task.status === 2 ? 'text-slate-300' : 'text-slate-500'">{{ task.description }}</p>
            </div>
        </div>
        
        <!-- 操作エリア -->
        <div class="flex items-center gap-4 justify-between md:justify-end border-t md:border-t-0 border-slate-100 pt-3 md:pt-0">
            <span 
                :class="[
                    'text-[11px] px-2 py-1 rounded whitespace-nowrap font-medium border',
                    isExpired(task.due_date) && task.status !== 2
                        ? 'bg-rose-50 text-rose-600 border-rose-200'
                        : isToday(task.due_date)
                        ? 'bg-indigo-50 text-indigo-600 border-indigo-200'
                        : 'bg-slate-50 text-slate-500 border-slate-200'
                ]"
            >
                期限: {{ isToday(task.due_date) ? '今日' : (task.due_date || '未設定') }}
            </span>
            
            <div class="flex items-center gap-1" :class="{ 'opacity-40': task.status === 2 }">
                <Link 
                    :href="route('tasks.edit', task.id)" 
                    class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-full hover:bg-slate-100 transition-all duration-150"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </Link>
                <button 
                    @click="deleteTask" 
                    class="p-1.5 text-slate-400 hover:text-rose-600 rounded-full hover:bg-rose-50 transition-all duration-150"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </div>
        </div>
    </div>
</template>