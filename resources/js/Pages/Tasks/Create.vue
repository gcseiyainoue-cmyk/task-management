<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    due_date: '',
    category: '未分類', // 追加
});

const submit = () => {
    form.post(route('tasks.store'));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">タスク新規作成</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="p-6 bg-white shadow rounded-lg space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">タスク名</label>
                        <input v-model="form.title" type="text" class="w-full mt-1 border-gray-300 rounded-md" required />
                        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                        <select v-model="form.category" class="w-full mt-1 border-gray-300 rounded-md">
                            <option value="未分類">未分類</option>
                            <option value="仕事">仕事</option>
                            <option value="プライベート">プライベート</option>
                            <option value="勉強">勉強</option>
                        </select>
                        <div v-if="form.errors.category" class="text-red-500 text-sm mt-1">{{ form.errors.category }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">詳細</label>
                        <textarea v-model="form.description" class="w-full mt-1 border-gray-300 rounded-md"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">期限</label>
                        <input v-model="form.due_date" type="date" class="w-full mt-1 border-gray-300 rounded-md" />
                        <div v-if="form.errors.due_date" class="text-red-500 text-sm mt-1">{{ form.errors.due_date }}</div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md" :disabled="form.processing">作成</button>
                        <Link :href="route('dashboard')" class="text-gray-500">キャンセル</Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>