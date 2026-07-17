<script setup>
defineProps({
    activeTab: String
});

defineEmits(['update:activeTab']);
</script>

<template>
    <div class="flex border-b border-slate-200 overflow-x-auto">
        <button 
            @click="$emit('update:activeTab', 'overdue')" 
            :class="activeTab === 'overdue' ? 'border-b-2 border-rose-600 text-rose-600' : 'text-slate-400 hover:text-rose-500'" 
            class="px-6 py-2.5 font-bold text-sm transition"
        >⚠️ 期限切れ</button>
        <button 
            @click="$emit('update:activeTab', 'today')" 
            :class="activeTab === 'today' ? 'border-b-2 border-slate-800 text-slate-800' : 'text-slate-400 hover:text-slate-600'" 
            class="px-6 py-2.5 font-medium text-sm transition"
        >今日のタスク</button>
        <button 
            @click="$emit('update:activeTab', 'all')" 
            :class="activeTab === 'all' ? 'border-b-2 border-slate-800 text-slate-800' : 'text-slate-400 hover:text-slate-600'" 
            class="px-6 py-2.5 font-medium text-sm transition"
        >マイタスク一覧</button>
    </div>
</template>