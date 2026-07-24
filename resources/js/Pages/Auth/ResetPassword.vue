<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout
        auth-text="すでにアカウントをお持ちですか？"
        :auth-route="route('login')"
        auth-route-text="ログイン"
    >
        <Head title="パスワード再設定 - Tasks" />

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mb-1">パスワードの再設定</h1>
            <p class="text-xs text-slate-500">新しいパスワードを入力してください。</p>
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="name@example.com"
                />
                <div v-if="form.errors.email" class="text-rose-600 text-[11px] mt-1">{{ form.errors.email }}</div>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">新しいパスワード</label>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="••••••••"
                />
                <div v-if="form.errors.password" class="text-rose-600 text-[11px] mt-1">{{ form.errors.password }}</div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">新しいパスワード（確認）</label>
                <input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="••••••••"
                />
                <div v-if="form.errors.password_confirmation" class="text-rose-600 text-[11px] mt-1">{{ form.errors.password_confirmation }}</div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full mt-2 py-3 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm active:scale-95 disabled:opacity-50"
            >
                パスワードをリセット
            </button>
        </form>
    </GuestLayout>
</template>