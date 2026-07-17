<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    task: Object,
});

const form = useForm({
    title: props.task.title,
    description: props.task.description || '',
    due_date: props.task.due_date || '',
    status: props.task.status,
    category: props.task.category || '未分類', // 追加
});

const submit = () => {
    form.patch(route('tasks.update', props.task.id));
};
</script>

<template>
    <Head title="タスク編集" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">タスクの編集</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">タイトル</label>
                            <input v-model="form.title" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                            <select v-model="form.category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="未分類">未分類</option>
                                <option value="仕事">仕事</option>
                                <option value="プライベート">プライベート</option>
                                <option value="勉強">勉強</option>
                            </select>
                            <div v-if="form.errors.category" class="text-red-500 text-sm mt-1">{{ form.errors.category }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">ステータス</label>
                            <select v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option :value="0">未着手</option>
                                <option :value="1">進行中</option>
                                <option :value="2">完了</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">期限</label>
                            <input v-model="form.due_date" type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div v-if="form.errors.due_date" class="text-red-500 text-sm mt-1">{{ form.errors.due_date }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">詳細</label>
                            <textarea v-model="form.description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="flex items-center gap-4">
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition"
                                :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                            >
                                {{ form.processing ? '更新中...' : '更新する' }}
                            </button>
                            <a :href="route('dashboard')" class="text-gray-600 hover:underline">キャンセル</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>