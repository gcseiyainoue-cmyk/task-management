<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="使い方ガイド" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span>📖</span> タスク管理の使い方ガイド
                </h2>
                <Link 
                    :href="route('dashboard')" 
                    class="text-xs bg-slate-900 text-white px-3.5 py-2.5 rounded-xl font-bold hover:bg-slate-800 transition shadow-md active:scale-95"
                >
                    ダッシュボードへ戻る
                </Link>
            </div>
        </template>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20 space-y-6">
            
            <!-- イントロカード -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
                <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-white/10 px-3 py-1 rounded-full text-amber-300">
                    <span>✨</span> 思考を解放するシンプルなタスク管理
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                    頭の中のモヤモヤを形にして、<br>今日やるべきことに100%集中しよう。
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    タスクの追加から絞り込み、カードの確認、一括操作までの流れを4つのステップでスムーズにマスターしましょう。
                </p>
            </div>

            <!-- ステップフロー（縦並び） -->
            <div class="space-y-6">
                
                <!-- STEP 01 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-white font-mono bg-slate-900 px-3 py-1.5 rounded-xl shadow-xs">STEP 01</span>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">「新規タスク」から通常 or スマート一括追加</h3>
                        </div>
                        <span class="text-2xl">📥</span>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        ヘッダーの「+ 新規タスク」ボタンをクリックするとモーダルが起動します。通常作成タブでは、タスク名だけでなく、カテゴリ・サブカテゴリ・期限日・重要度を詳細に設定して追加できます。
                    </p>

                    <!-- Step 1 プレビュー -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6 space-y-3">
                        <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                            <span>🖥️</span> 新規作成モーダルのプレビュー
                        </div>
                        <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-xl space-y-4 max-w-lg mx-auto">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                    <span>✨</span> タスクを追加
                                </span>
                                <div class="flex items-center bg-slate-100 p-1 rounded-xl text-[11px] font-bold">
                                    <span class="bg-white text-slate-900 px-3 py-1 rounded-lg shadow-2xs">通常</span>
                                    <span class="text-slate-500 px-3 py-1">✨ スマート一括</span>
                                </div>
                                <span class="text-slate-400 font-bold text-xs">✕</span>
                            </div>

                            <div class="space-y-3 text-xs">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-500">タスク名</label>
                                    <div class="bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-2.5 text-slate-400">例: 週報を作成する</div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500">カテゴリ</label>
                                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-slate-700 flex items-center justify-between">
                                            <span>📁 未分類</span>
                                            <span class="text-[10px] text-slate-400">▼</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500">サブカテゴリ</label>
                                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-slate-700 flex items-center justify-between">
                                            <span>📝 その他</span>
                                            <span class="text-[10px] text-slate-400">▼</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500">期限日</label>
                                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-slate-700 flex items-center justify-between">
                                            <span>2026/07/23</span>
                                            <span class="text-[10px] text-slate-400">📅</span>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[10px] font-bold text-slate-500">重要度</label>
                                        <div class="bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-slate-700 flex items-center justify-between">
                                            <span>⚡ 中</span>
                                            <span class="text-[10px] text-slate-400">▼</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-1">
                                    <div class="bg-slate-900 text-white font-bold py-3 rounded-2xl text-center text-xs shadow-md">
                                        タスクを追加する
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 矢印コネクター -->
                <div class="flex justify-center">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold shadow-xs">
                        ↓
                    </div>
                </div>

                <!-- STEP 02 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-white font-mono bg-slate-900 px-3 py-1.5 rounded-xl shadow-xs">STEP 02</span>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">コントロールバーで検索・ソート・絞り込み</h3>
                        </div>
                        <span class="text-2xl">🏷️</span>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        ダッシュボード上部のコントロールバーでは、フリーワード検索をはじめ、「すべてのカテゴリ」での絞り込み、期日順ソート、昇順・降順の切り替え、一括選択モードの起動が可能です。
                    </p>

                    <!-- Step 2 プレビュー -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6 space-y-3">
                        <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                            <span>🖥️</span> コントロールバーのプレビュー
                        </div>
                        <div class="bg-white rounded-2xl p-3 sm:p-4 border border-slate-200 shadow-2xs flex flex-wrap items-center gap-2 text-xs">
                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-[11px] text-slate-400 flex items-center gap-2 flex-1 min-w-[160px]">
                                <span>🔍</span> <span class="truncate">タスク・期日・カテゴリで検索...</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 text-[11px] flex items-center gap-1">
                                <span>📁 すべてのカテゴリ</span> <span class="text-slate-400 text-[10px]">▼</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 text-[11px] flex items-center gap-1">
                                <span>期日順</span> <span class="text-slate-400 text-[10px]">▼</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-bold text-[11px] flex items-center gap-1">
                                <span>昇順</span> <span class="text-indigo-600">⬆️</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-bold text-[11px]">
                                一括選択
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 矢印コネクター -->
                <div class="flex justify-center">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold shadow-xs">
                        ↓
                    </div>
                </div>

                <!-- STEP 03 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-white font-mono bg-slate-900 px-3 py-1.5 rounded-xl shadow-xs">STEP 03</span>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">タスクカードの各情報チェック</h3>
                        </div>
                        <span class="text-2xl">⚡</span>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        各カードは左端に「タスク完了」ボタンと、上部にタイトル、その下にカテゴリバッジを配置。右側には「優先度」「期限日」「作成日時」が美しく整列しています。
                    </p>

                    <!-- Step 3 プレビュー（最新の TaskCard.vue 構造を完全同期） -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6 space-y-3">
                        <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                            <span>🖥️</span> タスクカードのプレビュー
                        </div>
                        <div class="bg-white rounded-2xl p-3.5 sm:p-4.5 border border-slate-200/80 shadow-sm flex flex-col gap-3 sm:gap-0 sm:flex-row sm:items-center sm:justify-between overflow-hidden">
                            
                            <!-- メインコンテンツ（左側：完了ボタン＋タイトル＋カテゴリ） -->
                            <div class="flex items-center gap-3 min-w-0 flex-1 w-full">
                                <!-- 完了ボタン -->
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-[11px] font-bold border transition-all duration-300 shrink-0 whitespace-nowrap bg-slate-100 border-slate-300 text-slate-700 shadow-2xs">
                                    <span class="text-slate-400">□</span>
                                    <span>未完了</span>
                                </div>

                                <!-- タイトル・カテゴリ設定 -->
                                <div class="min-w-0 flex-1 space-y-1">
                                    <!-- タイトル -->
                                    <div class="text-xs sm:text-sm font-bold leading-normal tracking-tight text-slate-900 py-0.5 break-words">
                                        資料をまとめる
                                    </div>
                                    <!-- カテゴリバッジ -->
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <div class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 shadow-2xs whitespace-nowrap shrink-0">
                                            <span>📥</span>
                                            <span class="text-slate-700">未分類</span>
                                            <span class="text-slate-300 font-light">/</span>
                                            <span>📝</span>
                                            <span class="text-slate-600">その他</span>
                                            <span class="text-[9px] ml-0.5 text-slate-400 opacity-70">▼</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 右側：アクションボタン・メタデータ -->
                            <div class="flex items-center justify-between sm:justify-end gap-1.5 pt-2 sm:pt-0 border-t border-slate-100 sm:border-t-0 shrink-0 w-full sm:w-auto">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <!-- 優先度 -->
                                    <div class="px-2.5 py-1 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 shadow-2xs text-[11px] font-bold flex items-center gap-1 whitespace-nowrap shrink-0">
                                        <span>⚡</span>
                                        <span>高</span>
                                        <span class="text-[9px] opacity-60">▼</span>
                                    </div>
                                    <!-- 期日 -->
                                    <div class="border border-rose-200 bg-rose-50 text-rose-700 rounded-xl px-2.5 py-1 shadow-2xs text-[11px] font-bold flex items-center gap-1 whitespace-nowrap shrink-0">
                                        <span>📅</span>
                                        <span>2026-07-23</span>
                                        <span class="text-[9px] opacity-60">▼</span>
                                    </div>
                                    <!-- 作成日時 -->
                                    <div class="px-2 py-1 rounded-xl bg-slate-100/60 text-slate-400 text-[10px] font-medium flex items-center gap-1 shrink-0 whitespace-nowrap select-none">
                                        <span>🕒</span>
                                        <span>作成: 07/23 11:34</span>
                                    </div>
                                </div>
                                <!-- 削除ボタン -->
                                <div class="text-slate-300 p-1.5 rounded-xl shrink-0 ml-auto sm:ml-0 text-xs">
                                    ✕
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 矢印コネクター -->
                <div class="flex justify-center">
                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 font-bold shadow-xs">
                        ↓
                    </div>
                </div>

                <!-- STEP 04 -->
                <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-white font-mono bg-slate-900 px-3 py-1.5 rounded-xl shadow-xs">STEP 04</span>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">一括選択モードとボトムアクションバー</h3>
                        </div>
                        <span class="text-2xl">📂</span>
                    </div>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        コントロールバーの「一括選択」を有効にすると、タスクにチェックボックスが出現。画面下部には、選択件数の表示や一括操作（完了・未完了・期限・カテゴリ・優先度・削除）が行える黒い専用バーが出現します。
                    </p>

                    <!-- Step 4 プレビュー -->
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 sm:p-6 space-y-3">
                        <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                            <span>🖥️</span> ボトムアクションバーのプレビュー
                        </div>
                        <div class="bg-slate-900/95 backdrop-blur-md text-white text-xs p-3.5 rounded-2xl shadow-xl flex items-center gap-2 sm:gap-3 border border-slate-700 flex-wrap justify-center">
                            <span class="font-bold text-amber-400 px-1">24件選択中</span>
                            <div class="h-4 w-px bg-slate-700 hidden sm:block"></div>
                            
                            <div class="bg-slate-800 px-3.5 py-2 rounded-xl font-bold flex items-center gap-1 shadow-sm text-xs">
                                ✅ 完了
                            </div>
                            <div class="bg-slate-800 px-3.5 py-2 rounded-xl font-bold flex items-center gap-1 shadow-sm text-xs">
                                ⏳ 未完了
                            </div>
                            <div class="bg-slate-800 px-3.5 py-2 rounded-xl font-bold flex items-center gap-1 shadow-sm text-xs">
                                <span>📅</span> 期限
                            </div>
                            <div class="bg-slate-800 px-3.5 py-2 rounded-xl font-bold flex items-center gap-1 shadow-sm text-xs">
                                <span>🏷️</span> カテゴリ
                            </div>
                            <div class="bg-slate-800 px-3.5 py-2 rounded-xl font-bold flex items-center gap-1 shadow-sm text-xs">
                                <span>⚡</span> 優先度
                            </div>
                            <div class="bg-rose-950/80 text-rose-200 px-3.5 py-2 rounded-xl font-bold flex items-center gap-1 shadow-sm text-xs">
                                🗑️ 削除
                            </div>
                            <div class="text-slate-400 hover:text-white px-2 py-2 ml-1 cursor-pointer text-xs">
                                ✕
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- フッターアクション -->
            <div class="text-center pt-6">
                <Link 
                    :href="route('dashboard')" 
                    class="inline-flex items-center gap-2 bg-slate-900 text-white font-bold text-xs sm:text-sm px-8 py-4 rounded-2xl hover:bg-slate-800 transition shadow-lg active:scale-95"
                >
                    <span>🚀</span> さあ、タスク管理をはじめよう！
                </Link>
            </div>

        </div>
    </AuthenticatedLayout>
</template>