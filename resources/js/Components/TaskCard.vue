<script setup>
import { useTaskUtils } from '@/Composables/useTaskUtils';

const props = defineProps({
    task: Object,
    showPriority: { type: Boolean, default: false },
    priorityIndex: { type: Number, default: null },
    isSelected: Boolean,
});

const emit = defineEmits(['update-status', 'delete-task', 'edit-task', 'toggle-select']);

const { isToday, isExpired, getStatusBadge, getCardClass, getPriorityBadgeClass } = useTaskUtils();

const updateStatus = () => emit('update-status', props.task);
const deleteTask = () => emit('delete-task', props.task.id);

// 期限に応じた色分けロジック
const getDueDateColor = (dueDate) => {
    if (!dueDate) return { dot: 'bg-slate-300', text: 'text-slate-400' };
    
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const due = new Date(dueDate);
    due.setHours(0, 0, 0, 0);
    
    // 日数の差分を計算
    const diffDays = Math.ceil((due - today) / (1000 * 60 * 60 * 24));
    
    // 期限切れ (グレー)
    if (diffDays < 0) return { dot: 'bg-slate-400', text: 'text-slate-500' };
    // 今日 (赤)
    if (diffDays === 0) return { dot: 'bg-red-500', text: 'text-red-600' };
    // 3日以内 (アンバー)
    if (diffDays <= 3) return { dot: 'bg-amber-400', text: 'text-amber-600' };
    
    // 通常 (デフォルト)
    return { dot: 'bg-slate-300', text: 'text-slate-400' };
};
</script>

<template>
    <div 
        class="bg-white p-4 border border-slate-100 rounded-xl shadow-sm transition-all duration-300 flex items-center gap-3 hover:border-indigo-100 hover:shadow-md"
        :class="getCardClass(task)"
    >
        <!-- 【左側】チェックボックス -->
        <div 
            class="flex-shrink-0 w-8 flex items-center justify-center cursor-pointer"
            @click.stop="$emit('toggle-select', task.id)"
        >
            <input 
                type="checkbox" 
                :checked="isSelected" 
                class="h-5 w-5 rounded-md border-slate-200 text-indigo-600 focus:ring-indigo-500 cursor-pointer transition-all"
            >
        </div>

        <!-- 【中央】コンテンツ -->
        <div class="flex-1 min-w-0 flex flex-col md:flex-row md:items-center gap-2">
            <!-- 優先度バッジとステータス -->
            <div class="flex items-center gap-2 flex-shrink-0">
                <div 
                    v-if="showPriority" 
                    class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-lg text-[10px] font-bold border" 
                    :class="getPriorityBadgeClass(priorityIndex)"
                >
                    {{ priorityIndex + 1 }}
                </div>
                
                <button 
                    @click="updateStatus"
                    type="button"
                    class="px-2 py-0.5 text-[10px] font-bold tracking-wider uppercase rounded-md border transition hover:opacity-80" 
                    :class="getStatusBadge(task.status).class"
                >
                    {{ getStatusBadge(task.status).text }}
                </button>
            </div>

            <!-- タイトルと説明 -->
            <div class="flex-1 min-w-0">
                <h4 
                    class="text-sm font-semibold text-slate-800 truncate" 
                    :class="{ 'line-through text-slate-400': task.status === 2 }"
                >
                    {{ task.title }}
                </h4>
                <p 
                    v-if="task.description" 
                    class="text-[11px] mt-0.5 truncate text-slate-400"
                >
                    {{ task.description }}
                </p>
            </div>

            <!-- 【右側】期限と操作ボタン -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="flex items-center gap-1.5">
                    <span 
                        class="w-1.5 h-1.5 rounded-full"
                        :class="getDueDateColor(task.due_date).dot"
                    ></span>
                    <span 
                        class="text-[11px] font-medium"
                        :class="getDueDateColor(task.due_date).text"
                    >
                        {{ isToday(task.due_date) ? '今日' : (task.due_date || '') }}
                    </span>
                </div>
                
                <div class="flex items-center gap-1">
                    <button 
                        type="button"
                        @click="$emit('edit-task', task)" 
                        class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </button>
                    <button 
                        type="button"
                        @click="deleteTask" 
                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>