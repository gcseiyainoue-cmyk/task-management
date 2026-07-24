<script setup>
import { ref, watch } from 'vue';
import { categoryTree } from '@/Constants/task';

const props = defineProps({
    activeMenuTask: Object,
    activeMenuType: String,
});

const emit = defineEmits([
    'close', 'update-category', 'update-priority', 
    'update-due', 'bulk-update-due', 'bulk-update-category', 'bulk-update-priority'
]);

// 💡 期限日の入力値を保持するローカルステート
const tempDate = ref('');

// モーダルが開かれたとき（またはタスクが切り替わったとき）に初期値をセット
watch(() => props.activeMenuTask, (newTask) => {
    if (newTask && newTask.id !== 'bulk' && newTask.due_date) {
        tempDate.value = newTask.due_date;
    } else {
        tempDate.value = new Date().toISOString().split('T')[0];
    }
}, { immediate: true });

// 期限変更の確定処理
const submitDue = () => {
    if (!tempDate.value) return;
    if (props.activeMenuType === 'bulkDue') {
        emit('bulk-update-due', tempDate.value);
    } else {
        emit('update-due', props.activeMenuTask, tempDate.value);
    }
    emit('close');
};
</script>

<template>
    <Transition name="slide-up">
        <!-- z-[100] に変更し、一括操作バーやモバイルナビの最前面に配置 -->
        <div 
            v-if="activeMenuTask" 
            @click="$emit('close')" 
            class="fixed inset-0 z-[100] bg-slate-950/60 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4"
        >
            <!-- モーダル本体（pb-8 でスマホ下部の押しやすさを確保） -->
            <div 
                @click.stop 
                class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-5 pb-8 sm:pb-5 shadow-2xl space-y-4 border-t sm:border border-slate-200 max-h-[85vh] flex flex-col z-[101]"
            >
                
                <!-- スマホ用ドラッグバー -->
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto sm:hidden shrink-0"></div>

                <!-- 1. カテゴリ変更 -->
                <template v-if="activeMenuType === 'category' || activeMenuType === 'bulkCategory'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>🏷️</span> {{ activeMenuTask.id === 'bulk' ? '一括カテゴリ変更' : 'カテゴリを変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <div class="flex-1 overflow-y-auto space-y-4 pr-1 pb-2">
                        <div v-for="(pVal, pKey) in categoryTree" :key="pKey" class="space-y-1.5">
                            <div class="text-[11px] font-bold text-slate-400 px-1 flex items-center gap-1.5">
                                <span>{{ pVal.icon }}</span><span>{{ pVal.label }}</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <button 
                                    v-for="sub in pVal.items" 
                                    :key="sub.key" 
                                    @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-category', pKey, sub.key) : $emit('update-category', activeMenuTask, pKey, sub.key)" 
                                    class="p-2.5 text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 rounded-2xl transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 shadow-2xs cursor-pointer"
                                >
                                    <span class="text-base">{{ sub.icon }}</span>
                                    <span class="truncate w-full text-[11px]">{{ sub.label }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- 2. 重要度（優先度）変更 -->
                <template v-else-if="activeMenuType === 'priority' || activeMenuType === 'bulkPriority'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>⚡</span> {{ activeMenuTask.id === 'bulk' ? '一括重要度変更' : '重要度を変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <div class="space-y-2 pb-2">
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'high') : $emit('update-priority', activeMenuTask, 'high')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100 cursor-pointer active:scale-98"
                        >
                            ⚡ 高
                        </button>
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'medium') : $emit('update-priority', activeMenuTask, 'medium')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100 cursor-pointer active:scale-98"
                        >
                            ⚡ 中
                        </button>
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'low') : $emit('update-priority', activeMenuTask, 'low')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 cursor-pointer active:scale-98"
                        >
                            ⚡ 低
                        </button>
                    </div>
                </template>

                <!-- 3. 期限日変更（手入力 ＆ カレンダー両対応） -->
                <template v-else-if="activeMenuType === 'due' || activeMenuType === 'bulkDue'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📅</span> {{ activeMenuTask.id === 'bulk' ? '一括期限日変更' : '期限日を変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <div class="space-y-3 pb-2">
                        <input 
                            type="date" 
                            v-model="tempDate"
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 shadow-inner focus:outline-none focus:ring-2 focus:ring-slate-900 transition"
                        />
                        <div class="flex gap-2 pt-1">
                            <button @click="$emit('close')" type="button" class="flex-1 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-xs font-bold hover:bg-slate-50 transition cursor-pointer active:scale-98">キャンセル</button>
                            <button @click="submitDue" type="button" class="flex-1 py-3 bg-slate-900 text-white rounded-2xl text-xs font-bold shadow-md hover:bg-slate-800 transition cursor-pointer active:scale-98">変更する</button>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </Transition>
</template>