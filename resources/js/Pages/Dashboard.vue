<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    tasks: Array,
});

// 可能な限り具体的かつ網羅的に拡充したカテゴリツリー
const categoryTree = {
    work: {
        label: '仕事',
        icon: '💼',
        badgeClass: 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100',
        items: [
            { key: 'project', label: 'プロジェクト開発', icon: '💻' },
            { key: 'meeting', label: 'ミーティング・面談', icon: '🤝' },
            { key: 'task', label: '通常タスク・ルーチン', icon: '📝' },
            { key: 'docs', label: '資料・レポート作成', icon: '📊' },
            { key: 'client', label: '顧客対応・連絡', icon: '📞' },
            { key: 'management', label: 'マネジメント・採用', icon: '👥' },
        ]
    },
    private: {
        label: 'プライベート',
        icon: '🏠',
        badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100',
        items: [
            { key: 'shopping', label: '買い物・EC', icon: '🛒' },
            { key: 'outing', label: 'お出かけ・旅行', icon: '🚗' },
            { key: 'housework', label: '家事・掃除・洗濯', icon: '🧹' },
            { key: 'cooking', label: '料理・食事', icon: '🍳' },
            { key: 'hobby', label: '趣味・エンタメ', icon: '🎨' },
            { key: 'family', label: '家族・用事', icon: '👨‍👩‍👧‍👦' },
            { key: 'errands', label: '手続き・役所', icon: '🏛️' },
        ]
    },
    study: {
        label: '学習・自己投資',
        icon: '📚',
        badgeClass: 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100',
        items: [
            { key: 'reading', label: '読書・インプット', icon: '📖' },
            { key: 'coding', label: 'プログラミング・技術', icon: '⚡' },
            { key: 'language', label: '語学・英語', icon: '🌐' },
            { key: 'qualification', label: '資格試験・勉強', icon: '📝' },
            { key: 'output', label: '記事執筆・発信', icon: '✍️' },
        ]
    },
    health: {
        label: 'ヘルスケア',
        icon: '💪',
        badgeClass: 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100',
        items: [
            { key: 'workout', label: '筋トレ・運動', icon: '🏋️' },
            { key: 'running', label: 'ランニング・散歩', icon: '🏃' },
            { key: 'medical', label: '病院・通院・薬', icon: '🏥' },
            { key: 'mental', label: 'メンタルケア・睡眠', icon: '🧘' },
            { key: 'diet', label: '食事管理・栄養', icon: '🥗' },
        ]
    },
    finance: {
        label: 'ファイナンス',
        icon: '💰',
        badgeClass: 'bg-cyan-50 text-cyan-700 border-cyan-200 hover:bg-cyan-100',
        items: [
            { key: 'banking', label: '口座・振込・管理', icon: '💳' },
            { key: 'investment', label: '資産運用・投資', icon: '📈' },
            { key: 'tax', label: '税金・確定申告', icon: '🧾' },
            { key: 'budget', label: '家計簿・固定費', icon: '📉' },
        ]
    }
};

const getSubCategoryMeta = (category, subCategoryKey) => {
    const parent = categoryTree[category] || categoryTree.private;
    const found = parent.items.find(i => i.key === subCategoryKey);
    return found || parent.items[0];
};

const activeTab = ref('all');

const form = useForm({
    title: '',
    due_date: new Date().toISOString().split('T')[0],
    category: 'private',
    sub_category: 'shopping',
    priority: 'medium',
});

const todayStr = new Date().toISOString().split('T')[0];

const getTomorrowStr = () => {
    const d = new Date();
    d.setDate(d.getDate() + 1);
    return d.toISOString().split('T')[0];
};

const getThisWeekendStr = () => {
    const d = new Date();
    const day = d.getDay();
    const diff = d.getDate() + (7 - day) % 7;
    d.setDate(diff);
    return d.toISOString().split('T')[0];
};

const submitTask = () => {
    if (!form.title.trim()) return;
    
    form.post(route('tasks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('title');
            form.priority = 'medium';
            form.due_date = todayStr;
        },
    });
};

const toggleTask = (task) => {
    router.patch(route('tasks.update', task.id), {
        is_completed: !task.is_completed,
    }, {
        preserveScroll: true,
    });
};

const activeMenu = ref({ taskId: null, type: null });

const toggleMenu = (taskId, type, event) => {
    event.stopPropagation();
    if (activeMenu.value.taskId === taskId && activeMenu.value.type === type) {
        activeMenu.value = { taskId: null, type: null };
    } else {
        activeMenu.value = { taskId, type };
    }
};

const closeMenu = () => {
    activeMenu.value = { taskId: null, type: null };
};

onMounted(() => {
    window.addEventListener('click', closeMenu);
});
onUnmounted(() => {
    window.removeEventListener('click', closeMenu);
});

const updateCategoryAndSub = (task, category, subCategory) => {
    router.patch(route('tasks.update', task.id), { 
        category, 
        sub_category: subCategory 
    }, { preserveScroll: true });
    closeMenu();
};

const updatePriority = (task, priority) => {
    router.patch(route('tasks.update', task.id), { priority }, { preserveScroll: true });
    closeMenu();
};

const updateDueDate = (task, due_date) => {
    router.patch(route('tasks.update', task.id), { due_date }, { preserveScroll: true });
    closeMenu();
};

const deleteTask = (task) => {
    router.delete(route('tasks.destroy', task.id), {
        preserveScroll: true,
    });
};

const editingTaskId = ref(null);
const editingTitle = ref('');

const startEdit = (task) => {
    closeMenu();
    editingTaskId.value = task.id;
    editingTitle.value = task.title;
};

const saveEdit = (task) => {
    if (!editingTitle.value.trim() || editingTitle.value === task.title) {
        editingTaskId.value = null;
        return;
    }
    router.patch(route('tasks.update', task.id), {
        title: editingTitle.value,
    }, {
        preserveScroll: true,
        onSuccess: () => { editingTaskId.value = null; },
    });
};

const cancelEdit = () => {
    editingTaskId.value = null;
};

const todayTasks = computed(() => props.tasks.filter(t => t.due_date === todayStr));
const completedTodayCount = computed(() => todayTasks.value.filter(t => t.is_completed).length);
const totalTodayCount = computed(() => todayTasks.value.length);
const progressPercent = computed(() => {
    if (totalTodayCount.value === 0) return 0;
    return Math.round((completedTodayCount.value / totalTodayCount.value) * 100);
});
const completedTotalCount = computed(() => props.tasks.filter(t => t.is_completed).length);

const filteredTasks = computed(() => {
    let result = [...props.tasks];

    if (activeTab.value === 'today') {
        result = result.filter(task => task.due_date === todayStr);
        result.sort((a, b) => {
            if (a.is_completed !== b.is_completed) {
                return a.is_completed ? 1 : -1;
            }
            const priorityWeight = { high: 3, medium: 2, low: 1 };
            const pDiff = (priorityWeight[b.priority] || 2) - (priorityWeight[a.priority] || 2);
            if (pDiff !== 0) return pDiff;
            return a.id - b.id;
        });
    } else if (activeTab.value !== 'all') {
        result = result.filter(task => task.category === activeTab.value);
    }

    return result;
});

const priorityConfig = {
    high: { 
        label: '重要度: 高', 
        badgeClass: 'bg-rose-50 text-rose-700 border border-rose-200/90 font-semibold', 
        cardAccent: 'border-l-4 border-l-rose-500' 
    },
    medium: { 
        label: '重要度: 中', 
        badgeClass: 'bg-amber-50 text-amber-700 border border-amber-200/90', 
        cardAccent: 'border-l-4 border-l-amber-400' 
    },
    low: { 
        label: '重要度: 低', 
        badgeClass: 'bg-slate-100 text-slate-600 border border-slate-200/90', 
        cardAccent: 'border-l-4 border-l-slate-300' 
    },
};
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span>🧩</span> Tasks
                </h2>
                <div class="flex items-center gap-3 text-xs font-mono">
                    <span class="bg-slate-100 border border-slate-200 px-3 py-1 rounded-full text-slate-700 font-semibold shadow-2xs">
                        🎯 完了ピース: {{ completedTotalCount }}件
                    </span>
                </div>
            </div>
        </template>

        <div class="py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                
                <!-- 本日の達成状況 -->
                <div class="mb-6 bg-white border border-slate-200/80 shadow-xs sm:rounded-2xl p-5 backdrop-blur-xl">
                    <div class="flex items-center justify-between text-xs font-medium text-slate-600 mb-2">
                        <span class="flex items-center gap-1.5 font-semibold text-slate-900">
                            <span>📈</span> 本日のパズル完成度
                        </span>
                        <span class="font-mono">{{ completedTodayCount }} / {{ totalTodayCount }} 完了 ({{ progressPercent }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden border border-slate-200/60">
                        <div 
                            class="bg-slate-900 h-full transition-all duration-500 rounded-full"
                            :style="{ width: `${progressPercent}%` }"
                        ></div>
                    </div>
                </div>

                <!-- メインコンテナ -->
                <div class="bg-white border border-slate-200/80 shadow-xs sm:rounded-2xl p-6 sm:p-8 backdrop-blur-xl">
                    
                    <!-- 高速追加フォーム -->
                    <form @submit.prevent="submitTask" class="mb-6 flex gap-2">
                        <input 
                            type="text" 
                            v-model="form.title" 
                            placeholder="新しいピースを追加 (Enterで即座にはめ込む)..." 
                            class="w-full bg-white border border-slate-200 focus:border-slate-900 focus:ring-slate-900 rounded-xl shadow-2xs text-sm text-slate-900 placeholder-slate-400 py-3.5 px-4 transition"
                            autofocus
                        />
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-slate-900 text-white px-7 py-3.5 rounded-xl hover:bg-slate-800 active:bg-slate-950 text-sm font-medium transition shadow-sm cursor-pointer whitespace-nowrap"
                        >
                            ピース追加
                        </button>
                    </form>

                    <!-- タブ切り替え（各カテゴリ対応） -->
                    <div class="flex border-b border-slate-200/80 mb-6 overflow-x-auto scrollbar-none">
                        <button @click="activeTab = 'all'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'all' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            すべて ({{ tasks.length }})
                        </button>
                        <button @click="activeTab = 'work'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'work' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            💼 仕事 ({{ tasks.filter(t => t.category === 'work').length }})
                        </button>
                        <button @click="activeTab = 'private'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'private' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            🏠 プライベート ({{ tasks.filter(t => t.category === 'private').length }})
                        </button>
                        <button @click="activeTab = 'study'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'study' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            📚 学習 ({{ tasks.filter(t => t.category === 'study').length }})
                        </button>
                        <button @click="activeTab = 'health'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'health' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            💪 健康 ({{ tasks.filter(t => t.category === 'health').length }})
                        </button>
                        <button @click="activeTab = 'finance'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'finance' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            💰 資産 ({{ tasks.filter(t => t.category === 'finance').length }})
                        </button>
                        <button @click="activeTab = 'today'" :class="['py-2.5 px-4 text-sm font-medium border-b-2 -mb-px whitespace-nowrap transition', activeTab === 'today' ? 'border-slate-900 text-slate-900 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-800']">
                            📅 今日 ({{ tasks.filter(t => t.due_date === todayStr).length }})
                        </button>
                    </div>

                    <!-- 操作の案内 -->
                    <div class="mb-6 text-xs text-slate-600 bg-slate-50 border border-slate-200/80 px-4 py-3 rounded-xl font-medium flex items-center gap-2">
                        <span>💡</span>
                        <span>各ピースのカテゴリをクリックすると、階層メニューから詳細な用途へ切り替えられます。</span>
                    </div>

                    <!-- タスク一覧 -->
                    <div v-if="filteredTasks.length === 0" class="text-center py-20 text-slate-400 text-sm">
                        タスクのピースはありません
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div 
                            v-for="(task, index) in filteredTasks" 
                            :key="task.id"
                            :class="[
                                'flex flex-col justify-between p-5 bg-white border border-slate-200/90 rounded-2xl transition-all duration-200 group shadow-2xs relative gap-4',
                                priorityConfig[task.priority]?.cardAccent || 'border-l-4 border-l-slate-300',
                                task.is_completed ? 'opacity-40 bg-slate-50/50' : 'hover:border-slate-300 hover:shadow-xs'
                            ]"
                        >
                            <!-- 上段：アイコン、階層カテゴリ、タイトル、完了チェック -->
                            <div class="flex items-start gap-3.5">
                                <input 
                                    type="checkbox" 
                                    :checked="task.is_completed" 
                                    @change="toggleTask(task)"
                                    class="rounded-md border-slate-300 text-slate-900 focus:ring-slate-900 h-5 w-5 mt-0.5 cursor-pointer transition shrink-0"
                                />

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <button 
                                            @click.stop="toggleMenu(task.id, 'category', $event)"
                                            :class="['inline-flex items-center gap-1.5 text-[11px] font-semibold px-2.5 py-1 rounded-md border transition cursor-pointer', categoryTree[task.category]?.badgeClass || categoryTree.private.badgeClass]"
                                            title="クリックしてカテゴリ階層を切替"
                                        >
                                            <span>{{ categoryTree[task.category]?.icon || '🏠' }}</span>
                                            <span>{{ categoryTree[task.category]?.label || 'プライベート' }}</span>
                                            <span class="text-slate-400 font-normal">→</span>
                                            <span>{{ getSubCategoryMeta(task.category, task.sub_category).icon }}</span>
                                            <span>{{ getSubCategoryMeta(task.category, task.sub_category).label }}</span>
                                        </button>

                                        <span v-if="activeTab === 'today'" class="text-[10px] text-slate-400 font-mono">
                                            #{{ index + 1 }}
                                        </span>
                                    </div>

                                    <!-- 階層カテゴリ変更ポップオーバーメニュー（縦長になりすぎないよう高さ制限とスクロール対応） -->
                                    <div v-if="activeMenu.taskId === task.id && activeMenu.type === 'category'" class="absolute left-10 mt-1 w-60 bg-white border border-slate-200 rounded-xl shadow-xl py-2 z-20 max-h-72 overflow-y-auto space-y-3">
                                        <div v-for="(pVal, pKey) in categoryTree" :key="pKey" class="px-2">
                                            <div class="text-[10px] font-bold text-slate-400 px-2 mb-1 flex items-center gap-1">
                                                <span>{{ pVal.icon }}</span><span>{{ pVal.label }}</span>
                                            </div>
                                            <div class="space-y-0.5">
                                                <button 
                                                    v-for="sub in pVal.items" 
                                                    :key="sub.key" 
                                                    @click="updateCategoryAndSub(task, pKey, sub.key)" 
                                                    class="w-full text-left px-2.5 py-1.5 text-xs text-slate-700 hover:bg-slate-100 rounded-lg transition flex items-center gap-2"
                                                >
                                                    <span>{{ sub.icon }}</span>
                                                    <span>{{ sub.label }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 編集中の入力フィールド -->
                                    <div v-if="editingTaskId === task.id" class="mt-1">
                                        <input 
                                            type="text" 
                                            v-model="editingTitle" 
                                            @keyup.enter="saveEdit(task)"
                                            @keyup.esc="cancelEdit"
                                            @blur="saveEdit(task)"
                                            autofocus
                                            class="w-full text-base font-semibold border-slate-900 focus:ring-slate-900 rounded-lg shadow-2xs py-1.5 px-3 text-slate-900"
                                        />
                                    </div>

                                    <!-- 通常表示（タイトル） -->
                                    <div 
                                        v-else 
                                        @click="startEdit(task)"
                                        :class="['text-base font-semibold cursor-pointer leading-snug tracking-tight mt-1', task.is_completed ? 'line-through text-slate-400 font-normal' : 'text-slate-900 hover:text-slate-950']"
                                        title="クリックしてタイトルを編集"
                                    >
                                        {{ task.title }}
                                    </div>
                                </div>
                            </div>

                            <!-- 下段：ピースのパーツ（重要度、期限、削除） -->
                            <div class="flex items-center justify-between pt-3.5 border-t border-slate-100 text-xs px-0.5">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <div class="relative">
                                        <button 
                                            @click.stop="toggleMenu(task.id, 'priority', $event)"
                                            :class="['px-2.5 py-1.5 rounded-lg transition cursor-pointer font-medium flex items-center gap-1', priorityConfig[task.priority]?.badgeClass || priorityConfig.medium.badgeClass]"
                                        >
                                            <span>⚡</span>
                                            <span>{{ priorityConfig[task.priority]?.label || '重要度: 中' }}</span>
                                        </button>

                                        <div v-if="activeMenu.taskId === task.id && activeMenu.type === 'priority'" class="absolute left-0 mt-1.5 w-32 bg-white border border-slate-200 rounded-xl shadow-lg py-1 z-20">
                                            <button @click="updatePriority(task, 'high')" class="w-full text-left px-3 py-1.5 text-xs text-rose-700 hover:bg-rose-50 transition font-medium">⚡ 重要度: 高</button>
                                            <button @click="updatePriority(task, 'medium')" class="w-full text-left px-3 py-1.5 text-xs text-amber-700 hover:bg-amber-50 transition font-medium">⚡ 重要度: 中</button>
                                            <button @click="updatePriority(task, 'low')" class="w-full text-left px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-100 transition font-medium">⚡ 重要度: 低</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="relative">
                                        <button 
                                            @click.stop="toggleMenu(task.id, 'due', $event)"
                                            :class="['border rounded-lg px-2.5 py-1.5 transition flex items-center gap-1 cursor-pointer font-medium', task.due_date === todayStr && !task.is_completed ? 'bg-slate-900 text-white border-slate-900 shadow-2xs' : 'bg-slate-50/80 border-slate-200 text-slate-700 hover:bg-slate-100']"
                                        >
                                            <span>📅</span>
                                            <span>{{ task.due_date }}</span>
                                        </button>

                                        <div v-if="activeMenu.taskId === task.id && activeMenu.type === 'due'" @click.stop class="absolute right-0 mt-1.5 w-48 bg-white border border-slate-200 rounded-xl shadow-lg p-3 z-20 space-y-2">
                                            <div class="text-xs font-semibold text-slate-500 mb-1">期限を変更</div>
                                            <div class="grid grid-cols-1 gap-1">
                                                <button @click="updateDueDate(task, todayStr)" class="text-left px-2.5 py-1 text-xs bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-800 transition">今日 ({{ todayStr }})</button>
                                                <button @click="updateDueDate(task, getTomorrowStr())" class="text-left px-2.5 py-1 text-xs bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-800 transition">明日 ({{ getTomorrowStr() }})</button>
                                                <button @click="updateDueDate(task, getThisWeekendStr())" class="text-left px-2.5 py-1 text-xs bg-slate-50 hover:bg-slate-100 rounded-lg text-slate-800 transition">今週末</button>
                                            </div>
                                            <div class="pt-2 border-t border-slate-100">
                                                <input 
                                                    type="date" 
                                                    :value="task.due_date" 
                                                    @change="updateDueDate(task, $event.target.value)"
                                                    class="w-full text-xs bg-white border border-slate-200 rounded-lg px-2 py-1.5 text-slate-700 focus:border-slate-900 focus:ring-slate-900 cursor-pointer"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <button 
                                        @click="deleteTask(task)"
                                        class="text-slate-400 hover:text-rose-600 opacity-0 group-hover:opacity-100 transition px-1.5 py-1.5 cursor-pointer rounded-lg hover:bg-rose-50"
                                        title="削除"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>