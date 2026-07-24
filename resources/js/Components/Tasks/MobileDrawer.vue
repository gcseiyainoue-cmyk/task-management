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
    <div class="space-y-4 pb-4">
        <!-- クイックビュー -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="grid grid-cols-2 gap-1.5">
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'today' })" 
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-xl">📅</span>
                    <span class="text-[11px] font-bold">今日</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'all' })" 
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-xl">📂</span>
                    <span class="text-[11px] font-bold">すべてのタスク</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- カテゴリリスト -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1.5">
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    @click="$emit('close')"
                    :href="route('dashboard', { category: key })"
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-4 active:scale-95 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>

                <!-- 改善案: 「未分類」をカテゴリ最下部に区切り線付きで配置 -->
                <div class="pt-1.5 mt-1.5 border-t border-slate-100">
                    <Link 
                        @click="$emit('close')"
                        :href="route('dashboard', { category: 'inbox' })" 
                        :class="['p-3 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-4 active:scale-95 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                    >
                        <span class="flex items-center gap-2 text-xs font-bold">
                            <span>📥</span>
                            <span>未分類</span>
                        </span>
                        <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                            {{ tasks.filter(t => t.category === 'inbox').length }}
                        </span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>