<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout
        :auth-route="route('logout')"
        auth-route-text="ログアウト"
    >
        <Head title="パスワード確認 - Tasks" />

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mb-2">パスワードの確認</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                こちらはセキュリティ保護された領域です。続行する前にパスワードを確認してください。
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">パスワード</label>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autofocus
                    autocomplete="current-password"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="••••••••"
                />
                <div v-if="form.errors.password" class="text-rose-600 text-[11px] mt-1">{{ form.errors.password }}</div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full mt-2 py-3 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm active:scale-95 disabled:opacity-50"
            >
                確認する
            </button>
        </form>
    </GuestLayout>
</template>