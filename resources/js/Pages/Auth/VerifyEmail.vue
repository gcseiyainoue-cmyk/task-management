<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout
        :auth-route="route('logout')"
        auth-route-text="ログアウト"
    >
        <Head title="メールアドレス確認 - Tasks" />

        <div class="mb-6 text-center">
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mb-2">メールアドレスの確認</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                ご登録ありがとうございます！始める前に、お送りした確認リンクをクリックしてメールアドレスの認証を行ってください。メールが届いていない場合は、再送信ボタンより再度お送りいたします。
            </p>
        </div>

        <div
            class="mb-6 text-xs font-medium text-emerald-600 bg-emerald-50 p-3 rounded-xl border border-emerald-200 text-center"
            v-if="verificationLinkSent"
        >
            ご登録いただいたメールアドレスに、新しい確認リンクを送信しました。
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-3 rounded-xl text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition shadow-sm active:scale-95 disabled:opacity-50"
            >
                確認メールを再送信する
            </button>
        </form>
    </GuestLayout>
</template>