<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 TaskActionModal.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / アクションモーダル・ダイアログ）
 * =====================================================================================
 * 【実務における設計思想】
 * 単体タスクまたは複数タスク（一括処理）に対する操作メニュー（カテゴリ変更、重要度変更、
 * 期限日変更、ルーティン化・設定変更など）をモーダル形式で動的に切り替えて表示します。
 * `activeMenuType` に応じてテンプレートを出し分け、ユーザーのアクションを安全に親コンポーネントへ
 * イベントとして伝播させる設計になっています。
 */

import { ref, watch } from 'vue';
import { categoryTree } from '@/Constants/task';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
const props = defineProps({
    // 操作対象のタスクオブジェクト（コンテキストメニューやアクションモーダルで詳細を表示・編集するために使用）
    activeMenuTask: Object,
    // 表示するメニューやモーダルの種類を示す識別子（表示内容や挙動を切り替えるために使用）
    activeMenuType: String,
});

// 親コンポーネントへ発火する各種イベントの定義
const emit = defineEmits([
    'close',                  // モーダルを閉じる通知
    'update-category',        // 単体タスクのカテゴリ変更通知
    'update-priority',        // 単体タスクの重要度変更通知
    'update-due',             // 単体タスクの期限日変更通知
    'bulk-update-due',        // 複数タスクの一括期限日変更通知
    'bulk-update-category',   // 複数タスクの一括カテゴリ変更通知
    'bulk-update-priority',   // 複数タスクの一括重要度変更通知
    'convert-to-routine',     // ルーティン化通知
    'remove-routine',         // 非ルーティン化（解除）通知
    'toggle-routine-active'   // ルーティンの有効/停止（一時停止・再開）切替用通知
]);

// 期限日変更モーダル用の一時入力保持ステート
const tempDate = ref('');

// 編集対象タスクが変更された際、初期の期限日をセットするウォッチャー
watch(() => props.activeMenuTask, (newTask) => {
    if (newTask && newTask.id !== 'bulk' && newTask.due_date) {
        tempDate.value = newTask.due_date;
    } else {
        tempDate.value = new Date().toISOString().split('T')[0];
    }
}, { immediate: true });

/**
 * 期限日の送信処理（単体または一括更新を判別してイベントを発火）
 */
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
    <!-- モーダルのスライドアップトランジション -->
    <Transition name="slide-up">
        <div 
            v-if="activeMenuTask" 
            @click="$emit('close')" 
            class="fixed inset-0 z-[100] bg-slate-950/60 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4"
        >
            <div 
                @click.stop 
                class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-5 pb-8 sm:pb-5 shadow-2xl space-y-4 border-t sm:border border-slate-200 max-h-[85vh] flex flex-col z-[101]"
            >
                <!-- モバイル用インジケーターバー -->
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

                <!-- ─── 2. 重要度変更ビュー ─── -->
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

                <!-- ─── 3. 期限日変更ビュー ─── -->
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

                <!-- ─── 4. ルーティン管理・設定ビュー（状態に応じた選択肢の出し分け） ─── -->
                <template v-else-if="activeMenuType === 'routine'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>🔄</span> 
                            {{ 
                                !activeMenuTask?.routine_template_id 
                                    ? 'タスクをルーティン化' 
                                    : (activeMenuTask?.routine_template?.is_active === true ? 'ルーティン設定（一時停止）' : 'ルーティン設定（再開・解除）') 
                            }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <div class="space-y-4 pb-2">
                        <!-- パターンA: すでにルーティン化されており、かつ「有効」な場合 -->
                        <template v-if="activeMenuTask?.routine_template_id && activeMenuTask?.routine_template?.is_active === true">
                            <p class="text-xs text-slate-600 leading-relaxed">
                                「<span class="font-bold text-slate-900">{{ activeMenuTask?.title }}</span>」のルーティン設定をどうしますか？
                            </p>
                            <div class="space-y-2 pt-1">
                                <button 
                                    @click="$emit('toggle-routine-active', activeMenuTask); $emit('close');" 
                                    type="button" 
                                    class="w-full py-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl text-xs font-bold hover:bg-amber-100 transition cursor-pointer active:scale-98 flex items-center justify-center gap-2"
                                >
                                    <span>⏸️</span> ルーティンを一時停止する
                                </button>
                                <button 
                                    @click="$emit('remove-routine', activeMenuTask); $emit('close');" 
                                    type="button" 
                                    class="w-full py-3 bg-white border border-rose-200 text-rose-600 rounded-2xl text-xs font-bold hover:bg-rose-50 transition cursor-pointer active:scale-98 flex items-center justify-center gap-2"
                                >
                                    <span>🔓</span> ルーティン設定を解除する（単発タスクにする）
                                </button>
                            </div>
                        </template>

                        <!-- パターンB: すでにルーティン化されているが、「停止中」の場合 -->
                        <template v-else-if="activeMenuTask?.routine_template_id && activeMenuTask?.routine_template?.is_active === false">
                            <p class="text-xs text-slate-600 leading-relaxed">
                                「<span class="font-bold text-slate-900">{{ activeMenuTask?.title }}</span>」は現在ルーティン停止中です。
                            </p>
                            <div class="space-y-2 pt-1">
                                <button 
                                    @click="$emit('toggle-routine-active', activeMenuTask); $emit('close');" 
                                    type="button" 
                                    class="w-full py-3 bg-indigo-600 text-white rounded-2xl text-xs font-bold shadow-md hover:bg-indigo-700 transition cursor-pointer active:scale-98 flex items-center justify-center gap-2"
                                >
                                    <span>▶️</span> ルーティンを再開する
                                </button>
                                <button 
                                    @click="$emit('remove-routine', activeMenuTask); $emit('close');" 
                                    type="button" 
                                    class="w-full py-3 bg-white border border-rose-200 text-rose-600 rounded-2xl text-xs font-bold hover:bg-rose-50 transition cursor-pointer active:scale-98 flex items-center justify-center gap-2"
                                >
                                    <span>🔓</span> ルーティン設定を解除する（単発タスクにする）
                                </button>
                            </div>
                        </template>

                        <!-- パターンC: まだルーティン化されていない場合（新規登録） -->
                        <template v-else>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                「<span class="font-bold text-slate-900">{{ activeMenuTask?.title }}</span>」を毎日のルーティンとして登録しますか？<br>
                                <span class="text-[11px] text-slate-400">※元になったタスクは削除され、ルーティンテンプレートに1本化されます。</span>
                            </p>
                            <div class="flex gap-2 pt-1">
                                <button @click="$emit('close')" type="button" class="flex-1 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl text-xs font-bold hover:bg-slate-50 transition cursor-pointer active:scale-98">キャンセル</button>
                                <button @click="$emit('convert-to-routine', activeMenuTask); $emit('close');" type="button" class="flex-1 py-3 bg-slate-900 text-white rounded-2xl text-xs font-bold shadow-md hover:bg-slate-800 transition cursor-pointer active:scale-98">ルーティン化して1本化する</button>
                            </div>
                        </template>
                    </div>
                </template>

            </div>
        </div>
    </Transition>
</template>