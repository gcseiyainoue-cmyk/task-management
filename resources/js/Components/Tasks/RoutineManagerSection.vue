<script setup>
/**
 * =====================================================================================
 * 【ファイル名】 RoutineManagerSection.vue
 * 【アーキテクチャ上の位置づけ】 フロントエンド層（Vue 3 SFC / ルーティン管理セクション・モーダル状態管理）
 * =====================================================================================
 * 【実務における設計思想】
 * ルーティンテンプレートの一覧表示、検索・カテゴリフィルタリング、および新規作成・編集モーダルの
 * 開閉状態やフォーム入力を一元管理するコンポーネントです。
 * Inertia.jsの `useForm` を活用してバリデーションエラーやフォームデータを効率的に扱い、
 * 親コンポーネントへイベント（store, update, toggle, delete）を安全に発火させます。
 */

import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';
import RoutineControlBar from '@/Components/Tasks/RoutineControlBar.vue';
import RoutineListSection from '@/Components/Tasks/RoutineListSection.vue';

// --- プロパティの定義（親コンポーネントから受け取るデータ群） ---
const props = defineProps({
    // 登録されているルーティンテンプレートの配列（ルーティン一覧の表示や項目ごとの描画に使用）
    routineTemplates: {
        type: Array,
        default: () => []
    },
});

// 親コンポーネントへ発火する各種イベントの定義
const emit = defineEmits([
    'store',  // 新規ルーティンテンプレートの保存処理を親へ通知
    'update', // 既存ルーティンテンプレートの更新処理を親へ通知
    'toggle', // ルーティンテンプレートの有効/停止状態の切り替えを親へ通知
    'delete'  // ルーティンテンプレートの削除処理を親へ通知
]);

// 状態管理（検索クエリ、カテゴリフィルター、モーダル開閉、編集対象）
const searchQuery = ref('');
const selectedCategoryFilter = ref('all');
const isModalOpen = ref(false);
const editingRoutine = ref(null);
const isBackdropMousedown = ref(false);

// Inertia.js フォームヘルパーによる入力値・送信管理
const form = useForm({
    title: '',
    category: 'work',
    sub_category: 'general',
    priority: 'medium',
    frequency_type: 'interval',
    interval_days: 1,
    day_of_week: 1, // 初期値：月曜日 (0:日〜6:土)
});

/**
 * 新規作成モーダルを開く関数
 */
const openCreateModal = () => {
    editingRoutine.value = null;
    form.reset();
    form.frequency_type = 'interval';
    form.interval_days = 1;
    form.day_of_week = 1;
    isModalOpen.value = true;
};

/**
 * 編集モーダルを開き、既存のルーティン設定値をフォームにバインドする関数
 */
const openEditModal = (routine) => {
    editingRoutine.value = routine;
    form.title = routine.title;
    form.category = routine.category;
    form.sub_category = routine.sub_category || 'general';
    form.priority = routine.priority;
    form.frequency_type = routine.frequency_type || 'interval';
    form.interval_days = routine.interval_days ?? 1;
    form.day_of_week = routine.day_of_week ?? 1;
    isModalOpen.value = true;
};

/**
 * フォーム送信時の処理（新規作成または更新を判定してイベントを発火）
 */
const submit = () => {
    if (editingRoutine.value) {
        emit('update', editingRoutine.value.id, form.data());
    } else {
        emit('store', form.data());
    }
    
    // 保存処理（イベント発火）のあとにモーダルを閉じる
    isModalOpen.value = false;
};

/**
 * 検索キーワードとカテゴリフィルターに基づいてルーティン一覧を絞り込む算出プロパティ
 */
const filteredRoutines = computed(() => {
    if (!props.routineTemplates) return [];
    return props.routineTemplates.filter(routine => {
        const matchesSearch = routine.title.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesCategory = selectedCategoryFilter.value === 'all' || routine.category === selectedCategoryFilter.value;
        return matchesSearch && matchesCategory;
    });
});

/**
 * 全角数字を半角数字に変換し、数値としてフォームに格納する
 */
const handleIntervalInput = (event) => {
    // IMEの変換中（漢字や全角かな・数字の入力確定前）は何もしない
    if (event.isComposing) return;

    let value = event.target.value;
    
    // 全角数字（０〜９）を半角数字（0〜9）に変換
    value = value.replace(/[０-９]/g, (s) => String.fromCharCode(s.charCodeAt(0) - 0xFEE0));
    
    // 数字以外を削除
    const numericValue = value.replace(/[^0-9]/g, '');
    
    // フォームへ反映（空でなければ数値に変換）
    form.interval_days = numericValue === '' ? '' : Number(numericValue);
    
    // ※ 以前入れていた `event.target.value = ...` による強制書き換えは
    //    IMEとの競合（文字の重複）の原因になるため削除します。
};

// 親コンポーネントからの参照（Template Ref）を許可
defineExpose({
    openCreateModal,
});
</script>

<template>
    <div class="space-y-4">
        <!-- コントロールバー（検索・カテゴリ絞り込み・新規作成トリガー） -->
        <RoutineControlBar 
            v-model:search-query="searchQuery"
            v-model:selected-category-filter="selectedCategoryFilter"
            @open-create-modal="openCreateModal"
        />

        <!-- 一覧セクション（絞り込み済みルーティンを表示） -->
        <RoutineListSection 
            :routine-templates="filteredRoutines"
            :total-count="props.routineTemplates.length"
            @toggle="(routine) => emit('toggle', routine)"
            @edit="openEditModal"
            @delete="(id) => emit('delete', id)"
        />

        <!-- 登録・編集モーダル -->
        <Transition name="slide-up">
            <div 
                v-if="isModalOpen" 
                @mousedown.self="isBackdropMousedown = true"
                @mouseup.self="if (isBackdropMousedown) isModalOpen = false; isBackdropMousedown = false;"
                class="fixed inset-0 z-[100] bg-slate-950/60 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4"
            >
                <div 
                    @click.stop 
                    class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl p-6 space-y-5 shadow-2xl border-t sm:border border-slate-200 max-h-[90vh] overflow-y-auto transform-gpu flex flex-col z-[101]"
                >
                    <!-- モバイル用インジケーターバー -->
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto sm:hidden shrink-0"></div>

                    <!-- モダルヘッダー -->
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4 shrink-0">
                        <h4 class="font-bold text-sm text-slate-900 flex items-center gap-2">
                            <span>{{ editingRoutine ? '✏️' : '✨' }}</span>
                            <span>{{ editingRoutine ? 'ルーティンを編集' : '新規ルーティン作成' }}</span>
                        </h4>
                        <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-xs p-1.5 rounded-xl hover:bg-slate-100 transition cursor-pointer">✕</button>
                    </div>

                    <!-- フォーム本体 -->
                    <div class="space-y-4 flex-1 overflow-y-auto pb-2">
                        <!-- タイトル入力 -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">タイトル</label>
                            <input v-model="form.title" type="text" class="w-full rounded-xl border border-slate-200 text-xs px-3.5 py-2.5 focus:outline-none focus:border-slate-900 transition shadow-2xs" placeholder="例: 毎日のタスク確認" />
                            <div v-if="form.errors.title" class="text-xs text-rose-600 mt-1">{{ form.errors.title }}</div>
                        </div>

                        <!-- カテゴリ & サブカテゴリ選択 -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">カテゴリ</label>
                                <select 
                                    v-model="form.category" 
                                    @change="form.sub_category = categoryTree[form.category]?.defaultSub || 'general'"
                                    class="w-full rounded-xl border border-slate-200 text-xs px-3.5 py-2.5 focus:outline-none focus:border-slate-900 bg-white transition cursor-pointer shadow-2xs"
                                >
                                    <option v-for="(val, key) in categoryTree" :key="key" :value="key">
                                        {{ val.icon }} {{ val.label }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">サブカテゴリ</label>
                                <select 
                                    v-model="form.sub_category" 
                                    class="w-full rounded-xl border border-slate-200 text-xs px-3.5 py-2.5 focus:outline-none focus:border-slate-900 bg-white transition cursor-pointer shadow-2xs"
                                >
                                    <option 
                                        v-for="sub in (categoryTree[form.category]?.items || [])" 
                                        :key="sub.key" 
                                        :value="sub.key"
                                    >
                                        {{ sub.icon }} {{ sub.label }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- 優先度 & 頻度種別の選択 -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">優先度</label>
                                <select v-model="form.priority" class="w-full rounded-xl border border-slate-200 text-xs px-3.5 py-2.5 focus:outline-none focus:border-slate-900 bg-white transition cursor-pointer shadow-2xs">
                                    <option value="high">⚡ 高</option>
                                    <option value="medium">⚡ 中</option>
                                    <option value="low">⚡ 低</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">生成頻度</label>
                                <select v-model="form.frequency_type" class="w-full rounded-xl border border-slate-200 text-xs px-3.5 py-2.5 focus:outline-none focus:border-slate-900 bg-white transition cursor-pointer shadow-2xs">
                                    <option value="interval">日数指定(〇日ごとに生成)</option>
                                    <option value="day_of_week">曜日指定(毎週〇曜に生成)</option>
                                </select>
                            </div>
                        </div>

                        <!-- 【動的入力】日数指定（interval）を選択した場合 -->
                        <div v-if="form.frequency_type === 'interval'" class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">間隔（日数）</label>
                            <div class="flex items-center gap-2">
                                <input 
                                    v-model="form.interval_days" 
                                    type="text" 
                                    inputmode="numeric"
                                    @input="handleIntervalInput"
                                    @keydown.enter.prevent
                                    class="w-24 rounded-xl border border-slate-200 text-xs px-3.5 py-2 bg-white focus:outline-none focus:border-slate-900 transition shadow-2xs" 
                                />
                                <span class="text-xs text-slate-600 font-medium">日毎にタスクを生成する</span>
                            </div>
                            <div v-if="form.errors.interval_days" class="text-xs text-rose-600 mt-1">{{ form.errors.interval_days }}</div>
                        </div>

                        <!-- 【動的入力】曜日毎（day_of_week）を選択した場合 -->
                        <div v-if="form.frequency_type === 'day_of_week'" class="p-3 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">実行する曜日</label>
                            <select v-model.number="form.day_of_week" class="w-full rounded-xl border border-slate-200 text-xs px-3.5 py-2 bg-white focus:outline-none focus:border-slate-900 transition cursor-pointer shadow-2xs">
                                <option :value="0">日曜日</option>
                                :value="1"><option :value="1">月曜日</option>
                                <option :value="2">火曜日</option>
                                <option :value="3">水曜日</option>
                                <option :value="4">木曜日</option>
                                <option :value="5">金曜日</option>
                                <option :value="6">土曜日</option>
                            </select>
                            <div v-if="form.errors.day_of_week" class="text-xs text-rose-600 mt-1">{{ form.errors.day_of_week }}</div>
                        </div>
                    </div>

                    <!-- モダルフッター（アクションボタン） -->
                    <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 shrink-0">
                        <button @click="isModalOpen = false" type="button" class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition cursor-pointer active:scale-95 shadow-2xs">
                            キャンセル
                        </button>
                        <button @click="submit" type="button" class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md transition cursor-pointer active:scale-95">
                            保存する
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>