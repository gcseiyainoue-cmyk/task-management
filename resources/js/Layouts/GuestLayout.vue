<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 GuestLayout.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーション / ゲスト認証用共通レイアウト）
 * =====================================================================================
 * 【実務における設計思想】
 * ログイン画面やユーザー登録画面などのゲスト向けページで共通利用されるレイアウトコンポーネントです。
 * ヘッダー、中央配置のカード型メインコンテンツコンテナ、およびフッターを統合し、
 * 認証導線（ログイン/登録の切り替えリンク等）を動的に制御するプロパティを提供します。
 */
import { Link } from '@inertiajs/vue3';

/**
 * ─── プロパティの定義（認証画面のヘッダーに表示する案内文や導線リンク情報） ───
 */
defineProps({
    /**
     * ヘッダー部に表示する案内テキスト（例: "アカウントをお持ちでない場合は"）
     */
    authText: {
        type: String,
        default: '',
    },
    /**
     * ヘッダー内の導線リンク先となるルート名またはURL（例: route('register')）
     */
    authRoute: {
        type: String,
        default: '',
    },
    /**
     * 導線リンクのアンカーテキスト（例: "新規登録"）
     */
    authRouteText: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased selection:bg-slate-900 selection:text-white flex flex-col justify-between">
        <!-- ヘッダー -->
        <header class="sticky top-0 z-40 bg-white/85 backdrop-blur-md border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <Link href="/" class="flex items-center gap-2">
                    <span class="text-xl">🧩</span>
                    <span class="font-bold text-base text-slate-900 tracking-tight">Tasks</span>
                </Link>
                <div v-if="authRoute" class="text-xs text-slate-500">
                    {{ authText }}
                    <Link :href="authRoute" class="font-semibold text-slate-900 hover:underline ml-1">
                        {{ authRouteText }}
                    </Link>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="max-w-md mx-auto px-4 py-12 w-full flex-1 flex flex-col justify-center">
            <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50">
                <slot />
            </div>
        </main>

        <!-- フッター -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-6 text-center text-xs text-slate-400 border-t border-slate-200/60">
            &copy; {{ new Date().getFullYear() }} Tasks. All rights reserved.
        </footer>
    </div>
</template>