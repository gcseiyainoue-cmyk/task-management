<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 TaskFormModal.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーションコンポーネント / タスク新規作成・スマート一括入力モーダル）
 * =====================================================================================
 * 【実務における設計思想】
 * 単体タスクを詳細に設定して作成する「通常モード」と、改行区切りで自由なテキストから
 * 短縮記号（[wo], [hi]など）や自然言語の期限を解析して一括生成する「スマート一括入力モード」の
 * 2つのモードを切り替えて利用できるマルチパーパスなモーダルコンポーネントです。
 * 通常モードではInertia.jsのフォームオブジェクト（form）を受け取りデータバインディングとバリデーションエラー表示を行い、
 * スマート一括入力では専用のComposable（useSmartTaskCreate）にロジックを委譲してクリーンな関心の分離を実現しています。
 */

import { ref } from 'vue';
import { categoryTree } from '@/Constants/task';
import { useSmartTaskCreate } from '@/Composables/useSmartTaskCreate';

// --- プロパティの定義（モーダル表示状態、通常作成用 Inertia form オブジェクト） ---
const props = defineProps({
    isOpen: Boolean,
    form: Object, 
});

// --- イベント定義（モーダル閉鎖およびフォーム送信の通知） ---
const emit = defineEmits(['close', 'submit']);

// --- モード管理 ('single': 通常作成, 'smart': スマート一括入力) ---
const mode = ref('single');

// --- スマート一括入力のロジックをComposableから取得 ---
const {
    smartText,
    isProcessing,
    handleSmartSubmit,
} = useSmartTaskCreate(emit);
</script>

<template>
    <!-- 【トランジション・背景オーバーレイ】最前面（z-50）に配置し、背景クリックでモーダルを閉じる -->
    <Transition name="slide-up">
        <div v-if="isOpen" @click="$emit('close')" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4">
            <!-- モーダル本体（スマホでは下部スライドイン、PCでは中央配置） -->
            <div @click.stop class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-5 border-t sm:border border-slate-200 max-h-[90vh] flex flex-col">
                
                <!-- スマホ用ドラッグインジケーター（上部バー） -->
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto sm:hidden shrink-0"></div>

                <!-- ─── ヘッダー ＆ モード切り替えタブ ─── -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                    <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>✨</span> タスクを追加
                    </span>

                    <!-- タブ切替ボタン群 -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button 
                            @click="mode = 'single'" 
                            :class="['px-3 py-1 rounded-lg transition cursor-pointer', mode === 'single' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900']"
                        >
                            通常
                        </button>
                        <button 
                            @click="mode = 'smart'" 
                            :class="['px-3 py-1 rounded-lg transition cursor-pointer', mode === 'smart' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900']"
                        >
                            ✨ スマート一括
                        </button>
                    </div>

                    <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                </div>

                <!-- ─── 通常作成モードフォーム ─── -->
                <form v-if="mode === 'single'" @submit.prevent="$emit('submit')" class="space-y-4 overflow-y-auto pr-1 pb-2">
                    <!-- タスク名入力 -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500">タスク名</label>
                        <input 
                            type="text" 
                            v-model="form.title" 
                            placeholder="例: 週報を作成する" 
                            autofocus
                            class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-slate-900 shadow-inner focus:bg-white transition"
                        />
                        <div v-if="form.errors.title" class="text-rose-600 text-[10px] font-bold pl-1">{{ form.errors.title }}</div>
                    </div>

                    <!-- カテゴリ & サブカテゴリ選択 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">カテゴリ</label>
                            <select 
                                v-model="form.category" 
                                @change="form.sub_category = categoryTree[form.category]?.defaultSub || 'general'"
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            >
                                <option v-for="(val, key) in categoryTree" :key="key" :value="key">
                                    {{ val.icon }} {{ val.label }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">サブカテゴリ</label>
                            <select 
                                v-model="form.sub_category" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            >
                                <option 
                                    v-for="sub in (categoryTree[form.category]?.items || [])" 
                                    :key="sub.key" 
                                    :value="sub.key"
                                >
                                    {{ sub.icon }} {{ sub.label }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- 期限日 & 重要度選択 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">期限日</label>
                            <input 
                                type="date" 
                                v-model="form.due_date" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">重要度</label>
                            <select 
                                v-model="form.priority" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            >
                                <option value="high">⚡ 高</option>
                                <option value="medium">⚡ 中</option>
                                <option value="low">⚡ 低</option>
                            </select>
                        </div>
                    </div>

                    <!-- 送信ボタン -->
                    <div class="pt-2">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow-lg transition active:scale-98 cursor-pointer disabled:opacity-50"
                        >
                            タスクを追加する
                        </button>
                    </div>
                </form>

                <!-- ─── スマート一括入力モード ─── -->
                <div v-else class="space-y-4 overflow-y-auto pr-1 pb-2">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500">今日やることを自由に書き出す</label>
                        <p class="text-[11px] text-slate-400">改行区切りで入力すると、2文字の短縮記号やキーワードからカテゴリ・期限・優先度を自動推論します。</p>
                    </div>

                    <!-- 💡 Tips（書き方のコツ）セクション -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 space-y-2.5 text-[11px] text-slate-600">
                        <div class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span>💡</span> スマート入力のヒント
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-500">
                            <div>
                                <span class="font-bold text-slate-700">⚡ 優先度:</span> 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[hi]</code> 高 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[md]</code> 中 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[lo]</code> 低
                            </div>
                            <div>
                                <span class="font-bold text-slate-700">📁 カテゴリ:</span> 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[wo]</code> 仕事 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[pe]</code> 個人 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[gr]</code> 成長 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[he]</code> 健康 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[fi]</code> お金
                            </div>
                            <div class="sm:col-span-2">
                                <span class="font-bold text-slate-700">📅 期限:</span> 「明日」「今週末」「月末」「月曜」など
                            </div>
                        </div>
                    </div>

                    <!-- テキストエリア -->
                    <textarea 
                        v-model="smartText"
                        rows="6"
                        placeholder="例：
・明日の会議用資料を修正する [wo] [hi]
・週末にスーパーで食材の買い物 [pe] [lo]
・英語の学習を30分行う [gr] 月曜"
                        autofocus
                        class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-900 shadow-inner focus:bg-white transition resize-none leading-relaxed"
                    ></textarea>

                    <!-- 一括生成ボタン -->
                    <div class="pt-2">
                        <button 
                            @click="handleSmartSubmit"
                            :disabled="isProcessing || !smartText.trim()"
                            class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow-lg transition active:scale-98 cursor-pointer disabled:opacity-50"
                        >
                            {{ isProcessing ? '解析・生成中...' : 'スマート一括生成する' }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </Transition>
</template>