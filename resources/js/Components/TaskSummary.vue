<script setup>
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';

ChartJS.register(ArcElement, Tooltip, Legend);

defineProps({
    activeTab: String,
    globalSummary: Object, // マイタスク用の統計データ
    todaySummary: Object,  // 今日のタスク用の統計データ
    chartData: Object,     // グラフ用のデータ
});
</script>

<template>
    <div class="mb-8">
        <!-- A. マイタスク一覧タブ（リスクと負荷の統計） -->
        <div v-if="activeTab === 'all'" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                <span class="text-slate-400 text-xs font-medium">未完了の総タスク</span>
                <span class="text-xl font-bold text-slate-700 mt-1">{{ globalSummary.uncompletedCount }}</span>
            </div>
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                <span class="text-rose-500 text-xs font-bold">⚠️ 期限切れ</span>
                <span class="text-xl font-bold text-rose-600 mt-1">{{ globalSummary.expiredCount }}</span>
            </div>
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                <span class="text-amber-500 text-xs font-medium">⏳ 3日以内の期限</span>
                <span class="text-xl font-bold text-amber-600 mt-1">{{ globalSummary.upcomingCount }}</span>
            </div>
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col justify-center">
                <span class="text-slate-400 text-xs font-medium mb-2 text-center">未完了タスクの内訳</span>
                <div v-if="Object.keys(globalSummary.categoryMap).length === 0" class="text-xs text-slate-400 text-center py-2">
                    未完了タスクなし
                </div>
                <div v-else class="space-y-1.5 max-h-[70px] overflow-y-auto pr-1">
                    <div v-for="(count, cat) in globalSummary.categoryMap" :key="cat" class="flex flex-col text-xs">
                        <div class="flex justify-between text-[11px] text-slate-600 font-medium mb-0.5">
                            <span class="truncate max-w-[100px]">{{ cat }}</span>
                            <span>{{ count }}個</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-500" :style="{ width: `${(count / globalSummary.uncompletedCount) * 100}%` }"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- B. 今日のタスクタブ（消化率統計） -->
        <div v-else-if="activeTab === 'today'" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                <span class="text-slate-400 text-xs font-medium">今日の総タスク</span>
                <span class="text-xl font-bold text-slate-700 mt-1">{{ todaySummary.total }}</span>
            </div>
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                <span class="text-indigo-500 text-xs font-medium">今日の残り</span>
                <span class="text-xl font-bold text-indigo-600 mt-1">{{ todaySummary.remaining }}</span>
            </div>
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex flex-col items-center justify-center">
                <span class="text-emerald-500 text-xs font-medium">今日の完了</span>
                <span class="text-xl font-bold text-emerald-600 mt-1">{{ todaySummary.completed }}</span>
            </div>
            <div class="bg-white p-4 shadow-[0_4px_12px_rgba(15,23,42,0.06)] rounded-lg border border-slate-200 flex items-center justify-center">
                <div class="relative w-20 h-20 flex items-center justify-center">
                    <Doughnut 
                        :data="chartData" 
                        :options="{ maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }" 
                    />
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-sm font-extrabold text-slate-800 leading-none">{{ todaySummary.percentage }}%</span>
                        <span class="text-[9px] text-slate-400 font-bold mt-0.5">消化</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>