<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 TaskActionModal.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / アクションモーダル・ダイアログ）
 * =====================================================================================
 * 【実務における設計思想】
 * 個別タスクおよび一括操作（bulk）の両方に対応したモーダルコンポーネントです。
 * 「カテゴリ変更」「重要度変更」「期限日変更」の各アクションタイプを動的に切り替え、
 * タッチ操作に最適化したレスポンシブなレイアウト（スマホではボトムシート風、PCでは中央モーダル）を提供します。
 * 期限日変更においては、ローカルステート（tempDate）とウォッチャーを活用して初期値を安全に同期し、
 * 「Data Down, Actions Up」の原則に従って確定時に適切なイベント（単体用または一括用）を親へ発火させます。
 */

import { ref, watch } from 'vue';
import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（対象タスクオブジェクト、アクションの種類） ---
const props = defineProps({
    activeMenuTask: Object,
    activeMenuType: String,
});

// --- イベント定義（モーダル閉鎖および各種単体・一括更新処理の通知） ---
const emit = defineEmits([
    'close', 'update-category', 'update-priority', 
    'update-due', 'bulk-update-due', 'bulk-update-category', 'bulk-update-priority'
]);

// --- ローカルステート（期限日入力用の一時保持値） ---
const tempDate = ref('');

// --- ウォッチャー：モーダルオープン時やターゲットタスク変更時に期限日の初期値をセット ---
watch(() => props.activeMenuTask, (newTask) => {
    if (newTask && newTask.id !== 'bulk' && newTask.due_date) {
        tempDate.value = newTask.due_date;
    } else {
        tempDate.value = new Date().toISOString().split('T')[0];
    }
}, { immediate: true });

// --- 期限変更の確定処理（単体または一括の判定を行ってイベントを発火） ---
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
    <!-- 【トランジション・背景オーバーレイ】最前面（z-[100]）に配置し、背景クリックでモーダルを閉じる -->
    <Transition name="slide-up">
        <div 
            v-if="activeMenuTask" 
            @click="$emit('close')" 
            class="fixed inset-0 z-[100] bg-slate-950/60 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4"
        >
            <!-- モーダル本体（スマホでは下部スライドイン、PCでは中央配置） -->
            <div 
                @click.stop 
                class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-5 pb-8 sm:pb-5 shadow-2xl space-y-4 border-t sm:border border-slate-200 max-h-[85vh] flex flex-col z-[101]"
            >
                <!-- スマホ用ドラッグインジケーター（上部バー） -->
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto sm:hidden shrink-0"></div>

                <!-- ─── 1. カテゴリ変更ビュー ─── -->
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

                <!-- ─── 2. 重要度（優先度）変更ビュー ─── -->
                <template v-else-if="activeMenuType === 'priority' || activeMenuType === 'bulkPriority'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>⚡</span> {{ activeMenuTask.id === 'bulk' ? '一括重要度変更' : '重要度を変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <div class="space-y-2 pb-2">
                        <!-- 高優先度ボタン -->
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'high') : $emit('update-priority', activeMenuTask, 'high')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100 cursor-pointer active:scale-98"
                        >
                            ⚡ 高
                        </button>
                        <!-- 中優先度ボタン -->
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'medium') : $emit('update-priority', activeMenuTask, 'medium')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100 cursor-pointer active:scale-98"
                        >
                            ⚡ 中
                        </button>
                        <!-- 低優先度ボタン -->
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'low') : $emit('update-priority', activeMenuTask, 'low')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 cursor-pointer active:scale-98"
                        >
                            ⚡ 低
                        </button>
                    </div>
                </template>

                <!-- ─── 3. 期限日変更ビュー（手入力 ＆ カレンダー両対応） ─── -->
                <template v-else-if="activeMenuType === 'due' || activeMenuType === 'bulkDue'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📅</span> {{ activeMenuTask.id === 'bulk' ? '一括期限日変更' : '期限日を変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <div class="space-y-3 pb-2">
                        <!-- 期限日入力フィールド -->
                        <input 
                            type="date" 
                            v-model="tempDate"
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 shadow-inner focus:outline-none focus:ring-2 focus:ring-slate-900 transition"
                        />
                        <!-- アクションボタン群（キャンセル / 変更する） -->
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