<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 AuthenticatedLayout.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーション / アプリケーション共通レイアウト）
 * =====================================================================================
 * 【実務における設計思想】
 * アプリケーション全体の共通レイアウトを定義するコンポーネントです。
 * ヘッダー、ナビゲーションバー（PC/スマホ対応）、ユーザー情報、ログアウト機能などを集約し、
 * 各ページ（ダッシュボード、ルーティン管理、ガイド等）の土台となる共通フレームを提供します。
 */
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// スマホ用ナビゲーションドロップダウンの開閉状態を管理するリアクティブステート
const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50/50 text-slate-900 font-sans antialiased selection:bg-slate-900 selection:text-white">
        
        <!-- ▼ ナビゲーションとページヘッダーをひとまとめにして固定するラッパー -->
        <div class="sticky top-0 z-40">
            <!-- ナビゲーションバー -->
            <nav class="bg-white/85 backdrop-blur-md border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center gap-6">
                            <!-- ロゴ / アプリ名（ダッシュボード画面へのリンク） -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="font-bold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                    <span class="text-xl">🧩</span> 
                                    <span>Tasks</span>
                                </Link>
                            </div>

                            <!-- ナビゲーションリンク（PC用） -->
                            <div class="hidden sm:flex sm:items-center sm:gap-1.5">
                                <!-- ダッシュボードへのリンク -->
                                <Link 
                                    :href="route('dashboard')" 
                                    :class="[
                                        'px-3.5 py-2 rounded-xl text-xs font-semibold transition',
                                        (route().current('dashboard') && !route().params?.view) ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'
                                    ]"
                                >
                                    ダッシュボード
                                </Link>

                                <!-- 🔄 ルーティン管理画面へのリンク（ダッシュボード統合ビュー） -->
                                <Link 
                                    :href="route('dashboard', { view: 'routines' })" 
                                    :class="[
                                        'px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5',
                                        (route().current('dashboard') && route().params?.view === 'routines') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'
                                    ]"
                                >
                                    <span>🔄</span> ルーティン管理
                                </Link>

                                <!-- 使い方ガイドへのリンク -->
                                <Link 
                                    :href="route('tasks.guide')" 
                                    :class="[
                                        'px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5',
                                        route().current('tasks.guide') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'
                                    ]"
                                >
                                    <span>📖</span> 使い方ガイド
                                </Link>
                                
                                <!-- コード・UIガイドへのリンク -->
                                <Link 
                                    :href="route('dashboard.code-guide')" 
                                    :class="[
                                        'px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5',
                                        route().current('dashboard.code-guide') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'
                                    ]"
                                >
                                    <span>🗺️</span> コード・UIガイド
                                </Link>
                            </div>
                        </div>

                        <!-- ユーザー情報・ログアウト（PC用） -->
                        <div class="hidden sm:flex sm:items-center sm:gap-3">
                            <div class="text-xs font-semibold text-slate-700 bg-slate-100/80 px-3 py-1.5 rounded-xl border border-slate-200/60">
                                {{ $page.props.auth.user.name }} さん
                            </div>
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button" 
                                class="text-xs font-semibold text-slate-600 hover:text-rose-600 px-3.5 py-2 rounded-xl hover:bg-rose-50 transition"
                            >
                                ログアウト
                            </Link>
                        </div>

                        <!-- ハンバーガーメニューボタン（スマホ用） -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button 
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- スマホ用ドロップダウンメニュー -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden border-t border-slate-200/80 bg-white px-4 pt-3 pb-4 space-y-2">
                    <Link 
                        :href="route('dashboard')" 
                        class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition"
                    >
                        ダッシュボード
                    </Link>
                    <!-- 🔄 スマホ用ルーティン管理リンク -->
                    <Link 
                        :href="route('dashboard', { view: 'routines' })" 
                        class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition flex items-center gap-2"
                    >
                        <span>🔄</span> ルーティン管理
                    </Link>
                    <Link 
                        :href="route('tasks.guide')" 
                        class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition flex items-center gap-2"
                    >
                        <span>📖</span> 使い方ガイド
                    </Link>
                    <Link 
                        :href="route('dashboard.code-guide')" 
                        class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition flex items-center gap-2"
                    >
                        <span>🗺️</span> コード・UIガイドを見る
                    </Link>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between px-3">
                        <span class="text-xs font-semibold text-slate-700">{{ $page.props.auth.user.name }} さん</span>
                        <Link 
                            :href="route('logout')" 
                            method="post" 
                            as="button" 
                            class="text-xs font-semibold text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-xl transition"
                        >
                            ログアウト
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- ページヘッダー（各ページ個別のヘッダーコンテンツを挿入するスロット） -->
            <header v-if="$slots.header" class="bg-white/85 backdrop-blur-md border-b border-slate-200/60 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
        </div>
        <!-- ▲ 固定ラッパーここまで -->

        <!-- メインコンテンツ（各ページの固有コンポーネントが描画されるスロット） -->
        <main>
            <slot />
        </main>
    </div>
</template>