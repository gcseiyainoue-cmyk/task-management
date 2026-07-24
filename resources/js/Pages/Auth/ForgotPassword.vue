<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout
        auth-text="思い出しましたか？"
        :auth-route="route('login')"
        auth-route-text="ログイン"
    >
        <Head title="パスワード再設定 - Tasks" />

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mb-2">パスワードをお忘れですか？</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                ご登録のメールアドレスを入力していただければ、新しいパスワードを設定するためのリンクをお送りします。
            </p>
        </div>

        <div
            v-if="status"
            class="mb-6 text-xs font-medium text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-200 text-center"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="name@example.com"
                />
                <div v-if="form.errors.email" class="text-rose-600 text-[11px] mt-1">{{ form.errors.email }}</div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full mt-2 py-3 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm active:scale-95 disabled:opacity-50"
            >
                パスワード再設定リンクを送信
            </button>
        </form>
    </GuestLayout>
</template>