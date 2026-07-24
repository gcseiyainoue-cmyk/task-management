<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout
        auth-text="アカウントをお持ちでないですか？"
        :auth-route="route('register')"
        auth-route-text="新規登録"
    >
        <Head title="ログイン - Tasks" />

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mb-1">おかえりなさい</h1>
            <p class="text-xs text-slate-500">アカウント情報を入力してログインしてください。</p>
        </div>

        <div v-if="status" class="mb-6 text-xs font-medium text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-200">
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
                    autocomplete="username"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="name@example.com"
                />
                <div v-if="form.errors.email" class="text-rose-600 text-[11px] mt-1">{{ form.errors.email }}</div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-semibold text-slate-700">パスワード</label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-[11px] font-semibold text-slate-500 hover:text-slate-900 transition"
                    >
                        パスワードをお忘れですか？
                    </Link>
                </div>
                <input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 transition bg-slate-50/50"
                    placeholder="••••••••"
                />
                <div v-if="form.errors.password" class="text-rose-600 text-[11px] mt-1">{{ form.errors.password }}</div>
            </div>

            <div class="flex items-center pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        v-model="form.remember"
                        class="rounded border-slate-300 text-slate-900 focus:ring-slate-900/20 w-4 h-4"
                    />
                    <span class="text-xs text-slate-600 select-none">ログイン状態を記憶する</span>
                </label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full mt-2 py-3 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm active:scale-95 disabled:opacity-50"
            >
                ログイン
            </button>
        </form>
    </GuestLayout>
</template>