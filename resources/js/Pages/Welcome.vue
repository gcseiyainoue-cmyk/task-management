<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 Welcome.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーション / ウェルカム・トップページ画面）
 * =====================================================================================
 * 【実務における設計思想】
 * 未認証ユーザー向けのランディングページ（LP）として機能するコンポーネントです。
 * サービスの主要な特徴（サクサク動く操作感、プライバシー、デザイン）やコンセプトを紹介し、
 * ログイン画面や新規登録画面へのシームレスな導線を提供します。
 */
import { Head, Link } from '@inertiajs/vue3';

/**
 * ─── プロパティの定義（バックエンドから渡される認証機能の有効/無効フラグ） ───
 */
defineProps({
    /**
     * ログイン機能が有効かどうかを示すフラグ
     */
    canLogin: {
        type: Boolean,
        default: false,
    },
    /**
     * ユーザー登録機能が有効かどうかを示すフラグ
     */
    canRegister: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <Head title="" />

    <div class="min-h-screen bg-slate-50/50 text-slate-900 font-sans antialiased selection:bg-slate-900 selection:text-white flex flex-col justify-between">
        <!-- ヘッダー -->
        <header class="sticky top-0 z-40 bg-white/85 backdrop-blur-md border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-xl">🧩</span>
                    <span class="font-bold text-base text-slate-900 tracking-tight">Tasks</span>
                </div>

                <nav v-if="canLogin" class="flex items-center gap-2">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white shadow-2xs hover:bg-slate-800 transition"
                    >
                        ダッシュボードへ
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100/80 transition"
                        >
                            ログイン
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-900 text-white shadow-2xs hover:bg-slate-800 transition"
                        >
                            新規登録
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex-1 flex flex-col justify-center">
            <!-- ヒーローセクション -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-slate-900"></span>
                    シンプルで使いやすいタスク管理
                </div>
                
                <h1 class="text-4xl sm:text-6xl font-bold text-slate-900 tracking-tight leading-tight mb-6">
                    毎日のタスク管理を、<br />
                    <span class="text-slate-400">もっとシンプルに、心地よく。</span>
                </h1>
                
                <p class="text-sm sm:text-base text-slate-600 max-w-xl mx-auto mb-10 leading-relaxed">
                    日々のTODOやスケジュールをすっきり整理。<br>
                    見やすさとスムーズな操作性で、あなたの毎日のタスク管理をサポートします。
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm active:scale-95"
                    >
                        無料でアカウントを作成
                    </Link>
                    <Link
                        v-if="canLogin && !$page.props.auth.user"
                        :href="route('login')"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition shadow-2xs active:scale-95"
                    >
                        ログインして続ける
                    </Link>
                </div>
            </div>

            <!-- 特徴グリッド -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8 border-t border-slate-200/60">
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg mb-4">⚡</div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">サクサク動く操作感</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        複数選択による一括変更や、期限・カテゴリの切り替えがスムーズに行えます。
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg mb-4">🔒</div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">安心のプライバシー</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        マルチユーザー環境でもデータは完全に分離されるため、安心して自分のタスクを管理できます。
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-lg mb-4">✨</div>
                    <h3 class="text-sm font-bold text-slate-900 mb-2">見やすいデザイン</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        無駄を省いたクリーンな画面設計で、情報の優先度がひと目で分かります。
                    </p>
                </div>
            </div>
        </main>

        <!-- フッター -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-xs text-slate-400 border-t border-slate-200/60">
            &copy; {{ new Date().getFullYear() }} Tasks. All rights reserved.
        </footer>
    </div>
</template>