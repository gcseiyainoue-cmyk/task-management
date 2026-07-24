<script setup>
defineProps({
    selectedCount: Number,
});

defineEmits([
    'complete', 'uncomplete', 'open-due-modal', 
    'open-category-modal', 'open-priority-modal', 'delete', 'clear'
]);
</script>

<template>
    <div class="fixed bottom-20 left-4 right-4 sm:left-1/2 sm:-translate-x-1/2 sm:w-auto z-50 bg-slate-900/95 backdrop-blur-md text-white text-xs p-3.5 rounded-2xl shadow-2xl flex items-center gap-2 sm:gap-3 border border-slate-700 flex-wrap justify-center">
        <span class="font-bold text-amber-400 px-1">{{ selectedCount }}件選択中</span>
        <div class="h-4 w-px bg-slate-700 hidden sm:block"></div>
        
        <button @click="$emit('complete')" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            ✅ 完了
        </button>
        <button @click="$emit('uncomplete')" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            ⏳ 未完了
        </button>

        <button @click="$emit('open-due-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>📅</span> 期限
        </button>

        <button @click="$emit('open-category-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>🏷️</span> カテゴリ
        </button>

        <button @click="$emit('open-priority-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>⚡</span> 優先度
        </button>

        <button @click="$emit('delete')" class="bg-rose-950/80 hover:bg-rose-900 active:scale-95 text-rose-200 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            🗑️ 削除
        </button>
        <button @click="$emit('clear')" class="text-slate-400 hover:text-white px-2 py-2 ml-1" title="閉じる">
            ✕
        </button>
    </div>
</template>