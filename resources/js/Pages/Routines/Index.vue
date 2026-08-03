<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 RoutineIndex.vue
 * 【アーキテクチャ上の位置づけ】 UI層（プレゼンテーション / ルーティン管理画面メインコンポーネント）
 * =====================================================================================
 * 【実務における設計思想】
 * ルーティンタスクの一覧表示、検索フィルタリング、新規作成・編集モーダルの制御、
 * および有効/無効の切り替えや削除などのアクションを管理するコンポーネントです。
 * サイドバーとの連携により、頻度やカテゴリごとの絞り込み表示を実現します。
 */
import { ref, computed } from 'vue';
import { useForm, router, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { categoryTree } from '@/Constants/task';
import RoutineSidebar from '@/Components/Routines/RoutineSidebar.vue';
import TaskFormModal from '@/Components/Tasks/TaskFormModal.vue';

/**
 * ─── プロパティの定義（バックエンドから渡されるルーティン関連データとフィルター条件） ───
 */
const props = defineProps({
    /**
     * 現在の絞り込み条件に一致するルーティンの一覧配列
     */
    routines: {
        type: Array,
        default: () => [],
    },
    /**
     * サイドバーの集計・表示用に全ルーティンを格納した配列
     */
    allRoutines: {
        type: Array,
        default: () => [],
    },
    /**
     * 現在選択されている頻度のフィルター条件（例: 'daily', 'weekly'）
     */
    currentFrequency: {
        type: String,
        default: null,
    },
    /**
     * 現在選択されているカテゴリのフィルター条件
     */
    currentCategory: {
        type: String,
        default: null,
    },
});

/**
 * 検索窓の入力文字列（タイトルによるリアルタイム絞り込み用）
 */
const searchQuery = ref('');

/**
 * ルーティン作成・編集モーダルの開閉状態を管理するリアクティブステート
 */
const isFormModalOpen = ref(false);

/**
 * 編集対象のルーティンオブジェクト（新規作成時は null）
 */
const editingRoutine = ref(null);

/**
 * Inertiaフォームヘルパー（ルーティンデータの登録・更新用フォーム状態）
 */
const form = useForm({
    title: '',
    category: 'work',
    sub_category: '',
    priority: 'medium',
    frequency: 'daily',
    is_active: true,
});

/**
 * 新規作成モーダルを開き、フォームを初期化する関数
 */
const openCreateModal = () => {
    editingRoutine.value = null;
    form.reset();
    isFormModalOpen.value = true;
};

/**
 * 既存ルーティンの編集モーダルを開き、選択されたデータをフォームにバインドする関数
 * @param {Object} routine - 編集対象のルーティンオブジェクト
 */
const openEditModal = (routine) => {
    editingRoutine.value = routine;
    form.title = routine.title;
    form.category = routine.category;
    form.sub_category = routine.sub_category || '';
    form.priority = routine.priority;
    form.frequency = routine.frequency;
    form.is_active = routine.is_active;
    isFormModalOpen.value = true;
};

/**
 * フォームの送信処理（新規作成または更新を判定してリクエストを送信）
 */
const handleFormSubmit = () => {
    if (editingRoutine.value) {
        form.put(route('routines.update', editingRoutine.value.id), {
            onSuccess: () => { isFormModalOpen.value = false; form.reset(); },
        });
    } else {
        form.post(route('routines.store'), {
            onSuccess: () => { isFormModalOpen.value = false; form.reset(); },
        });
    }
};

/**
 * ルーティンの削除確認と削除リクエストの送信処理
 * @param {Object} routine - 削除対象のルーティンオブジェクト
 */
const handleDelete = (routine) => {
    if (confirm(`「${routine.title}」を削除してもよろしいですか？`)) {
        router.delete(route('routines.destroy', routine.id), { preserveScroll: true });
    }
};

/**
 * ルーティンの有効/無効状態を切り替えるパッチリクエストの送信処理
 * @param {Object} routine - 状態を切り替えるルーティンオブジェクト
 */
const handleToggle = (routine) => {
    router.patch(route('routines.toggle', routine.id), {}, { preserveScroll: true });
};

/**
 * カテゴリキーに対応する表示用ラベルとアイコンを取得する関数
 * @param {string} catKey - カテゴリ識別キー
 * @returns {Object} ラベルとアイコンを含むオブジェクト
 */
const getCategoryInfo = (catKey) => {
    return categoryTree[catKey] || { label: catKey, icon: '📁' };
};

/**
 * 検索キーワードに基づいてルーティン一覧を動的にフィルタリングする算出プロパティ
 */
const filteredRoutines = computed(() => {
    let result = props.routines || [];
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(r => r.title.toLowerCase().includes(query));
    }
    return result;
});
</script>

<template>
    <Head title="ルーティン管理" />

    <AuthenticatedLayout>
        <div class="flex h-full min-h-screen bg-slate-50">
            
            <RoutineSidebar 
                :routines="allRoutines"
                :currentFrequency="currentFrequency"
                :currentCategory="currentCategory"
            />

            <main class="flex-1 flex flex-col min-w-0 overflow-y-auto pb-20 md:pb-6">
                <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
                    
                    <div class="bg-white border border-slate-200/85 rounded-3xl p-4 shadow-sm flex items-center justify-between gap-4">
                        <div class="w-full sm:w-80 relative">
                            <input 
                                type="text" 
                                v-model="searchQuery"
                                placeholder="ルーティン名で検索..." 
                                class="w-full bg-slate-50 border border-slate-200 text-xs rounded-2xl pl-9 pr-3 py-2.5 text-slate-900 focus:bg-white transition shadow-inner"
                            />
                            <span class="absolute left-3 top-3 text-slate-400 text-xs">🔍</span>
                        </div>

                        <button 
                            @click="openCreateModal"
                            class="bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-2xl transition cursor-pointer shadow-sm whitespace-nowrap active:scale-95"
                        >
                            + 新規ルーティン
                        </button>
                    </div>

                    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm space-y-4">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-400 px-1">
                            <span>ルーティン一覧 ({{ filteredRoutines.length }})</span>
                        </div>

                        <div v-if="filteredRoutines.length === 0" class="text-center py-20 text-slate-400 text-xs font-medium space-y-1">
                            <p class="text-base">🎉</p>
                            <p>登録されているルーティンはありません</p>
                        </div>

                        <div class="space-y-3">
                            <div 
                                v-for="routine in filteredRoutines" 
                                :key="routine.id"
                                class="flex items-center justify-between p-4 bg-white border border-slate-200/80 rounded-2xl hover:border-slate-300 hover:shadow-sm transition"
                            >
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <input 
                                        type="checkbox"
                                        :checked="routine.is_active"
                                        @change="handleToggle(routine)"
                                        class="w-4 h-4 rounded text-slate-900 focus:ring-slate-900 border-slate-300 cursor-pointer"
                                        title="有効/無効の切り替え"
                                    />
                                    <div class="min-w-0 space-y-1">
                                        <h3 class="text-xs font-bold text-slate-900 truncate">{{ routine.title }}</h3>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-500">
                                            <span class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded-md font-medium text-slate-700 border border-slate-200/60">
                                                <span>{{ getCategoryInfo(routine.category).icon }}</span>
                                                <span>{{ getCategoryInfo(routine.category).label }}</span>
                                            </span>
                                            <span class="text-slate-300">|</span>
                                            <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md font-medium border border-indigo-100">
                                                {{ routine.frequency === 'daily' ? '🔄 毎日' : '📅 毎週' }}
                                            </span>
                                            <span class="text-slate-300">|</span>
                                            <span :class="[
                                                'px-2 py-0.5 rounded-md font-medium border',
                                                routine.priority === 'high' ? 'bg-rose-50 text-rose-700 border-rose-100' :
                                                routine.priority === 'medium' ? 'bg-amber-50 text-amber-700 border-amber-100' :
                                                'bg-slate-50 text-slate-600 border-slate-200'
                                            ]">
                                                優先度: {{ routine.priority === 'high' ? '高' : routine.priority === 'medium' ? '中' : '低' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <button 
                                        @click="openEditModal(routine)"
                                        class="text-xs text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-xl transition cursor-pointer font-medium"
                                    >
                                        編集
                                    </button>
                                    <button 
                                        @click="handleDelete(routine)"
                                        class="text-xs text-rose-600 hover:text-rose-700 bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 px-3 py-1.5 rounded-xl transition cursor-pointer font-medium"
                                    >
                                        削除
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

            <TaskFormModal 
                :isOpen="isFormModalOpen"
                :editingTask="editingRoutine"
                :form="form"
                @close="isFormModalOpen = false"
                @submit="handleFormSubmit"
            />
        </div>
    </AuthenticatedLayout>
</template>