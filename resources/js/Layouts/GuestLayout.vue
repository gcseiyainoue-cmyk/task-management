<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    authText: {
        type: String,
        default: '',
    },
    authRoute: {
        type: String,
        default: '',
    },
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