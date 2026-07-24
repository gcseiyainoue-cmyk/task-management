<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    currentCategory: String,
    todayCount: Number,
    inboxCount: Number,
});

defineEmits(['open-menu', 'open-task-modal']);
</script>

<template>
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/90 backdrop-blur-md border-t border-slate-200/80 px-4 py-2 lg:hidden flex items-center justify-around shadow-lg">
        <!-- すべて -->
        <Link 
            :href="route('dashboard', { category: 'all' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl', currentCategory === 'all' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📂</span>
            <span>すべて</span>
        </Link>

        <!-- 今日 -->
        <Link 
            :href="route('dashboard', { category: 'today' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl relative', currentCategory === 'today' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📅</span>
            <span>今日</span>
            <span v-if="todayCount > 0" class="absolute -top-1 -right-1 bg-slate-900 text-white text-[9px] font-mono px-1.5 py-0.2 rounded-full">
                {{ todayCount }}
            </span>
        </Link>

        <!-- 新規作成ボタン（半角の「+」に変更し、正円で中央配置を調整） -->
        <button 
            @click="$emit('open-task-modal')"
            class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-900 text-white shadow-md active:scale-95 transition cursor-pointer hover:bg-slate-800"
        >
            <span class="text-xl font-normal leading-none">+</span>
        </button>

        <!-- 未分類 -->
        <Link 
            :href="route('dashboard', { category: 'inbox' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl relative', currentCategory === 'inbox' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📥</span>
            <span>未分類</span>
            <span v-if="inboxCount > 0" class="absolute -top-1 -right-1 bg-slate-900 text-white text-[9px] font-mono px-1.5 py-0.2 rounded-full">
                {{ inboxCount }}
            </span>
        </Link>

        <!-- メニューボタン -->
        <button 
            @click="$emit('open-menu')"
            class="flex flex-col items-center gap-0.5 text-[10px] font-bold text-slate-400 hover:text-slate-600 transition px-2 py-1 rounded-xl cursor-pointer"
        >
            <span class="text-base">🍔</span>
            <span>メニュー</span>
        </button>
    </div>
</template>