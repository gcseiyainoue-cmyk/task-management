<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    task: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    title: '',
    description: '', // 追加
    due_date: '',
    status: 0,
    category: '未分類',
});

// モーダルが開かれた時にタスクデータをフォームにセットする
watch(() => props.task, (newTask) => {
    if (newTask) {
        form.title = newTask.title;
        form.description = newTask.description || '';
        form.due_date = newTask.due_date || '';
        form.status = newTask.status;
        form.category = newTask.category || '未分類';
        form.clearErrors();
    }
}, { immediate: true });

const submit = () => {
    form.patch(route('tasks.update', props.task.id), {
        onSuccess: () => emit('close'),
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <h2 class="text-lg font-semibold mb-4">タスクを編集</h2>
            <form @submit.prevent="submit" class="space-y-4">
                <!-- タイトル -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">タイトル</label>
                    <input v-model="form.title" type="text" class="mt-1 block w-full rounded-md border-gray-300">
                    <div v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</div>
                </div>

                <!-- 内容（追加） -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">内容</label>
                    <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                    <div v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- カテゴリ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">カテゴリ</label>
                        <select v-model="form.category" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="未分類">未分類</option>
                            <option value="仕事">仕事</option>
                            <option value="プライベート">プライベート</option>
                            <option value="勉強">勉強</option>
                        </select>
                        <div v-if="form.errors.category" class="text-sm text-red-600 mt-1">{{ form.errors.category }}</div>
                    </div>
                    <!-- ステータス -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ステータス</label>
                        <select v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300">
                            <option :value="0">未着手</option>
                            <option :value="1">進行中</option>
                            <option :value="2">完了</option>
                        </select>
                        <div v-if="form.errors.status" class="text-sm text-red-600 mt-1">{{ form.errors.status }}</div>
                    </div>
                </div>

                <!-- 期限 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">期限</label>
                    <input v-model="form.due_date" type="date" class="mt-1 block w-full rounded-md border-gray-300">
                    <div v-if="form.errors.due_date" class="text-sm text-red-600 mt-1">{{ form.errors.due_date }}</div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="emit('close')" class="px-4 py-2 text-gray-600 hover:text-gray-800">キャンセル</button>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-slate-800 text-white rounded-md hover:bg-slate-700">更新する</button>
                </div>
            </form>
        </div>
    </div>
</template>