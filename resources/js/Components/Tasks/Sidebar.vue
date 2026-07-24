<script setup>
import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

defineProps({
    tasks: Array,
    currentCategory: String,
    todayStr: String,
});

defineEmits(['close']);
</script>

<template>
    <div class="space-y-4 pb-1">
        <!-- クイックビュー -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="grid grid-cols-2 gap-1.5">
                <!-- 未分類 -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'inbox' })" 
                    :class="['p-2.5 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-0.5 text-center active:scale-95 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-lg">📥</span>
                    <span class="text-[11px] font-bold">未分類</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === 'inbox').length }}
                    </span>
                </Link>

                <!-- 今日 -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'today' })" 
                    :class="['p-2.5 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-0.5 text-center active:scale-95 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-lg">📅</span>
                    <span class="text-[11px] font-bold">今日</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <!-- すべて -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'all' })" 
                    :class="['col-span-2 p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 active:scale-95 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📂</span> すべてのタスク</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- カテゴリリスト（横長スタイルに変更し、奇数個でも崩れないように修正） -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1.5">
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    @click="$emit('close')"
                    :href="route('dashboard', { category: key })"
                    :class="['p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 active:scale-95 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>
            </div>
        </div>
    </div>
</template>