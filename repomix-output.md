This file is a merged representation of a subset of the codebase, containing specifically included files and files not matching ignore patterns, combined into a single document by Repomix.

# File Summary

## Purpose
This file contains a packed representation of a subset of the repository's contents that is considered the most important context.
It is designed to be easily consumable by AI systems for analysis, code review,
or other automated processes.

## File Format
The content is organized as follows:
1. This summary section
2. Repository information
3. Directory structure
4. Repository files (if enabled)
5. Multiple file entries, each consisting of:
  a. A header with the file path (## File: path/to/file)
  b. The full contents of the file in a code block

## Usage Guidelines
- This file should be treated as read-only. Any changes should be made to the
  original repository files, not this packed version.
- When processing this file, use the file path to distinguish
  between different files in the repository.
- Be aware that this file may contain sensitive information. Handle it with
  the same level of security as you would the original repository.

## Notes
- Some files may have been excluded based on .gitignore rules and Repomix's configuration
- Binary files are not included in this packed representation. Please refer to the Repository Structure section for a complete list of file paths, including binary files
- Only files matching these patterns are included: app/**/*, resources/**/*, routes/**/*, database/**/*, composer.json, package.json, vite.config.js
- Files matching these patterns are excluded: **/node_modules/**, **/vendor/**, **/storage/**, **/bootstrap/cache/**, **/*.log, **/*.lock
- Files matching patterns in .gitignore are excluded
- Files matching default ignore patterns are excluded
- Files are sorted by Git change count (files with more changes are at the bottom)

# Directory Structure
```
app/
  Http/
    Controllers/
      Auth/
        AuthenticatedSessionController.php
        ConfirmablePasswordController.php
        EmailVerificationNotificationController.php
        EmailVerificationPromptController.php
        NewPasswordController.php
        PasswordController.php
        PasswordResetLinkController.php
        RegisteredUserController.php
        VerifyEmailController.php
      Controller.php
      ProfileController.php
      TaskController.php
    Middleware/
      HandleInertiaRequests.php
    Requests/
      Auth/
        LoginRequest.php
      ProfileUpdateRequest.php
  Models/
    Task.php
    User.php
  Policies/
    TaskPolicy.php
  Providers/
    AppServiceProvider.php
database/
  factories/
    UserFactory.php
  seeders/
    DatabaseSeeder.php
    TaskSeeder.php
  .gitignore
resources/
  css/
    app.css
  js/
    Components/
      Tasks/
        BulkActionBar.vue
        DesktopSidebar.vue
        MobileDrawer.vue
        MobileNav.vue
        Sidebar.vue
        TaskActionModal.vue
        TaskFormModal.vue
        TaskItem.vue
      ApplicationLogo.vue
      Checkbox.vue
      DangerButton.vue
      Dropdown.vue
      DropdownLink.vue
      InputError.vue
      InputLabel.vue
      Modal.vue
      NavLink.vue
      PrimaryButton.vue
      ResponsiveNavLink.vue
      SecondaryButton.vue
      TextInput.vue
    Composables/
      useTaskUtils.js
    Constants/
      task.js
    Layouts/
      AuthenticatedLayout.vue
      GuestLayout.vue
    Pages/
      Auth/
        ConfirmPassword.vue
        ForgotPassword.vue
        Login.vue
        Register.vue
        ResetPassword.vue
        VerifyEmail.vue
      Profile/
        Partials/
          DeleteUserForm.vue
          UpdatePasswordForm.vue
          UpdateProfileInformationForm.vue
        Edit.vue
      Tasks/
        Create.vue
        Edit.vue
        Index.vue
      Dashboard.vue
      TaskGuide.vue
      Welcome.vue
    app.js
    bootstrap.js
  views/
    app.blade.php
routes/
  auth.php
  console.php
  web.php
composer.json
package.json
vite.config.js
```

# Files

## File: database/seeders/TaskSeeder.php
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 必要に応じて既存データをリセットする場合はコメントアウトを解除
        // Task::truncate();

        $pools = [
            ['category' => 'work', 'sub' => 'project', 'titles' => ['Q3プロジェクトのキックオフ資料作成', 'クライアント向け進捗レポートのまとめ', '新機能の要件定義レビュー', 'デザインモックアップのフィードバック反映']],
            ['category' => 'work', 'sub' => 'meeting', 'titles' => ['週次定例ミーティングの準備', 'デザインチームとのすり合わせ', 'クライアントとのキックオフ面談']],
            ['category' => 'work', 'sub' => 'task', 'titles' => ['バグ修正：ログイン時のバリデーションエラー', 'APIレスポンスの高速化対応', 'コードレビュー（PR #142）']],
            ['category' => 'work', 'sub' => 'admin', 'titles' => ['経費精算書の提出と承認確認', '今週の工数入力とタスク整理']],
            
            ['category' => 'personal', 'sub' => 'shopping', 'titles' => ['日用品（洗剤・ティッシュ）のまとめ買い', 'スーパーで今週末の食材調達', 'コーヒー豆の買い出し']],
            ['category' => 'personal', 'sub' => 'housework', 'titles' => ['部屋全体の掃除機かけと換気', 'たまった洗濯物の整理とアイロンがけ', '水回り（キッチン・浴室）の清掃']],
            ['category' => 'personal', 'sub' => 'family', 'titles' => ['実家の両親へ近況連絡の電話', '週末の家族ディナーのお店予約']],
            ['category' => 'personal', 'sub' => 'event', 'titles' => ['友人との週末の予定調整', '映画のチケット事前予約']],
            
            ['category' => 'growth', 'sub' => 'study', 'titles' => ['TypeScriptの高度な型推論の学習', 'Vue 3 Composition APIの公式ドキュメント読み込み', '資格試験の過去問1年分を解く']],
            ['category' => 'growth', 'sub' => 'reading', 'titles' => ['ビジネス書の読書（第3章まで）', 'テックブログの最新記事チェック']],
            ['category' => 'growth', 'sub' => 'goal', 'titles' => ['今月の自己成長目標の進捗振り返り', '来期のスキルアップ計画の立案']],
            
            ['category' => 'health', 'sub' => 'fitness', 'titles' => ['ジムでの筋トレ（下半身メニュー）', '夜の軽めなランニング（3km）', 'ストレッチとヨガ（20分）']],
            ['category' => 'health', 'sub' => 'medical', 'titles' => ['定期歯科検診の予約', '処方薬の受け取り']],
            ['category' => 'health', 'sub' => 'mental', 'titles' => ['マインドフルネス瞑想（15分）', 'デジタルデトックスの時間を確保']],
            
            ['category' => 'finance', 'sub' => 'payment', 'titles' => ['クレジットカードの引き落とし口座残高確認', '今月の光熱費の支払い手続き']],
            ['category' => 'finance', 'sub' => 'procedure', 'titles' => ['ふるさと納税のワンストップ特例申請書類の発送', '保険内容の見直し手続き']],
            ['category' => 'finance', 'sub' => 'asset', 'titles' => ['つみたてNISAの運用状況チェック', '家計簿アプリの収支データ確認']],
            
            ['category' => 'inbox', 'sub' => 'general', 'titles' => ['後で読むブックマークの整理', 'PCデスクトップのファイル整理', '気になったアイデアのメモ書き', '未整理のメールチェックと返信']],
        ];

        $offsets = [0, 0, 1, 1, 2, 3, 4, 5, 6, 7, 9, 11, 14];
        $priorities = ['high', 'medium', 'low'];

        $tasks = [];
        $createdTitles = [];

        while (count($tasks) < 30) {
            $pool = $pools[array_rand($pools)];
            $title = $pool['titles'][array_rand($pool['titles'])];

            if (!in_array($title, $createdTitles)) {
                $createdTitles[] = $title;
                $offset = $offsets[array_rand($offsets)];
                $priority = $priorities[array_rand($priorities)];

                $tasks[] = [
                    'title' => $title,
                    'category' => $pool['category'],
                    'sub_category' => $pool['sub'],
                    'priority' => $priority,
                    'due_date' => Carbon::today()->addDays($offset)->toDateString(),
                    'is_completed' => (rand(1, 100) <= 15), // 15%の確率で完了済み
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Task::insert($tasks);
    }
}
```

## File: resources/js/Components/Tasks/BulkActionBar.vue
```vue
<script setup>
defineProps({
    selectedCount: Number,
});

defineEmits([
    'complete', 'uncomplete', 'open-due-modal', 
    'open-category-modal', 'open-priority-modal', 'delete', 'clear'
]);
</script>

<template>
    <div class="fixed bottom-20 left-4 right-4 sm:left-1/2 sm:-translate-x-1/2 sm:w-auto z-50 bg-slate-900/95 backdrop-blur-md text-white text-xs p-3.5 rounded-2xl shadow-2xl flex items-center gap-2 sm:gap-3 border border-slate-700 flex-wrap justify-center">
        <span class="font-bold text-amber-400 px-1">{{ selectedCount }}件選択中</span>
        <div class="h-4 w-px bg-slate-700 hidden sm:block"></div>
        
        <button @click="$emit('complete')" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            ✅ 完了
        </button>
        <button @click="$emit('uncomplete')" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            ⏳ 未完了
        </button>

        <button @click="$emit('open-due-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>📅</span> 期限
        </button>

        <button @click="$emit('open-category-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>🏷️</span> カテゴリ
        </button>

        <button @click="$emit('open-priority-modal', $event)" class="bg-slate-800 hover:bg-slate-700 active:scale-95 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            <span>⚡</span> 優先度
        </button>

        <button @click="$emit('delete')" class="bg-rose-950/80 hover:bg-rose-900 active:scale-95 text-rose-200 px-3.5 py-2 rounded-xl transition cursor-pointer font-bold flex items-center gap-1 shadow-sm">
            🗑️ 削除
        </button>
        <button @click="$emit('clear')" class="text-slate-400 hover:text-white px-2 py-2 ml-1" title="閉じる">
            ✕
        </button>
    </div>
</template>
```

## File: resources/js/Components/Tasks/DesktopSidebar.vue
```vue
<script setup>
import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

defineProps({
    tasks: Array,
    currentCategory: String,
    todayStr: String,
});
</script>

<template>
    <aside class="w-64 shrink-0 hidden lg:block space-y-6 bg-white border border-slate-200/80 rounded-3xl p-5 shadow-sm sticky top-20">
        <!-- ビュー -->
        <div class="space-y-2">
            <div class="text-[11px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="space-y-1">
                <Link 
                    :href="route('dashboard', { category: 'inbox' })" 
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📥</span> 未分類</span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === 'inbox').length }}
                    </span>
                </Link>

                <Link 
                    :href="route('dashboard', { category: 'today' })" 
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📅</span> 今日</span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <Link 
                    :href="route('dashboard', { category: 'all' })" 
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📂</span> すべてのタスク</span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- カテゴリ -->
        <div class="space-y-2">
            <div class="text-[11px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1">
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    :href="route('dashboard', { category: key })"
                    :class="['w-full p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[10px] px-2 py-0.5 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>
            </div>
        </div>
    </aside>
</template>
```

## File: resources/js/Components/Tasks/MobileDrawer.vue
```vue
<script setup>
import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

defineProps({
    tasks: Array,
    currentCategory: String,
    todayStr: String,
});

defineEmits(['close']);
</script>

<template>
    <div class="space-y-4 pb-4">
        <!-- クイックビュー -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="grid grid-cols-2 gap-1.5">
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'inbox' })" 
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-xl">📥</span>
                    <span class="text-[11px] font-bold">未分類</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === 'inbox').length }}
                    </span>
                </Link>

                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'today' })" 
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-xl">📅</span>
                    <span class="text-[11px] font-bold">今日</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'all' })" 
                    :class="['col-span-2 p-3 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-4 active:scale-95 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📂</span> すべてのタスク</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- カテゴリリスト -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1.5">
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    @click="$emit('close')"
                    :href="route('dashboard', { category: key })"
                    :class="['p-3 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-4 active:scale-95 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>
            </div>
        </div>
    </div>
</template>
```

## File: resources/js/Components/Tasks/MobileNav.vue
```vue
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    currentCategory: String,
    todayCount: Number,
    inboxCount: Number,
});

defineEmits(['open-menu', 'open-task-modal']);
</script>

<template>
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/90 backdrop-blur-md border-t border-slate-200/80 px-4 py-2 lg:hidden flex items-center justify-around shadow-lg">
        <!-- すべて -->
        <Link 
            :href="route('dashboard', { category: 'all' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl', currentCategory === 'all' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📂</span>
            <span>すべて</span>
        </Link>

        <!-- 今日 -->
        <Link 
            :href="route('dashboard', { category: 'today' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl relative', currentCategory === 'today' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📅</span>
            <span>今日</span>
            <span v-if="todayCount > 0" class="absolute -top-1 -right-1 bg-slate-900 text-white text-[9px] font-mono px-1.5 py-0.2 rounded-full">
                {{ todayCount }}
            </span>
        </Link>

        <!-- 新規作成ボタン（半角の「+」に変更し、正円で中央配置を調整） -->
        <button 
            @click="$emit('open-task-modal')"
            class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-900 text-white shadow-md active:scale-95 transition cursor-pointer hover:bg-slate-800"
        >
            <span class="text-xl font-normal leading-none">+</span>
        </button>

        <!-- 未分類 -->
        <Link 
            :href="route('dashboard', { category: 'inbox' })"
            :class="['flex flex-col items-center gap-0.5 text-[10px] font-bold transition px-2 py-1 rounded-xl relative', currentCategory === 'inbox' ? 'text-slate-900 bg-slate-100' : 'text-slate-400 hover:text-slate-600']"
        >
            <span class="text-base">📥</span>
            <span>未分類</span>
            <span v-if="inboxCount > 0" class="absolute -top-1 -right-1 bg-slate-900 text-white text-[9px] font-mono px-1.5 py-0.2 rounded-full">
                {{ inboxCount }}
            </span>
        </Link>

        <!-- メニューボタン -->
        <button 
            @click="$emit('open-menu')"
            class="flex flex-col items-center gap-0.5 text-[10px] font-bold text-slate-400 hover:text-slate-600 transition px-2 py-1 rounded-xl cursor-pointer"
        >
            <span class="text-base">🍔</span>
            <span>メニュー</span>
        </button>
    </div>
</template>
```

## File: resources/js/Components/Tasks/Sidebar.vue
```vue
<script setup>
import { Link } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

defineProps({
    tasks: Array,
    currentCategory: String,
    todayStr: String,
});

defineEmits(['close']);
</script>

<template>
    <div class="space-y-4 pb-1">
        <!-- クイックビュー -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">ビュー</div>
            <div class="grid grid-cols-2 gap-1.5">
                <!-- 未分類 -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'inbox' })" 
                    :class="['p-2.5 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-0.5 text-center active:scale-95 border cursor-pointer', currentCategory === 'inbox' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-lg">📥</span>
                    <span class="text-[11px] font-bold">未分類</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'inbox' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === 'inbox').length }}
                    </span>
                </Link>

                <!-- 今日 -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'today' })" 
                    :class="['p-2.5 rounded-2xl text-xs font-semibold transition flex flex-col items-center justify-center gap-0.5 text-center active:scale-95 border cursor-pointer', currentCategory === 'today' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="text-lg">📅</span>
                    <span class="text-[11px] font-bold">今日</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'today' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.due_date === todayStr).length }}
                    </span>
                </Link>

                <!-- すべて -->
                <Link 
                    @click="$emit('close')"
                    :href="route('dashboard', { category: 'all' })" 
                    :class="['col-span-2 p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 active:scale-95 border cursor-pointer', currentCategory === 'all' ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold"><span>📂</span> すべてのタスク</span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === 'all' ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.length }}
                    </span>
                </Link>
            </div>
        </div>

        <!-- カテゴリリスト（横長スタイルに変更し、奇数個でも崩れないように修正） -->
        <div class="space-y-1.5">
            <div class="text-[10px] font-bold text-slate-400 px-1 uppercase tracking-wider">カテゴリ</div>
            <div class="space-y-1.5">
                <Link 
                    v-for="(val, key) in categoryTree" 
                    :key="key"
                    v-show="key !== 'inbox'"
                    @click="$emit('close')"
                    :href="route('dashboard', { category: key })"
                    :class="['p-2.5 rounded-2xl text-xs font-semibold transition flex items-center justify-between px-3.5 active:scale-95 border cursor-pointer', currentCategory === key ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200/80']"
                >
                    <span class="flex items-center gap-2 text-xs font-bold">
                        <span>{{ val.icon }}</span>
                        <span>{{ val.label }}</span>
                    </span>
                    <span class="font-mono text-[9px] px-1.5 py-0.2 rounded-full" :class="currentCategory === key ? 'bg-slate-800 text-slate-200' : 'bg-slate-200/60 text-slate-600'">
                        {{ tasks.filter(t => t.category === key).length }}
                    </span>
                </Link>
            </div>
        </div>
    </div>
</template>
```

## File: resources/js/Components/Tasks/TaskActionModal.vue
```vue
<script setup>
import { categoryTree } from '@/Constants/task';

defineProps({
    activeMenuTask: Object,
    activeMenuType: String,
});

defineEmits([
    'close', 'update-category', 'update-priority', 
    'update-due', 'bulk-update-due', 'bulk-update-category', 'bulk-update-priority'
]);
</script>

<template>
    <Transition name="slide-up">
        <div v-if="activeMenuTask" @click="$emit('close')" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4">
            <div @click.stop class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-5 shadow-2xl space-y-4 border-t sm:border border-slate-200 max-h-[85vh] flex flex-col">
                
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto sm:hidden shrink-0"></div>

                <!-- カテゴリ変更 -->
                <template v-if="activeMenuType === 'category' || activeMenuType === 'bulkCategory'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>🏷️</span> {{ activeMenuTask.id === 'bulk' ? '一括カテゴリ変更' : 'カテゴリを変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1">✕</button>
                    </div>
                    <div class="flex-1 overflow-y-auto space-y-4 pr-1 pb-4">
                        <div v-for="(pVal, pKey) in categoryTree" :key="pKey" class="space-y-1.5">
                            <div class="text-[11px] font-bold text-slate-400 px-1 flex items-center gap-1.5">
                                <span>{{ pVal.icon }}</span><span>{{ pVal.label }}</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <button 
                                    v-for="sub in pVal.items" 
                                    :key="sub.key" 
                                    @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-category', pKey, sub.key) : $emit('update-category', activeMenuTask, pKey, sub.key)" 
                                    class="p-2.5 text-xs font-semibold bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200/80 rounded-2xl transition flex flex-col items-center justify-center gap-1 text-center active:scale-95 shadow-2xs"
                                >
                                    <span class="text-base">{{ sub.icon }}</span>
                                    <span class="truncate w-full text-[11px]">{{ sub.label }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- 重要度変更 -->
                <template v-else-if="activeMenuType === 'priority' || activeMenuType === 'bulkPriority'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>⚡</span> {{ activeMenuTask.id === 'bulk' ? '一括重要度変更' : '重要度を変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1">✕</button>
                    </div>
                    <div class="space-y-2 pb-2">
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'high') : $emit('update-priority', activeMenuTask, 'high')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100 cursor-pointer"
                        >
                            ⚡ 高
                        </button>
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'medium') : $emit('update-priority', activeMenuTask, 'medium')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100 cursor-pointer"
                        >
                            ⚡ 中
                        </button>
                        <button 
                            @click="activeMenuTask.id === 'bulk' ? $emit('bulk-update-priority', 'low') : $emit('update-priority', activeMenuTask, 'low')" 
                            class="w-full text-left px-4 py-3 text-xs font-bold rounded-2xl transition flex items-center gap-2 border bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 cursor-pointer"
                        >
                            ⚡ 低
                        </button>
                    </div>
                </template>

                <!-- 期限日変更 -->
                <template v-else-if="activeMenuType === 'due' || activeMenuType === 'bulkDue'">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                        <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <span>📅</span> {{ activeMenuTask.id === 'bulk' ? '一括期限日変更' : '期限日を変更' }}
                        </span>
                        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1">✕</button>
                    </div>
                    <div class="space-y-3 pb-2">
                        <input 
                            type="date" 
                            :value="activeMenuTask.id === 'bulk' ? '' : activeMenuTask.due_date" 
                            @change="activeMenuTask.id === 'bulk' ? $emit('bulk-update-due', $event.target.value) : $emit('update-due', activeMenuTask, $event.target.value)"
                            class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                        />
                        <button @click="$emit('close')" class="w-full py-3 bg-slate-900 text-white rounded-2xl text-xs font-bold shadow-md hover:bg-slate-800 transition">閉じる</button>
                    </div>
                </template>

            </div>
        </div>
    </Transition>
</template>
```

## File: resources/js/Components/Tasks/TaskFormModal.vue
```vue
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

const props = defineProps({
    isOpen: Boolean,
    form: Object, // 通常作成用 Inertia form object
});

const emit = defineEmits(['close', 'submit']);

// モード管理 ('single': 通常作成, 'smart': スマート一括入力)
const mode = ref('single');
const smartText = ref('');
const isProcessing = ref(false);

// スマート入力をバックエンドに送信して一括生成
const handleSmartSubmit = () => {
    if (!smartText.value.trim()) return;

    isProcessing.value = true;

    router.post(route('tasks.store-bulk'), {
        raw_text: smartText.value
    }, {
        onSuccess: () => {
            isProcessing.value = false;
            smartText.value = '';
            emit('close');
        },
        onError: () => {
            isProcessing.value = false;
        }
    });
};
</script>

<template>
    <Transition name="slide-up">
        <div v-if="isOpen" @click="$emit('close')" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs flex items-end sm:items-center sm:justify-center sm:p-4">
            <div @click.stop class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl space-y-5 border-t sm:border border-slate-200 max-h-[90vh] flex flex-col">
                
                <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto sm:hidden shrink-0"></div>

                <!-- ヘッダー ＆ タブ切り替え -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                    <span class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>✨</span> タスクを追加
                    </span>

                    <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold">
                        <button 
                            @click="mode = 'single'" 
                            :class="['px-3 py-1 rounded-lg transition cursor-pointer', mode === 'single' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900']"
                        >
                            通常
                        </button>
                        <button 
                            @click="mode = 'smart'" 
                            :class="['px-3 py-1 rounded-lg transition cursor-pointer', mode === 'smart' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-900']"
                        >
                            ✨ スマート一括
                        </button>
                    </div>

                    <button @click="$emit('close')" class="text-slate-400 hover:text-slate-900 font-bold text-xs p-1 cursor-pointer">✕</button>
                </div>

                <!-- 通常作成モード -->
                <form v-if="mode === 'single'" @submit.prevent="$emit('submit')" class="space-y-4 overflow-y-auto pr-1 pb-2">
                    <!-- タイトル -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-500">タスク名</label>
                        <input 
                            type="text" 
                            v-model="form.title" 
                            placeholder="例: 週報を作成する" 
                            autofocus
                            class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-slate-900 shadow-inner focus:bg-white transition"
                        />
                        <div v-if="form.errors.title" class="text-rose-600 text-[10px] font-bold pl-1">{{ form.errors.title }}</div>
                    </div>

                    <!-- カテゴリ & サブカテゴリ選択 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">カテゴリ</label>
                            <select 
                                v-model="form.category" 
                                @change="form.sub_category = categoryTree[form.category]?.defaultSub || 'general'"
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            >
                                <option v-for="(val, key) in categoryTree" :key="key" :value="key">
                                    {{ val.icon }} {{ val.label }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">サブカテゴリ</label>
                            <select 
                                v-model="form.sub_category" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
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

                    <!-- 期限日 & 重要度 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">期限日</label>
                            <input 
                                type="date" 
                                v-model="form.due_date" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-500">重要度</label>
                            <select 
                                v-model="form.priority" 
                                class="w-full text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3.5 py-3 text-slate-700 cursor-pointer shadow-inner"
                            >
                                <option value="high">⚡ 高</option>
                                <option value="medium">⚡ 中</option>
                                <option value="low">⚡ 低</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow-lg transition active:scale-98 cursor-pointer disabled:opacity-50"
                        >
                            タスクを追加する
                        </button>
                    </div>
                </form>

                <!-- スマート一括入力モード -->
                <div v-else class="space-y-4 overflow-y-auto pr-1 pb-2">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500">今日やることを自由に書き出す</label>
                        <p class="text-[11px] text-slate-400">改行区切りで入力すると、2文字の短縮記号やキーワードからカテゴリ・期限・優先度を自動推論します。</p>
                    </div>

                    <!-- 💡 Tips（書き方のコツ）セクション -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 space-y-2.5 text-[11px] text-slate-600">
                        <div class="font-bold text-slate-800 flex items-center gap-1.5">
                            <span>💡</span> スマート入力のヒント
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-500">
                            <div>
                                <span class="font-bold text-slate-700">⚡ 優先度:</span> 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[hi]</code> 高 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[md]</code> 中 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[lo]</code> 低
                            </div>
                            <div>
                                <span class="font-bold text-slate-700">📁 カテゴリ:</span> 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[wo]</code> 仕事 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[pe]</code> 個人 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[gr]</code> 成長 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[he]</code> 健康 / 
                                <code class="bg-white px-1 py-0.5 rounded border border-slate-200 text-slate-800 font-mono">[fi]</code> お金
                            </div>
                            <div class="sm:col-span-2">
                                <span class="font-bold text-slate-700">📅 期限:</span> 「明日」「今週末」「月末」「月曜」など
                            </div>
                        </div>
                    </div>

                    <textarea 
                        v-model="smartText"
                        rows="6"
                        placeholder="例：
                ・明日の会議用資料を修正する [wo] [hi]
                ・週末にスーパーで食材の買い物 [pe] [lo]
                ・英語の学習を30分行う [gr] 月曜"
                        autofocus
                        class="w-full text-xs sm:text-sm bg-slate-50 border border-slate-200 rounded-2xl p-4 text-slate-900 shadow-inner focus:bg-white transition resize-none leading-relaxed"
                    ></textarea>

                    <div class="pt-2">
                        <button 
                            @click="handleSmartSubmit"
                            :disabled="isProcessing || !smartText.trim()"
                            class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow-lg transition active:scale-98 cursor-pointer disabled:opacity-50"
                        >
                            {{ isProcessing ? '解析・生成中...' : 'スマート一括生成する' }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </Transition>
</template>
```

## File: resources/js/Components/Tasks/TaskItem.vue
```vue
<script setup>
import { ref } from 'vue';
import { categoryTree, priorityConfig } from '@/Constants/task';

const props = defineProps({
    task: Object,
    isSelectionMode: Boolean,
    isSelected: Boolean,
    isHighlighted: Boolean,
    isFlashing: Boolean,
});

const emit = defineEmits([
    'toggle', 'select', 'delete', 'update-title', 
    'open-menu', 'action-handled'
]);

const editingTaskId = ref(null);
const editingTitle = ref('');

const startEdit = (task) => {
    editingTaskId.value = task.id;
    editingTitle.value = task.title;
};

const saveEdit = (task) => {
    if (!editingTitle.value.trim() || editingTitle.value === task.title) {
        editingTaskId.value = null;
        return;
    }
    emit('action-handled', task);
    emit('update-title', task, editingTitle.value);
    editingTaskId.value = null;
};

const cancelEdit = () => {
    editingTaskId.value = null;
};

const getSubCategoryMeta = (category, subCategoryKey) => {
    const parent = categoryTree[category] || categoryTree.inbox;
    const found = parent.items.find(i => i.key === subCategoryKey);
    return found || parent.items[0];
};

const getDueDateBadgeClass = (dueDate, isCompleted) => {
    if (!dueDate || isCompleted) return 'bg-slate-50 border-slate-200 text-slate-500';
    const today = new Date().toISOString().split('T')[0];
    if (dueDate <= today) return 'bg-slate-900 text-white border-slate-900 font-semibold';
    return 'bg-slate-50 border-slate-200 text-slate-700';
};
</script>

<template>
    <div :class="[
        'group relative rounded-2xl p-4 sm:p-5 transition-all duration-300 border shadow-2xs hover:shadow-md flex flex-col gap-3 sm:gap-0 sm:flex-row sm:items-center sm:justify-between',
        isSelected ? 'bg-slate-50/90 border-slate-400 ring-1 ring-slate-400' : '',
        // 新着かつ点滅中（20秒以内）
        isHighlighted && isFlashing ? 'bg-amber-100/90 border-amber-400 animate-pulse shadow-md' : '',
        // 点滅終了後〜リフレッシュまでの常時ハイライト
        isHighlighted && !isFlashing ? 'bg-amber-50/90 border-amber-300 ring-2 ring-amber-200/50' : '',
        // 通常時
        !isHighlighted && !isSelected ? 'bg-white border-slate-200/80' : ''
    ]">
        <!-- メインコンテンツ（左側） -->
        <div class="flex items-start sm:items-center gap-3.5 min-w-0 flex-1">
            
            <!-- 一括選択モード時はチェックボックスを表示 -->
            <div v-if="isSelectionMode" class="flex items-center justify-center shrink-0 pt-0.5 sm:pt-0">
                <input 
                    type="checkbox" 
                    :checked="isSelected"
                    @change="$emit('select', task.id)"
                    class="rounded-lg border-slate-300 text-slate-900 focus:ring-slate-900 h-5 w-5 cursor-pointer transition shrink-0"
                />
            </div>

            <!-- 通常時はタスク完了ボタンを表示 -->
            <button 
                v-else
                @click="$emit('toggle', task)"
                :class="[
                    'w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all shrink-0 cursor-pointer active:scale-75 shadow-2xs mt-0.5 sm:mt-0',
                    task.is_completed 
                        ? 'bg-slate-900 border-slate-900 text-white shadow-xs' 
                        : 'border-slate-300 hover:border-slate-900 hover:bg-slate-50 bg-white text-transparent hover:text-slate-300'
                ]"
            >
                ✓
            </button>

            <div class="min-w-0 flex-1 space-y-1.5">
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button 
                        @click.stop="$emit('open-menu', task, 'category', $event)"
                        :class="['inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-xl border transition cursor-pointer active:scale-95 shadow-2xs', categoryTree[task.category]?.badgeClass || categoryTree.inbox.badgeClass]"
                    >
                        <span>{{ categoryTree[task.category]?.icon || '📥' }}</span>
                        <span class="text-slate-700">{{ categoryTree[task.category]?.label || '未分類' }}</span>
                        <span class="text-slate-300 font-light">/</span>
                        <span>{{ getSubCategoryMeta(task.category, task.sub_category).icon }}</span>
                        <span class="text-slate-600">{{ getSubCategoryMeta(task.category, task.sub_category).label }}</span>
                        <span class="text-[9px] ml-0.5 text-slate-400 opacity-70">▼</span>
                    </button>
                </div>

                <div v-if="editingTaskId === task.id">
                    <input 
                        type="text" 
                        v-model="editingTitle" 
                        @keyup.enter="saveEdit(task)"
                        @keyup.esc="cancelEdit"
                        @blur="saveEdit(task)"
                        autofocus
                        class="w-full text-xs sm:text-sm font-bold border-slate-900 rounded-xl py-1.5 px-3 text-slate-900 shadow-inner bg-white"
                    />
                </div>
                <div 
                    v-else 
                    @click="startEdit(task)"
                    class="text-xs sm:text-sm font-bold cursor-pointer leading-relaxed tracking-tight text-slate-900 hover:text-indigo-600 transition py-0.5 break-words"
                >
                    {{ task.title }}
                </div>
            </div>
        </div>

        <!-- アクションボタン・メタデータ（スマホではカードフッター風、PCでは右寄せ） -->
        <div class="flex items-center justify-between sm:justify-end gap-2 pt-2.5 mt-1 border-t border-slate-100 sm:border-t-0 sm:pt-0 sm:mt-0 shrink-0">
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <button 
                    @click.stop="$emit('open-menu', task, 'priority', $event)"
                    :class="['px-3 py-1.5 rounded-xl transition cursor-pointer flex items-center gap-1.5 active:scale-95 shadow-2xs border text-[11px] font-bold', priorityConfig[task.priority]?.badgeClass || priorityConfig.medium.badgeClass]"
                >
                    <span>⚡</span>
                    <span>{{ priorityConfig[task.priority]?.label }}</span>
                    <span class="text-[9px] opacity-60">▼</span>
                </button>

                <button 
                    @click.stop="$emit('open-menu', task, 'due', $event)"
                    :class="['border rounded-xl px-3 py-1.5 transition flex items-center gap-1.5 cursor-pointer font-bold active:scale-95 shadow-2xs text-[11px]', getDueDateBadgeClass(task.due_date, task.is_completed)]"
                >
                    <span>📅</span>
                    <span>{{ task.due_date }}</span>
                    <span class="text-[9px] opacity-60">▼</span>
                </button>
            </div>

            <button 
                @click="$emit('delete', task)"
                class="text-slate-300 hover:text-rose-600 sm:opacity-0 sm:group-hover:opacity-100 transition p-2 cursor-pointer rounded-xl hover:bg-rose-50"
            >
                ✕
            </button>
        </div>
    </div>
</template>
```

## File: resources/js/Constants/task.js
```javascript
export const categoryTree = {
    inbox: {
        label: '未分類',
        icon: '📥',
        badgeClass: 'bg-slate-100 text-slate-700 border-slate-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他', icon: '📝' },
        ]
    },
    work: {
        label: '仕事',
        icon: '💼',
        badgeClass: 'bg-blue-50 text-blue-700 border-blue-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'project', label: 'プロジェクト・業務', icon: '📊' },
            { key: 'meeting', label: 'ミーティング・商談', icon: '🤝' },
            { key: 'task', label: '通常タスク・作業', icon: '💻' },
            { key: 'admin', label: '事務・管理', icon: '📁' },
        ]
    },
    personal: {
        label: 'プライベート',
        icon: '🏠',
        badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'shopping', label: '買い物・購入', icon: '🛒' },
            { key: 'housework', label: '家事・用事', icon: '🧹' },
            { key: 'family', label: '家族・友人', icon: '👨‍👩‍👦' },
            { key: 'event', label: 'イベント・予定', icon: '🎉' },
        ]
    },
    growth: {
        label: '学習・成長',
        icon: '📚',
        badgeClass: 'bg-purple-50 text-purple-700 border-purple-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'study', label: '学習・スキルアップ', icon: '🎓' },
            { key: 'reading', label: '読書・インプット', icon: '📖' },
            { key: 'goal', label: '目標・計画', icon: '🎯' },
        ]
    },
    health: {
        label: '健康',
        icon: '🏃',
        badgeClass: 'bg-rose-50 text-rose-700 border-rose-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'fitness', label: '運動・フィットネス', icon: '💪' },
            { key: 'medical', label: '医療・健康管理', icon: '🏥' },
            { key: 'mental', label: 'メンタルケア・休息', icon: '🍵' },
        ]
    },
    finance: {
        label: 'お金・手続き',
        icon: '💳',
        badgeClass: 'bg-amber-50 text-amber-700 border-amber-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'payment', label: '支払い・請求', icon: '🧾' },
            { key: 'procedure', label: '手続き・行政', icon: '🏛️' },
            { key: 'asset', label: '資産管理・投資', icon: '📈' },
        ]
    }
};

export const categoryOrder = ['inbox', 'work', 'personal', 'growth', 'health', 'finance'];

export const priorityConfig = {
    high: {
        label: '高',
        badgeClass: 'bg-rose-50 text-rose-700 border-rose-200',
    },
    medium: {
        label: '中',
        badgeClass: 'bg-amber-50 text-amber-700 border-amber-200',
    },
    low: {
        label: '低',
        badgeClass: 'bg-slate-50 text-slate-700 border-slate-200',
    },
};
```

## File: resources/js/Pages/Tasks/Index.vue
```vue
<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useForm, router, Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { categoryTree } from '@/Constants/task';

// 分割したコンポーネントのインポート（PC用とスマホ用で分離）
import DesktopSidebar from '@/Components/Tasks/DesktopSidebar.vue';
import MobileDrawer from '@/Components/Tasks/MobileDrawer.vue';
import TaskItem from '@/Components/Tasks/TaskItem.vue';
import TaskFormModal from '@/Components/Tasks/TaskFormModal.vue';
import TaskActionModal from '@/Components/Tasks/TaskActionModal.vue';
import BulkActionBar from '@/Components/Tasks/BulkActionBar.vue';
import MobileNav from '@/Components/Tasks/MobileNav.vue';

const props = defineProps({
    tasks: Array,          
    filteredTasks: Array,  
    currentCategory: String, 
});

// --- 新着タスクのハイライト・点滅管理 ---
const page = usePage();
const newIds = ref([]);
const blinkingMap = ref({});

const handleFlashNewTasks = () => {
    const flashIds = page.props.flash?.new_task_ids;
    if (Array.isArray(flashIds) && flashIds.length > 0) {
        newIds.value = flashIds;
        flashIds.forEach(id => {
            blinkingMap.value[id] = true;
            setTimeout(() => {
                blinkingMap.value[id] = false;
            }, 20000);
        });
    }
};

onMounted(() => {
    handleFlashNewTasks();
});

watch(() => page.props.flash?.new_task_ids, (newVal) => {
    if (newVal) {
        handleFlashNewTasks();
    }
}, { deep: true });
// ------------------------------------

const todayStr = new Date().toISOString().split('T')[0];
const activeCategoryKey = ['all', 'today'].includes(props.currentCategory) ? 'inbox' : props.currentCategory;
const defaultSubKey = categoryTree[activeCategoryKey]?.defaultSub || 'general';

// 新規作成フォーム
const form = useForm({
    title: '',
    due_date: todayStr,
    category: activeCategoryKey,
    sub_category: defaultSubKey,
    priority: 'medium',
});

// UI制御の状態
const isSidebarOpen = ref(false);
const isTaskModalOpen = ref(false);
const sortBy = ref('default');
const searchQuery = ref('');
const isSelectionMode = ref(false);
const selectedTaskIds = ref([]);

// モバイル用・モーダル制御など
const activeMenuTask = ref(null);
const activeMenuType = ref(null);

const openMenuModal = (task, type, event) => {
    if (event) event.stopPropagation();
    activeMenuTask.value = task;
    activeMenuType.value = type;
};

const closeMenuModal = () => {
    activeMenuTask.value = null;
    activeMenuType.value = null;
};

// フィルタリングと並び替え
const filteredAndSortedTasks = computed(() => {
    let tasksToSort = [...props.filteredTasks];
    if (searchQuery.value.trim()) {
        const query = searchQuery.value.toLowerCase();
        tasksToSort = tasksToSort.filter(t => t.title.toLowerCase().includes(query));
    }
    return tasksToSort.sort((a, b) => {
        if (sortBy.value === 'due_date') {
            if (!a.due_date) return 1;
            if (!b.due_date) return -1;
            return a.due_date.localeCompare(b.due_date);
        } else if (sortBy.value === 'priority') {
            const weights = { high: 3, medium: 2, low: 1 };
            return (weights[b.priority] || 2) - (weights[a.priority] || 2);
        } else if (sortBy.value === 'oldest') {
            return a.id - b.id;
        } else {
            return b.id - a.id;
        }
    });
});

const activeTasks = computed(() => filteredAndSortedTasks.value.filter(t => !t.is_completed));
const completedTasksList = computed(() => filteredAndSortedTasks.value.filter(t => t.is_completed));

// 選択モード関連
const toggleSelectionMode = () => {
    isSelectionMode.value = !isSelectionMode.value;
    if (!isSelectionMode.value) selectedTaskIds.value = [];
};

const toggleTaskSelection = (taskId) => {
    const idx = selectedTaskIds.value.indexOf(taskId);
    if (idx > -1) selectedTaskIds.value.splice(idx, 1);
    else selectedTaskIds.value.push(taskId);
};

const toggleSelectAll = () => {
    const activeIds = activeTasks.value.map(t => t.id);
    const allSelected = activeIds.every(id => selectedTaskIds.value.includes(id));
    
    if (allSelected) {
        selectedTaskIds.value = selectedTaskIds.value.filter(id => !activeIds.includes(id));
    } else {
        const combined = new Set([...selectedTaskIds.value, ...activeIds]);
        selectedTaskIds.value = Array.from(combined);
    }
};

// 一括操作
const bulkUpdate = (payload, successMessage) => {
    if (selectedTaskIds.value.length === 0) return;
    
    router.patch(route('tasks.bulk-update'), {
        ids: selectedTaskIds.value,
        ...payload
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showToast(successMessage);
            selectedTaskIds.value = [];
            isSelectionMode.value = false;
            closeMenuModal();
        }
    });
};

const bulkComplete = (isCompleted) => bulkUpdate({ is_completed: isCompleted }, '選択したタスクの状態を更新しました');
const bulkUpdateDueDate = (dueDate) => bulkUpdate({ due_date: dueDate }, '期限を一括更新しました');
const bulkUpdateCategoryAndSub = (category, subCategory) => bulkUpdate({ category, sub_category: subCategory }, 'カテゴリを一括変更しました');
const bulkUpdatePriority = (priority) => bulkUpdate({ priority }, '優先度を一括変更しました');

const bulkDelete = () => {
    if (!confirm('選択したタスクを削除しますか？')) return;
    
    router.delete(route('tasks.bulk-destroy'), {
        data: { ids: selectedTaskIds.value },
        preserveScroll: true,
        onSuccess: () => {
            showToast('選択したタスクを削除しました');
            selectedTaskIds.value = [];
            isSelectionMode.value = false;
        }
    });
};

// 個別アクション
const submitTask = () => {
    if (!form.title.trim()) return;
    form.post(route('tasks.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast('タスクを追加しました');
            form.reset('title');
            isTaskModalOpen.value = false;
        }
    });
};

const toggleTask = (task) => {
    router.patch(route('tasks.update', task.id), { is_completed: !task.is_completed }, { preserveScroll: true });
};

const updateTitle = (task, title) => {
    router.patch(route('tasks.update', task.id), { title }, { preserveScroll: true });
};

const updateCategoryAndSub = (task, category, subCategory) => {
    router.patch(route('tasks.update', task.id), { category, sub_category: subCategory }, { preserveScroll: true });
    closeMenuModal();
};

const updatePriority = (task, priority) => {
    router.patch(route('tasks.update', task.id), { priority }, { preserveScroll: true });
    closeMenuModal();
};

const updateDueDate = (task, due_date) => {
    router.patch(route('tasks.update', task.id), { due_date }, { preserveScroll: true });
    closeMenuModal();
};

const deleteTask = (task) => {
    router.delete(route('tasks.destroy', task.id), { preserveScroll: true });
};

// トースト通知
const toastMessage = ref(null);
const showToast = (msg) => {
    toastMessage.value = msg;
    setTimeout(() => { toastMessage.value = null; }, 4000);
};
</script>

<template>
    <Head title="Tasks Dashboard" />

    <AuthenticatedLayout>
        <!-- トースト通知 -->
        <Transition name="slide-up">
            <div v-if="toastMessage" class="fixed top-16 right-4 z-50 bg-slate-900 text-white text-xs px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 border border-slate-700">
                <span>✨</span>
                <span>{{ toastMessage }}</span>
            </div>
        </Transition>

        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span>📋</span> タスクダッシュボード
                </h2>
                <div class="flex items-center gap-2">
                    <button 
                        @click="isSidebarOpen = true" 
                        class="lg:hidden p-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 text-xs font-bold shadow-xs active:scale-95 cursor-pointer"
                    >
                        📂 メニュー
                    </button>
                    <button 
                        @click="isTaskModalOpen = true" 
                        class="px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold shadow-md transition active:scale-95 cursor-pointer flex items-center gap-1.5"
                    >
                        <span>+</span> 新規タスク
                    </button>
                </div>
            </div>
        </template>

        <!-- 全体のボリュームと余白バランスを調整 -->
        <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 lg:pb-12">
            <div class="flex gap-8 items-start">
                
                <!-- PC用サイドバー（専用デザイン） -->
                <DesktopSidebar 
                    :tasks="tasks" 
                    :current-category="currentCategory" 
                    :today-str="todayStr" 
                />

                <!-- メインコンテンツ領域 -->
                <div class="flex-1 min-w-0 space-y-4">
                    
                    <!-- コントロールバー -->
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-4 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="w-full sm:w-72 relative">
                            <input 
                                type="text" 
                                v-model="searchQuery" 
                                placeholder="タスクを検索..." 
                                class="w-full bg-slate-50 border border-slate-200 text-xs rounded-2xl pl-9 pr-3 py-2.5 text-slate-900 focus:bg-white transition shadow-inner"
                            />
                            <span class="absolute left-3 top-3 text-slate-400 text-xs">🔍</span>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end flex-wrap">
                            <button 
                                v-if="isSelectionMode"
                                @click="toggleSelectAll"
                                class="text-xs px-3.5 py-2.5 font-bold rounded-2xl border bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-200 transition active:scale-95 shadow-2xs cursor-pointer"
                            >
                                {{ activeTasks.every(t => selectedTaskIds.includes(t.id)) && activeTasks.length > 0 ? '選択をすべて解除' : 'すべて選択' }}
                            </button>

                            <select 
                                v-model="sortBy" 
                                class="text-xs bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2.5 text-slate-700 cursor-pointer shadow-inner"
                            >
                                <option value="default">並び替え: 新着順</option>
                                <option value="oldest">並び替え: 過去順</option>
                                <option value="due_date">並び替え: 期限順</option>
                                <option value="priority">並び替え: 重要度順</option>
                            </select>

                            <button 
                                @click="toggleSelectionMode" 
                                :class="['text-xs px-3.5 py-2.5 font-bold rounded-2xl border transition active:scale-95 shadow-2xs cursor-pointer', isSelectionMode ? 'bg-slate-900 text-white border-slate-900' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100']"
                            >
                                {{ isSelectionMode ? '選択モード解除' : '一括選択' }}
                            </button>
                        </div>
                    </div>

                    <!-- アクティブタスク一覧 -->
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-sm space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-400 px-1 pb-1">
                            <span>タスク一覧 ({{ activeTasks.length }})</span>
                        </div>

                        <div v-if="activeTasks.length === 0" class="text-center py-16 text-slate-400 text-xs font-medium space-y-1">
                            <p class="text-base">🎉</p>
                            <p>表示するタスクはありません</p>
                        </div>

                        <div class="space-y-2.5">
                            <TaskItem 
                                v-for="task in activeTasks" 
                                :key="task.id"
                                :task="task"
                                :is-selection-mode="isSelectionMode"
                                :is-selected="selectedTaskIds.includes(task.id)"
                                :is-highlighted="newIds.includes(task.id)"
                                :is-flashing="blinkingMap[task.id]"
                                @toggle="toggleTask"
                                @select="toggleTaskSelection"
                                @delete="deleteTask"
                                @update-title="updateTitle"
                                @open-menu="openMenuModal"
                            />
                        </div>
                    </div>

                    <!-- 完了済みタスク一覧 -->
                    <div v-if="completedTasksList.length > 0" class="bg-white border border-slate-200/80 rounded-3xl p-5 sm:p-6 shadow-sm space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-400 px-1">
                            <span>完了済み ({{ completedTasksList.length }})</span>
                        </div>
                        <div class="space-y-2.5 opacity-75">
                            <TaskItem 
                                v-for="task in completedTasksList" 
                                :key="task.id"
                                :task="task"
                                :is-selection-mode="isSelectionMode"
                                :is-selected="selectedTaskIds.includes(task.id)"
                                :is-highlighted="newIds.includes(task.id)"
                                :is-flashing="blinkingMap[task.id]"
                                @toggle="toggleTask"
                                @select="toggleTaskSelection"
                                @delete="deleteTask"
                                @update-title="updateTitle"
                                @open-menu="openMenuModal"
                            />
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- モバイル用サイドバードロワー（スマホ専用のタイルレイアウト） -->
        <Transition name="slide-up">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs lg:hidden flex items-end">
                <div @click.stop class="bg-white w-full rounded-t-3xl p-6 space-y-4 max-h-[85vh] overflow-y-auto">
                    <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto"></div>
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <span class="text-sm font-bold text-slate-900">メニュー</span>
                        <button @click="isSidebarOpen = false" class="text-slate-400 font-bold text-xs p-1 cursor-pointer">✕</button>
                    </div>
                    <MobileDrawer 
                        :tasks="tasks" 
                        :current-category="currentCategory" 
                        :today-str="todayStr" 
                        @close="isSidebarOpen = false"
                    />
                </div>
            </div>
        </Transition>

        <!-- 新規作成モーダル -->
        <TaskFormModal 
            :is-open="isTaskModalOpen"
            :form="form"
            @close="isTaskModalOpen = false"
            @submit="submitTask"
        />

        <!-- アクション・変更モーダル -->
        <TaskActionModal 
            :active-menu-task="activeMenuTask"
            :active-menu-type="activeMenuType"
            @close="closeMenuModal"
            @update-category="updateCategoryAndSub"
            @update-priority="updatePriority"
            @update-due="updateDueDate"
            @bulk-update-due="bulkUpdateDueDate"
            @bulk-update-category="bulkUpdateCategoryAndSub"
            @bulk-update-priority="bulkUpdatePriority"
        />

        <!-- 一括操作アクションバー -->
        <BulkActionBar 
            v-if="selectedTaskIds.length > 0"
            :selected-count="selectedTaskIds.length"
            @complete="bulkComplete(true)"
            @uncomplete="bulkComplete(false)"
            @open-due-modal="openMenuModal({ id: 'bulk' }, 'bulkDue', $event)"
            @open-category-modal="openMenuModal({ id: 'bulk' }, 'bulkCategory', $event)"
            @open-priority-modal="openMenuModal({ id: 'bulk' }, 'bulkPriority', $event)"
            @delete="bulkDelete"
            @clear="selectedTaskIds = []; isSelectionMode = false;"
        />

        <!-- モバイル下部ナビゲーション -->
        <MobileNav 
            :current-category="currentCategory"
            :today-count="tasks.filter(t => t.due_date === todayStr).length"
            :inbox-count="tasks.filter(t => t.category === 'inbox').length"
            @open-menu="isSidebarOpen = true"
            @open-task-modal="isTaskModalOpen = true"
        />

    </AuthenticatedLayout>
</template>
```

## File: resources/js/Pages/TaskGuide.vue
```vue
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <Head title="使い方ガイド" />

    <AuthenticatedLayout>
        <template #header>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <h2 class="font-semibold text-xl text-slate-900 tracking-tight flex items-center gap-2">
                    <span>📖</span> タスク管理の使い方ガイド
                </h2>
                <Link 
                    :href="route('dashboard')" 
                    class="text-xs bg-slate-900 text-white px-3.5 py-2 rounded-xl font-semibold hover:bg-slate-800 transition"
                >
                    ダッシュボードへ戻る
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <!-- イントロカード -->
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl p-6 sm:p-8 shadow-xl space-y-4">
                    <div class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-white/10 px-3 py-1 rounded-full text-amber-300">
                        <span>✨</span> 思考を解放するシンプルなタスク管理
                    </div>
                    <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                        頭の中のモヤモヤを形にして、<br>今日やるべきことに100%集中しよう。
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        このアプリは、思いついたタスクをまず「未分類」に素早く放り込み、後からカテゴリや重要度で整理してスムーズに消化するためのシステムです。以下の4ステップで、毎日のタスク管理を快適にしましょう。
                    </p>
                </div>

                <!-- 4つの基本ステップ -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider px-1">
                        4つの基本ステップ
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        
                        <!-- Step 1 -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-3 relative overflow-hidden group hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 font-mono">STEP 01</span>
                                <span class="text-xl">📥</span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">まず「未分類」へ一瞬で追加</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                やるべきことを思いついたら、思考を止めずにサイドバーの入力欄からパッと追加。脳のメモリを解放してストレスをゼロに。追加したタスクは少し濃いスレートグレーのカードで一覧に分かりやすく表示されます。
                            </p>
                        </div>

                        <!-- Step 2 -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-3 relative overflow-hidden group hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 font-mono">STEP 02</span>
                                <span class="text-xl">🏷️</span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">カテゴリ・サブカテゴリで整理</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                タスクカードのカテゴリ名をクリックすると、ポップオーバーメニューが開きます。「仕事」「プライベート」「学習」などの5大カテゴリや細かいサブカテゴリへ、一瞬でスマートに振り分け可能です。
                            </p>
                        </div>

                        <!-- Step 3 -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-3 relative overflow-hidden group hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 font-mono">STEP 03</span>
                                <span class="text-xl">⚡</span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">重要度と期限を設定する</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                重要度（高・中・低）や期限日をワンクリックで変更できます。重要度「高」のタスクは赤系のアクセントで目立ち、毎日の優先順位が一目でわかるようになります。
                            </p>
                        </div>

                        <!-- Step 4 -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-2xs space-y-3 relative overflow-hidden group hover:border-slate-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 font-mono">STEP 04</span>
                                <span class="text-xl">📅</span>
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">「今日」ビューで1日を駆け抜ける</h4>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                毎朝「今日」ビューを開いて、その日取り組むタスクだけに集中しましょう。タスクを完了したらチェックボックスをON！達成感とともにスマートにタスクを消化していきましょう。
                            </p>
                        </div>

                    </div>
                </div>

                <!-- 便利な機能・Tipsセクション -->
                <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 space-y-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                        💡 さらに使いこなすためのヒント
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-2">
                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                <span>✏️</span> タイトル編集
                            </div>
                            <p class="text-slate-600 leading-relaxed">
                                タスクのタイトルをクリックするだけで、その場で直接編集が可能です。
                            </p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-2">
                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                <span>🔄</span> 並び替え機能
                            </div>
                            <p class="text-slate-600 leading-relaxed">
                                「期限が近い順」「重要度が高い順」など、好みの順序に並び替えて効率よく作業できます。
                            </p>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-200 space-y-2">
                            <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                <span>✨</span> 追加時のハイライト
                            </div>
                            <p class="text-slate-600 leading-relaxed">
                                新しくタスクを追加した直後は、該当カードが自動でハイライトされて迷いません。
                            </p>
                        </div>
                    </div>
                </div>

                <!-- フッターアクション -->
                <div class="text-center pt-4">
                    <Link 
                        :href="route('dashboard')" 
                        class="inline-flex items-center gap-2 bg-slate-900 text-white font-semibold text-xs px-6 py-3 rounded-2xl hover:bg-slate-800 transition shadow-md"
                    >
                        <span>🚀</span> さあ、タスク管理をはじめよう！
                    </Link>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
```

## File: app/Http/Controllers/Auth/AuthenticatedSessionController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

## File: app/Http/Controllers/Auth/ConfirmablePasswordController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
```

## File: app/Http/Controllers/Auth/EmailVerificationNotificationController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
```

## File: app/Http/Controllers/Auth/EmailVerificationPromptController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : Inertia::render('Auth/VerifyEmail', ['status' => session('status')]);
    }
}
```

## File: app/Http/Controllers/Auth/NewPasswordController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
```

## File: app/Http/Controllers/Auth/PasswordController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
```

## File: app/Http/Controllers/Auth/PasswordResetLinkController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
```

## File: app/Http/Controllers/Auth/RegisteredUserController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
```

## File: app/Http/Controllers/Auth/VerifyEmailController.php
```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
```

## File: app/Http/Controllers/Controller.php
```php
<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
}
```

## File: app/Http/Controllers/ProfileController.php
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
```

## File: app/Http/Middleware/HandleInertiaRequests.php
```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'new_task_ids' => fn () => $request->session()->get('new_task_ids'),
            ],
        ];
    }
}
```

## File: app/Http/Requests/Auth/LoginRequest.php
```php
<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
```

## File: app/Http/Requests/ProfileUpdateRequest.php
```php
<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
```

## File: app/Models/Task.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_completed',
        'due_date',
        'category',
        'sub_category',
        'priority',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
}
```

## File: app/Models/User.php
```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // app/Models/User.php 内の適当なメソッドの下に追加
    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }
}
```

## File: app/Policies/TaskPolicy.php
```php
<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // 誰でも一覧画面にアクセスできるように true にします
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // 自分のタスクのみ詳細表示を許可します
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // ログインしていればタスク作成を許可します
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // 自分のタスクのみ更新を許可します
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // 自分のタスクのみ削除を許可します
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}
```

## File: app/Providers/AppServiceProvider.php
```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
```

## File: database/factories/UserFactory.php
```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

## File: database/seeders/DatabaseSeeder.php
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            TaskSeeder::class,
        ]);
    }
}
```

## File: database/.gitignore
```
*.sqlite*
```

## File: resources/css/app.css
```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@keyframes flashHighlight {
    0%, 100% { background-color: rgba(251, 191, 36, 0.2); }
    50% { background-color: rgba(251, 191, 36, 0.6); }
}

.task-highlight {
    animation: flashHighlight 1.5s ease-in-out infinite;
}

.task-solid-highlight {
    background-color: rgba(251, 191, 36, 0.15);
}

/* モーダル用下からのスライドアニメーション */
.slide-up-enter-active,
.slide-up-leave-active {
    transition: all 0.25s ease-out;
}

.slide-up-enter-from,
.slide-up-leave-to {
    opacity: 0;
    transform: translateY(20px);
}
```

## File: resources/js/Components/ApplicationLogo.vue
```vue
<template>
    <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125ZM144.2 227.205L100.57 202.515L146.39 176.135L196.66 147.195L240.33 172.335L208.29 190.625L144.2 227.205ZM244.75 114.995V164.795L226.39 154.225L201.03 139.625V89.825L219.39 100.395L244.75 114.995ZM249.12 57.105L292.81 82.265L249.12 107.425L205.43 82.265L249.12 57.105ZM114.49 184.425L96.13 194.995V85.305L121.49 70.705L139.85 60.135V169.815L114.49 184.425ZM91.76 27.425L135.45 52.585L91.76 77.745L48.07 52.585L91.76 27.425ZM43.67 60.135L62.03 70.705L87.39 85.305V202.545V202.555V202.565C87.39 202.735 87.44 202.895 87.46 203.055C87.49 203.265 87.49 203.485 87.55 203.695V203.705C87.6 203.875 87.69 204.035 87.76 204.195C87.84 204.375 87.89 204.575 87.99 204.745C87.99 204.745 87.99 204.755 88 204.755C88.09 204.905 88.22 205.035 88.33 205.175C88.45 205.335 88.55 205.495 88.69 205.635L88.7 205.645C88.82 205.765 88.98 205.855 89.12 205.965C89.28 206.085 89.42 206.225 89.59 206.325C89.6 206.325 89.6 206.325 89.61 206.335C89.62 206.335 89.62 206.345 89.63 206.345L139.87 234.775V285.065L43.67 229.705V60.135ZM244.75 229.705L148.58 285.075V234.775L219.8 194.115L244.75 179.875V229.705ZM297.2 139.625L253.49 164.795V114.995L278.85 100.395L297.21 89.825V139.625H297.2Z"
        />
    </svg>
</template>
```

## File: resources/js/Components/Checkbox.vue
```vue
<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
```

## File: resources/js/Components/DangerButton.vue
```vue
<template>
    <button
        class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-700"
    >
        <slot />
    </button>
</template>
```

## File: resources/js/Components/Dropdown.vue
```vue
<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
});

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (props.align === 'right') {
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    } else {
        return 'origin-top';
    }
});

const open = ref(false);
</script>

<template>
    <div class="relative">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div
            v-show="open"
            class="fixed inset-0 z-40"
            @click="open = false"
        ></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-md shadow-lg"
                :class="[widthClass, alignmentClasses]"
                style="display: none"
                @click="open = false"
            >
                <div
                    class="rounded-md ring-1 ring-black ring-opacity-5"
                    :class="contentClasses"
                >
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
```

## File: resources/js/Components/DropdownLink.vue
```vue
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
    >
        <slot />
    </Link>
</template>
```

## File: resources/js/Components/InputError.vue
```vue
<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
</template>
```

## File: resources/js/Components/InputLabel.vue
```vue
<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block text-sm font-medium text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
```

## File: resources/js/Components/Modal.vue
```vue
<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            showSlot.value = true;

            dialog.value?.showModal();
        } else {
            document.body.style.overflow = '';

            setTimeout(() => {
                dialog.value?.close();
                showSlot.value = false;
            }, 200);
        }
    },
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        e.preventDefault();

        if (props.show) {
            close();
        }
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);

    document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <dialog
        class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        ref="dialog"
    >
        <div
            class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
            scroll-region
        >
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-show="show"
                    class="fixed inset-0 transform transition-all"
                    @click="close"
                >
                    <div
                        class="absolute inset-0 bg-gray-500 opacity-75"
                    />
                </div>
            </Transition>

            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div
                    v-show="show"
                    class="mb-6 transform overflow-hidden rounded-lg bg-white shadow-xl transition-all sm:mx-auto sm:w-full"
                    :class="maxWidthClass"
                >
                    <slot v-if="showSlot" />
                </div>
            </Transition>
        </div>
    </dialog>
</template>
```

## File: resources/js/Components/NavLink.vue
```vue
<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
```

## File: resources/js/Components/PrimaryButton.vue
```vue
<template>
    <button
        class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900"
    >
        <slot />
    </button>
</template>
```

## File: resources/js/Components/ResponsiveNavLink.vue
```vue
<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
```

## File: resources/js/Components/SecondaryButton.vue
```vue
<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
```

## File: resources/js/Components/TextInput.vue
```vue
<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="input"
    />
</template>
```

## File: resources/js/Layouts/AuthenticatedLayout.vue
```vue
<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50/50 text-slate-900 font-sans antialiased selection:bg-slate-900 selection:text-white">
        
        <!-- ▼ ナビゲーションとページヘッダーをひとまとめにして固定するラッパー -->
        <div class="sticky top-0 z-40">
            <!-- ナビゲーションバー -->
            <nav class="bg-white/85 backdrop-blur-md border-b border-slate-200/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center gap-6">
                            <!-- ロゴ / アプリ名 -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="font-bold text-base text-slate-900 tracking-tight flex items-center gap-2">
                                    <span class="text-xl">🧩</span> 
                                    <span>Tasks</span>
                                </Link>
                            </div>

                            <!-- ナビゲーションリンク -->
                            <div class="hidden sm:flex sm:items-center sm:gap-1.5">
                                <Link 
                                    :href="route('dashboard')" 
                                    :class="[
                                        'px-3.5 py-2 rounded-xl text-xs font-semibold transition',
                                        route().current('dashboard*') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'
                                    ]"
                                >
                                    ダッシュボード
                                </Link>
                                <Link 
                                    :href="route('tasks.guide')" 
                                    :class="[
                                        'px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center gap-1.5',
                                        route().current('tasks.guide') ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'
                                    ]"
                                >
                                    <span>📖</span> 使い方ガイド
                                </Link>
                            </div>
                        </div>

                        <!-- ユーザー情報・ログアウト -->
                        <div class="hidden sm:flex sm:items-center sm:gap-3">
                            <div class="text-xs font-semibold text-slate-700 bg-slate-100/80 px-3 py-1.5 rounded-xl border border-slate-200/60">
                                {{ $page.props.auth.user.name }} さん
                            </div>
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button" 
                                class="text-xs font-semibold text-slate-600 hover:text-rose-600 px-3.5 py-2 rounded-xl hover:bg-rose-50 transition"
                            >
                                ログアウト
                            </Link>
                        </div>

                        <!-- ハンバーガーメニュー（スマホ用） -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button 
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- スマホ用ドロップダウンメニュー -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="sm:hidden border-t border-slate-200/80 bg-white px-4 pt-3 pb-4 space-y-2">
                    <Link 
                        :href="route('dashboard')" 
                        class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition"
                    >
                        ダッシュボード
                    </Link>
                    <Link 
                        :href="route('tasks.guide')" 
                        class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition flex items-center gap-2"
                    >
                        <span>📖</span> 使い方ガイド
                    </Link>
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between px-3">
                        <span class="text-xs font-semibold text-slate-700">{{ $page.props.auth.user.name }} さん</span>
                        <Link 
                            :href="route('logout')" 
                            method="post" 
                            as="button" 
                            class="text-xs font-semibold text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-xl transition"
                        >
                            ログアウト
                        </Link>
                    </div>
                </div>
            </nav>

            <!-- ページヘッダー -->
            <header v-if="$slots.header" class="bg-white/85 backdrop-blur-md border-b border-slate-200/60 py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
        </div>
        <!-- ▲ 固定ラッパーここまで -->

        <!-- メインコンテンツ -->
        <main>
            <slot />
        </main>
    </div>
</template>
```

## File: resources/js/Layouts/GuestLayout.vue
```vue
<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
```

## File: resources/js/Pages/Auth/ConfirmPassword.vue
```vue
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex justify-end">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
```

## File: resources/js/Pages/Auth/ForgotPassword.vue
```vue
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email
            address and we will email you a password reset link that will allow
            you to choose a new one.
        </div>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
```

## File: resources/js/Pages/Auth/Login.vue
```vue
<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
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
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
```

## File: resources/js/Pages/Auth/Register.vue
```vue
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
```

## File: resources/js/Pages/Auth/ResetPassword.vue
```vue
<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
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
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
```

## File: resources/js/Pages/Auth/VerifyEmail.vue
```vue
<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >Log Out</Link
                >
            </div>
        </form>
    </GuestLayout>
</template>
```

## File: resources/js/Pages/Profile/Partials/DeleteUserForm.vue
```vue
<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900"
                >
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
```

## File: resources/js/Pages/Profile/Partials/UpdatePasswordForm.vue
```vue
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Update Password
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Ensure your account is using a long, random password to stay
                secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <InputError
                    :message="form.errors.current_password"
                    class="mt-2"
                />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
```

## File: resources/js/Pages/Profile/Partials/UpdateProfileInformationForm.vue
```vue
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
```

## File: resources/js/Pages/Profile/Edit.vue
```vue
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
```

## File: resources/js/Pages/Tasks/Create.vue
```vue
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
```

## File: resources/js/Pages/Welcome.vue
```vue
<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img
            id="background"
            class="absolute -left-20 top-0 max-w-[877px]"
            src="https://laravel.com/assets/img/welcome/background.svg"
        />
        <div
            class="relative flex min-h-screen flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header
                    class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3"
                >
                    <div class="flex lg:col-start-2 lg:justify-center">
                        <svg
                            class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]"
                            viewBox="0 0 62 65"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.70891 0.105946 7.65531 0.133367 7.6067C0.152063 7.5743 0.179485 7.54812 0.20192 7.51821C0.230588 7.47832 0.256763 7.43719 0.290416 7.40229C0.319084 7.37362 0.356476 7.35243 0.388883 7.32751C0.425029 7.29759 0.457436 7.26518 0.498568 7.2415L12.4779 0.345059C12.6296 0.257786 12.8015 0.211853 12.9765 0.211853C13.1515 0.211853 13.3234 0.257786 13.475 0.345059L25.4531 7.2415H25.4556C25.4955 7.26643 25.5292 7.29759 25.5653 7.32626C25.5977 7.35119 25.6339 7.37362 25.6625 7.40104C25.6974 7.43719 25.7224 7.47832 25.7523 7.51821C25.7735 7.54812 25.8021 7.5743 25.8196 7.6067C25.8483 7.65656 25.8645 7.70891 25.8844 7.76126C25.8944 7.78993 25.9118 7.8161 25.9193 7.84602C25.9423 7.93096 25.954 8.01853 25.9542 8.10652V33.7317L35.9355 27.9844V14.8846C35.9355 14.7973 35.948 14.7088 35.9704 14.6253C35.9792 14.5954 35.9954 14.5692 36.0053 14.5405C36.0253 14.4882 36.0427 14.4346 36.0702 14.386C36.0888 14.3536 36.1163 14.3274 36.1375 14.2975C36.1674 14.2576 36.1923 14.2165 36.2272 14.1816C36.2559 14.1529 36.292 14.1317 36.3244 14.1068C36.3618 14.0769 36.3942 14.0445 36.4341 14.0208L48.4147 7.12434C48.5663 7.03694 48.7383 6.99094 48.9133 6.99094C49.0883 6.99094 49.2602 7.03694 49.4118 7.12434L61.3899 14.0208C61.4323 14.0457 61.4647 14.0769 61.5021 14.1055C61.5333 14.1305 61.5694 14.1529 61.5981 14.1803C61.633 14.2165 61.6579 14.2576 61.6878 14.2975C61.7103 14.3274 61.7377 14.3536 61.7551 14.386C61.7838 14.4346 61.8 14.4882 61.8199 14.5405C61.8312 14.5692 61.8474 14.5954 61.8548 14.6253ZM59.893 27.9844V16.6121L55.7013 19.0252L49.9104 22.3593V33.7317L59.8942 27.9844H59.893ZM47.9149 48.5566V37.1768L42.2187 40.4299L25.953 49.7133V61.2003L47.9149 48.5566ZM1.99677 9.83281V48.5566L23.9562 61.199V49.7145L12.4841 43.2219L12.4804 43.2194L12.4754 43.2169C12.4368 43.1945 12.4044 43.1621 12.3682 43.1347C12.3371 43.1097 12.3009 43.0898 12.2735 43.0624L12.271 43.0586C12.2386 43.0275 12.2162 42.9888 12.1887 42.9539C12.1638 42.9203 12.1339 42.8916 12.114 42.8567L12.1127 42.853C12.0903 42.8156 12.0766 42.7707 12.0604 42.7283C12.0442 42.6909 12.023 42.656 12.013 42.6161C12.0005 42.5688 11.998 42.5177 11.9931 42.4691C11.9881 42.4317 11.9781 42.3943 11.9781 42.3569V15.5801L6.18848 12.2446L1.99677 9.83281ZM12.9777 2.36177L2.99764 8.10652L12.9752 13.8513L22.9541 8.10527L12.9752 2.36177H12.9777ZM18.1678 38.2138L23.9574 34.8809V9.83281L19.7657 12.2459L13.9749 15.5801V40.6281L18.1678 38.2138ZM48.9133 9.14105L38.9344 14.8858L48.9133 20.6305L58.8909 14.8846L48.9133 9.14105ZM47.9149 22.3593L42.124 19.0252L37.9323 16.6121V27.9844L43.7219 31.3174L47.9149 33.7317V22.3593ZM24.9533 47.987L39.59 39.631L46.9065 35.4555L36.9352 29.7145L25.4544 36.3242L14.9907 42.3482L24.9533 47.987Z"
                                fill="currentColor"
                            />
                        </svg>
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Register
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <a
                            href="https://laravel.com/docs"
                            id="docs-card"
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div
                                id="screenshot-container"
                                class="relative flex w-full flex-1 items-stretch"
                            >
                                <img
                                    src="https://laravel.com/assets/img/welcome/docs-light.svg"
                                    alt="Laravel documentation screenshot"
                                    class="aspect-video h-full w-full flex-1 rounded-[10px] object-cover object-top drop-shadow-[0px_4px_34px_rgba(0,0,0,0.06)] dark:hidden"
                                    @error="handleImageError"
                                />
                                <img
                                    src="https://laravel.com/assets/img/welcome/docs-dark.svg"
                                    alt="Laravel documentation screenshot"
                                    class="hidden aspect-video h-full w-full flex-1 rounded-[10px] object-cover object-top drop-shadow-[0px_4px_34px_rgba(0,0,0,0.25)] dark:block"
                                />
                                <div
                                    class="absolute -bottom-16 -left-16 h-40 w-[calc(100%+8rem)] bg-gradient-to-b from-transparent via-white to-white dark:via-zinc-900 dark:to-zinc-900"
                                ></div>
                            </div>

                            <div
                                class="relative flex items-center gap-6 lg:items-end"
                            >
                                <div
                                    id="docs-card-content"
                                    class="flex items-start gap-6 lg:flex-col"
                                >
                                    <div
                                        class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16"
                                    >
                                        <svg
                                            class="size-5 sm:size-6"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                fill="#FF2D20"
                                                d="M23 4a1 1 0 0 0-1.447-.894L12.224 7.77a.5.5 0 0 1-.448 0L2.447 3.106A1 1 0 0 0 1 4v13.382a1.99 1.99 0 0 0 1.105 1.79l9.448 4.728c.14.065.293.1.447.1.154-.005.306-.04.447-.105l9.453-4.724a1.99 1.99 0 0 0 1.1-1.789V4ZM3 6.023a.25.25 0 0 1 .362-.223l7.5 3.75a.251.251 0 0 1 .138.223v11.2a.25.25 0 0 1-.362.224l-7.5-3.75a.25.25 0 0 1-.138-.22V6.023Zm18 11.2a.25.25 0 0 1-.138.224l-7.5 3.75a.249.249 0 0 1-.329-.099.249.249 0 0 1-.033-.12V9.772a.251.251 0 0 1 .138-.224l7.5-3.75a.25.25 0 0 1 .362.224v11.2Z"
                                            />
                                            <path
                                                fill="#FF2D20"
                                                d="m3.55 1.893 8 4.048a1.008 1.008 0 0 0 .9 0l8-4.048a1 1 0 0 0-.9-1.785l-7.322 3.706a.506.506 0 0 1-.452 0L4.454.108a1 1 0 0 0-.9 1.785H3.55Z"
                                            />
                                        </svg>
                                    </div>

                                    <div class="pt-3 sm:pt-5 lg:pt-0">
                                        <h2
                                            class="text-xl font-semibold text-black dark:text-white"
                                        >
                                            Documentation
                                        </h2>

                                        <p class="mt-4 text-sm/relaxed">
                                            Laravel has wonderful documentation
                                            covering every aspect of the
                                            framework. Whether you are a
                                            newcomer or have prior experience
                                            with Laravel, we recommend reading
                                            our documentation from beginning to
                                            end.
                                        </p>
                                    </div>
                                </div>

                                <svg
                                    class="size-6 shrink-0 stroke-[#FF2D20]"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                    />
                                </svg>
                            </div>
                        </a>

                        <a
                            href="https://laracasts.com"
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div
                                class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16"
                            >
                                <svg
                                    class="size-5 sm:size-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <g fill="#FF2D20">
                                        <path
                                            d="M24 8.25a.5.5 0 0 0-.5-.5H.5a.5.5 0 0 0-.5.5v12a2.5 2.5 0 0 0 2.5 2.5h19a2.5 2.5 0 0 0 2.5-2.5v-12Zm-7.765 5.868a1.221 1.221 0 0 1 0 2.264l-6.626 2.776A1.153 1.153 0 0 1 8 18.123v-5.746a1.151 1.151 0 0 1 1.609-1.035l6.626 2.776ZM19.564 1.677a.25.25 0 0 0-.177-.427H15.6a.106.106 0 0 0-.072.03l-4.54 4.543a.25.25 0 0 0 .177.427h3.783c.027 0 .054-.01.073-.03l4.543-4.543ZM22.071 1.318a.047.047 0 0 0-.045.013l-4.492 4.492a.249.249 0 0 0 .038.385.25.25 0 0 0 .14.042h5.784a.5.5 0 0 0 .5-.5v-2a2.5 2.5 0 0 0-1.925-2.432ZM13.014 1.677a.25.25 0 0 0-.178-.427H9.101a.106.106 0 0 0-.073.03l-4.54 4.543a.25.25 0 0 0 .177.427H8.4a.106.106 0 0 0 .073-.03l4.54-4.543ZM6.513 1.677a.25.25 0 0 0-.177-.427H2.5A2.5 2.5 0 0 0 0 3.75v2a.5.5 0 0 0 .5.5h1.4a.106.106 0 0 0 .073-.03l4.54-4.543Z"
                                        />
                                    </g>
                                </svg>
                            </div>

                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-black dark:text-white"
                                >
                                    Laracasts
                                </h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Laracasts offers thousands of video
                                    tutorials on Laravel, PHP, and JavaScript
                                    development. Check them out, see for
                                    yourself, and massively level up your
                                    development skills in the process.
                                </p>
                            </div>

                            <svg
                                class="size-6 shrink-0 self-center stroke-[#FF2D20]"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                />
                            </svg>
                        </a>

                        <a
                            href="https://laravel-news.com"
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div
                                class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16"
                            >
                                <svg
                                    class="size-5 sm:size-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <g fill="#FF2D20">
                                        <path
                                            d="M8.75 4.5H5.5c-.69 0-1.25.56-1.25 1.25v4.75c0 .69.56 1.25 1.25 1.25h3.25c.69 0 1.25-.56 1.25-1.25V5.75c0-.69-.56-1.25-1.25-1.25Z"
                                        />
                                        <path
                                            d="M24 10a3 3 0 0 0-3-3h-2V2.5a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2V20a3.5 3.5 0 0 0 3.5 3.5h17A3.5 3.5 0 0 0 24 20V10ZM3.5 21.5A1.5 1.5 0 0 1 2 20V3a.5.5 0 0 1 .5-.5h14a.5.5 0 0 1 .5.5v17c0 .295.037.588.11.874a.5.5 0 0 1-.484.625L3.5 21.5ZM22 20a1.5 1.5 0 1 1-3 0V9.5a.5.5 0 0 1 .5-.5H21a1 1 0 0 1 1 1v10Z"
                                        />
                                        <path
                                            d="M12.751 6.047h2a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-2A.75.75 0 0 1 12 7.3v-.5a.75.75 0 0 1 .751-.753ZM12.751 10.047h2a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-2A.75.75 0 0 1 12 11.3v-.5a.75.75 0 0 1 .751-.753ZM4.751 14.047h10a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-10A.75.75 0 0 1 4 15.3v-.5a.75.75 0 0 1 .751-.753ZM4.75 18.047h7.5a.75.75 0 0 1 .75.75v.5a.75.75 0 0 1-.75.75h-7.5A.75.75 0 0 1 4 19.3v-.5a.75.75 0 0 1 .75-.753Z"
                                        />
                                    </g>
                                </svg>
                            </div>

                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-black dark:text-white"
                                >
                                    Laravel News
                                </h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Laravel News is a community driven portal
                                    and newsletter aggregating all of the latest
                                    and most important news in the Laravel
                                    ecosystem, including new package releases
                                    and tutorials.
                                </p>
                            </div>

                            <svg
                                class="size-6 shrink-0 self-center stroke-[#FF2D20]"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75"
                                />
                            </svg>
                        </a>

                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800"
                        >
                            <div
                                class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16"
                            >
                                <svg
                                    class="size-5 sm:size-6"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <g fill="#FF2D20">
                                        <path
                                            d="M16.597 12.635a.247.247 0 0 0-.08-.237 2.234 2.234 0 0 1-.769-1.68c.001-.195.03-.39.084-.578a.25.25 0 0 0-.09-.267 8.8 8.8 0 0 0-4.826-1.66.25.25 0 0 0-.268.181 2.5 2.5 0 0 1-2.4 1.824.045.045 0 0 0-.045.037 12.255 12.255 0 0 0-.093 3.86.251.251 0 0 0 .208.214c2.22.366 4.367 1.08 6.362 2.118a.252.252 0 0 0 .32-.079 10.09 10.09 0 0 0 1.597-3.733ZM13.616 17.968a.25.25 0 0 0-.063-.407A19.697 19.697 0 0 0 8.91 15.98a.25.25 0 0 0-.287.325c.151.455.334.898.548 1.328.437.827.981 1.594 1.619 2.28a.249.249 0 0 0 .32.044 29.13 29.13 0 0 0 2.506-1.99ZM6.303 14.105a.25.25 0 0 0 .265-.274 13.048 13.048 0 0 1 .205-4.045.062.062 0 0 0-.022-.07 2.5 2.5 0 0 1-.777-.982.25.25 0 0 0-.271-.149 11 11 0 0 0-5.6 2.815.255.255 0 0 0-.075.163c-.008.135-.02.27-.02.406.002.8.084 1.598.246 2.381a.25.25 0 0 0 .303.193 19.924 19.924 0 0 1 5.746-.438ZM9.228 20.914a.25.25 0 0 0 .1-.393 11.53 11.53 0 0 1-1.5-2.22 12.238 12.238 0 0 1-.91-2.465.248.248 0 0 0-.22-.187 18.876 18.876 0 0 0-5.69.33.249.249 0 0 0-.179.336c.838 2.142 2.272 4 4.132 5.353a.254.254 0 0 0 .15.048c1.41-.01 2.807-.282 4.117-.802ZM18.93 12.957l-.005-.008a.25.25 0 0 0-.268-.082 2.21 2.21 0 0 1-.41.081.25.25 0 0 0-.217.2c-.582 2.66-2.127 5.35-5.75 7.843a.248.248 0 0 0-.09.299.25.25 0 0 0 .065.091 28.703 28.703 0 0 0 2.662 2.12.246.246 0 0 0 .209.037c2.579-.701 4.85-2.242 6.456-4.378a.25.25 0 0 0 .048-.189 13.51 13.51 0 0 0-2.7-6.014ZM5.702 7.058a.254.254 0 0 0 .2-.165A2.488 2.488 0 0 1 7.98 5.245a.093.093 0 0 0 .078-.062 19.734 19.734 0 0 1 3.055-4.74.25.25 0 0 0-.21-.41 12.009 12.009 0 0 0-10.4 8.558.25.25 0 0 0 .373.281 12.912 12.912 0 0 1 4.826-1.814ZM10.773 22.052a.25.25 0 0 0-.28-.046c-.758.356-1.55.635-2.365.833a.25.25 0 0 0-.022.48c1.252.43 2.568.65 3.893.65.1 0 .2 0 .3-.008a.25.25 0 0 0 .147-.444c-.526-.424-1.1-.917-1.673-1.465ZM18.744 8.436a.249.249 0 0 0 .15.228 2.246 2.246 0 0 1 1.352 2.054c0 .337-.08.67-.23.972a.25.25 0 0 0 .042.28l.007.009a15.016 15.016 0 0 1 2.52 4.6.25.25 0 0 0 .37.132.25.25 0 0 0 .096-.114c.623-1.464.944-3.039.945-4.63a12.005 12.005 0 0 0-5.78-10.258.25.25 0 0 0-.373.274c.547 2.109.85 4.274.901 6.453ZM9.61 5.38a.25.25 0 0 0 .08.31c.34.24.616.561.8.935a.25.25 0 0 0 .3.127.631.631 0 0 1 .206-.034c2.054.078 4.036.772 5.69 1.991a.251.251 0 0 0 .267.024c.046-.024.093-.047.141-.067a.25.25 0 0 0 .151-.23A29.98 29.98 0 0 0 15.957.764a.25.25 0 0 0-.16-.164 11.924 11.924 0 0 0-2.21-.518.252.252 0 0 0-.215.076A22.456 22.456 0 0 0 9.61 5.38Z"
                                        />
                                    </g>
                                </svg>
                            </div>

                            <div class="pt-3 sm:pt-5">
                                <h2
                                    class="text-xl font-semibold text-black dark:text-white"
                                >
                                    Vibrant Ecosystem
                                </h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Laravel's robust library of first-party
                                    tools and libraries, such as
                                    <a
                                        href="https://forge.laravel.com"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white dark:focus-visible:ring-[#FF2D20]"
                                        >Forge</a
                                    >,
                                    <a
                                        href="https://vapor.laravel.com"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Vapor</a
                                    >,
                                    <a
                                        href="https://nova.laravel.com"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Nova</a
                                    >,
                                    <a
                                        href="https://envoyer.io"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Envoyer</a
                                    >, and
                                    <a
                                        href="https://herd.laravel.com"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Herd</a
                                    >
                                    help you take your projects to the next
                                    level. Pair them with powerful open source
                                    libraries like
                                    <a
                                        href="https://laravel.com/docs/billing"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Cashier</a
                                    >,
                                    <a
                                        href="https://laravel.com/docs/dusk"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Dusk</a
                                    >,
                                    <a
                                        href="https://laravel.com/docs/broadcasting"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Echo</a
                                    >,
                                    <a
                                        href="https://laravel.com/docs/horizon"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Horizon</a
                                    >,
                                    <a
                                        href="https://laravel.com/docs/sanctum"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Sanctum</a
                                    >,
                                    <a
                                        href="https://laravel.com/docs/telescope"
                                        class="rounded-sm underline hover:text-black focus:outline-none focus-visible:ring-1 focus-visible:ring-[#FF2D20] dark:hover:text-white"
                                        >Telescope</a
                                    >, and more.
                                </p>
                            </div>
                        </div>
                    </div>
                </main>

                <footer
                    class="py-16 text-center text-sm text-black dark:text-white/70"
                >
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>
```

## File: resources/js/app.js
```javascript
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
```

## File: resources/js/bootstrap.js
```javascript
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

## File: resources/views/app.blade.php
```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
```

## File: routes/auth.php
```php
<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
```

## File: routes/console.php
```php
<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
```

## File: composer.json
```json
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "laravel/laravel",
    "type": "project",
    "description": "The skeleton application for the Laravel framework.",
    "keywords": ["laravel", "framework"],
    "license": "MIT",
    "require": {
        "php": "^8.3",
        "inertiajs/inertia-laravel": "^2.0",
        "laravel/framework": "^13.8",
        "laravel/sanctum": "^4.0",
        "laravel/tinker": "^3.0",
        "tightenco/ziggy": "^2.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel-lang/common": "^6.8",
        "laravel/breeze": "^2.4",
        "laravel/pail": "^1.2.5",
        "laravel/pao": "^1.0.6",
        "laravel/pint": "^1.27",
        "laravel/sail": "^1.63",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^12.5.12"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "setup": [
            "composer install",
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\"",
            "@php artisan key:generate",
            "@php artisan migrate --force",
            "npm install --ignore-scripts",
            "npm run build"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite --kill-others"
        ],
        "test": [
            "@php artisan config:clear --ansi @no_additional_args",
            "@php artisan test"
        ],
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ],
        "pre-package-uninstall": [
            "Illuminate\\Foundation\\ComposerScripts::prePackageUninstall"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
```

## File: package.json
```json
{
    "$schema": "https://www.schemastore.org/package.json",
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "devDependencies": {
        "@inertiajs/vue3": "^2.0.0",
        "@tailwindcss/forms": "^0.5.3",
        "@tailwindcss/vite": "^4.0.0",
        "@vitejs/plugin-vue": "^6.0.0",
        "autoprefixer": "^10.4.12",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^3.1",
        "postcss": "^8.4.31",
        "tailwindcss": "^3.2.1",
        "vite": "^8.0.0",
        "vue": "^3.4.0"
    },
    "dependencies": {
        "chart.js": "^4.5.1",
        "vue-chartjs": "^5.3.4",
        "vuedraggable": "^4.1.0"
    }
}
```

## File: vite.config.js
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
```

## File: app/Http/Controllers/TaskController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests; // 追加

    public function index($category = 'all')
    {
        $query = Task::oldest();

        if ($category !== 'all' && $category !== 'today') {
            $query->where('category', $category);
        }

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::oldest()->get(),
            'filteredTasks' => $category === 'today' 
                ? Task::where('due_date', now()->toDateString())->oldest()->get() 
                : ($category === 'all' ? Task::oldest()->get() : Task::where('category', $category)->oldest()->get()),
            'currentCategory' => $category,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'category' => 'required|in:inbox,work,personal,growth,health,finance',
            'sub_category' => 'nullable|string',
            'priority' => 'required|in:high,medium,low',
        ]);

        $parsed = $this->parseSmartTask($validated['title']);

        $taskData = [
            'title' => $parsed['title'],
            'due_date' => $validated['due_date'] ?? $parsed['due_date'],
            'category' => $validated['category'] !== 'inbox' ? $validated['category'] : $parsed['category'],
            'sub_category' => $validated['sub_category'] ?? $parsed['sub_category'],
            'priority' => $validated['priority'] !== 'medium' ? $validated['priority'] : $parsed['priority'],
            'is_completed' => false,
        ];

        $task = Task::create($taskData);

        return redirect()->back()->with('new_task_ids', [$task->id]);
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'raw_text' => ['required', 'string'],
        ]);

        $lines = collect(explode("\n", $validated['raw_text']))
            ->map(fn($line) => trim($line))
            ->filter(fn($line) => !empty($line));

        $newTaskIds = [];
        
        $lines->each(function ($line) use (&$newTaskIds) {
            $taskData = $this->parseSmartTask($line);
            $task = Task::create($taskData);
            $newTaskIds[] = $task->id;
        });

        return redirect()->back()->with('new_task_ids', $newTaskIds);
    }

    private function parseSmartTask($line)
    {
        $category = 'inbox';
        $subCategory = 'general';
        $priority = 'medium'; 
        $dueDate = now()->toDateString(); 
        $cleanTitle = $line;

        $priorityPatterns = [
            '/(?:\[hi\]|\(hi\)|\bhi\b|(?<=[^\x00-\x7F])hi)/ui' => 'high',
            '/(?:\[lo\]|\(lo\)|\blo\b|(?<=[^\x00-\x7F])lo)/ui' => 'low',
            '/(?:\[md\]|\(md\)|\bmd\b|(?<=[^\x00-\x7F])md)/ui' => 'medium',
        ];
        foreach ($priorityPatterns as $pattern => $prioVal) {
            if (preg_match($pattern, $cleanTitle)) {
                $priority = $prioVal;
                $cleanTitle = preg_replace($pattern, '', $cleanTitle);
                break;
            }
        }

        $categoryPatterns = [
            '/(?:\[wo\]|\(wo\)|\bwo\b|(?<=[^\x00-\x7F])wo)/ui' => ['work', 'task'],
            '/(?:\[pe\]|\(pe\)|\bpe\b|(?<=[^\x00-\x7F])pe)/ui' => ['personal', 'shopping'],
            '/(?:\[gr\]|\(gr\)|\bgr\b|(?<=[^\x00-\x7F])gr)/ui' => ['growth', 'learning'],
            '/(?:\[he\]|\(he\)|\bhe\b|(?<=[^\x00-\x7F])he)/ui' => ['health', 'fitness'],
            '/(?:\[fi\]|\(fi\)|\bfi\b|(?<=[^\x00-\x7F])fi)/ui' => ['finance', 'budget'],
        ];
        foreach ($categoryPatterns as $pattern => $catInfo) {
            if (preg_match($pattern, $cleanTitle)) {
                $category = $catInfo[0];
                $subCategory = $catInfo[1];
                $cleanTitle = preg_replace($pattern, '', $cleanTitle);
                break;
            }
        }

        if ($priority === 'medium') {
            if (Str::contains($cleanTitle, ['急ぎ', '緊急', '最優先', '今すぐ', '至急', '重要', '!'])) {
                $priority = 'high';
            } elseif (Str::contains($cleanTitle, ['後で', 'いつでも', 'いつか', '急ぎではない', '暇なとき'])) {
                $priority = 'low';
            }
        }

        if ($category === 'inbox') {
            if (Str::contains($cleanTitle, ['ミーティング', '会議', '資料', '修正', '開発', 'バグ', 'PR', 'メール', '返信'])) {
                $category = 'work';
                $subCategory = 'task';
            } elseif (Str::contains($cleanTitle, ['買い物', 'スーパー', '食材', '掃除', '洗濯', '片付け', '購入'])) {
                $category = 'personal';
                $subCategory = 'shopping';
            } elseif (Str::contains($cleanTitle, ['勉強', '読書', '学習', '英語', '資格', 'スキル', '本'])) {
                $category = 'growth';
                $subCategory = 'learning';
            } elseif (Str::contains($cleanTitle, ['ジム', 'ランニング', '筋トレ', '病院', '薬', '運動', '散歩'])) {
                $category = 'health';
                $subCategory = 'fitness';
            } elseif (Str::contains($cleanTitle, ['銀行', '振込', '家計簿', '税金', '支払い', 'ATM'])) {
                $category = 'finance';
                $subCategory = 'budget';
            }
        }

        $dateKeywords = [
            '明後日' => fn() => now()->addDays(2)->toDateString(),
            'あさって' => fn() => now()->addDays(2)->toDateString(),
            '明日' => fn() => now()->addDay()->toDateString(),
            'あした' => fn() => now()->addDay()->toDateString(),
            '今週末' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '週末' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '月末' => fn() => now()->endOfMonth()->toDateString(),
            '来週' => fn() => now()->addWeek()->toDateString(),
            '月曜日' => fn() => now()->next(Carbon::MONDAY)->toDateString(),
            '月曜' => fn() => now()->next(Carbon::MONDAY)->toDateString(),
            '火曜日' => fn() => now()->next(Carbon::TUESDAY)->toDateString(),
            '火曜' => fn() => now()->next(Carbon::TUESDAY)->toDateString(),
            '水曜日' => fn() => now()->next(Carbon::WEDNESDAY)->toDateString(),
            '水曜' => fn() => now()->next(Carbon::WEDNESDAY)->toDateString(),
            '木曜日' => fn() => now()->next(Carbon::THURSDAY)->toDateString(),
            '木曜' => fn() => now()->next(Carbon::THURSDAY)->toDateString(),
            '金曜日' => fn() => now()->next(Carbon::FRIDAY)->toDateString(),
            '金曜' => fn() => now()->next(Carbon::FRIDAY)->toDateString(),
            '土曜日' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '土曜' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '日曜日' => fn() => now()->next(Carbon::SUNDAY)->toDateString(),
            '日曜' => fn() => now()->next(Carbon::SUNDAY)->toDateString(),
        ];

        foreach ($dateKeywords as $keyword => $calculator) {
            if (Str::contains($cleanTitle, $keyword)) {
                $dueDate = $calculator();
                $cleanTitle = str_replace($keyword, '', $cleanTitle);
                break;
            }
        }

        $cleanTitle = preg_replace('/^[、。\s]+|[、。\s]+$/u', '', $cleanTitle);
        $cleanTitle = preg_replace('/[、。\s]{2,}/u', '', $cleanTitle);
        $cleanTitle = trim($cleanTitle);

        return [
            'title' => $cleanTitle,
            'category' => $category,
            'sub_category' => $subCategory,
            'priority' => $priority,
            'due_date' => $dueDate,
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'is_completed' => 'nullable|boolean',
            'due_date' => 'nullable|date',
            'category' => 'nullable|in:inbox,work,personal,growth,health,finance',
            'sub_category' => 'nullable|string',
            'priority' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back();
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:tasks,id'],
        ]);

        Task::whereIn('id', $request->ids)->delete();

        return redirect()->back();
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:tasks,id'],
            'is_completed' => ['nullable', 'boolean'],
            'due_date' => ['nullable', 'date'],
            'category' => ['nullable', 'in:inbox,work,personal,growth,health,finance'],
            'sub_category' => ['nullable', 'string'],
            'priority' => ['nullable', 'in:high,medium,low'],
        ]);

        $updateData = collect($validated)
            ->except('ids')
            ->filter(fn($value) => !is_null($value))
            ->toArray();

        if (!empty($updateData)) {
            Task::whereIn('id', $validated['ids'])->update($updateData);
        }

        return redirect()->back();
    }
}
```

## File: resources/js/Composables/useTaskUtils.js
```javascript
export function useTaskUtils() {
    const isToday = (dateString) => {
        const today = new Date().toISOString().split('T')[0];
        return dateString === today;
    };

    const isExpired = (dateString) => {
        if (!dateString) return false;
        const today = new Date().toISOString().split('T')[0];
        return dateString < today;
    };

    // 【追加】期限の状態を返す関数
    const getDueDateStatus = (dueDate) => {
        if (!dueDate) return 'normal';
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const due = new Date(dueDate);
        due.setHours(0, 0, 0, 0);
        
        const diffDays = Math.ceil((due - today) / (1000 * 60 * 60 * 24));

        if (diffDays <= 0) return 'expired'; // 今日・期限切れ
        if (diffDays <= 3) return 'warning'; // 3日以内
        return 'normal';
    };

    const getStatusBadge = (status) => {
        const statuses = {
            0: { text: '⚪ 未着手', class: 'bg-slate-100/70 text-slate-600 border-slate-200' },
            1: { text: '⚡ 進行中', class: 'bg-indigo-50 text-indigo-600 border-indigo-150 font-medium' },
            2: { text: '✅ 完了', class: 'bg-emerald-50 text-emerald-600 border-emerald-150 font-medium' },
        };
        return statuses[status] || { text: '❓ 不明', class: 'bg-rose-50 text-rose-600 border-rose-200' };
    };

    const getCardClass = (task) => {
        if (isExpired(task.due_date) && task.status !== 2) {
            return 'border-l-2 border-l-rose-500 border-y-slate-200 border-r-slate-200 bg-white shadow-[0_2px_12px_rgba(15,23,42,0.06)]';
        }
        if (task.status === 2) {
            return 'border-slate-200 bg-slate-50/60 opacity-60 shadow-none';
        }
        if (task.status === 1) {
            return 'border-l-2 border-l-indigo-500 border-y-slate-200 border-r-slate-200 bg-white shadow-[0_4px_16px_rgba(99,102,241,0.08)]';
        }
        return 'border-slate-200 bg-white shadow-[0_4px_12px_rgba(15,23,42,0.06)] hover:border-slate-300 hover:shadow-[0_6px_20px_rgba(15,23,42,0.1)] transition-all duration-200';
    };

    const getPriorityBadgeClass = (index) => {
        if (index === 0) return 'bg-slate-800 text-white border-transparent font-semibold shadow-sm';
        if (index === 1 || index === 2) return 'bg-slate-100 text-slate-700 border-slate-200 font-medium';
        return 'bg-slate-50 text-slate-400 border-slate-100';
    };

    return { isToday, isExpired, getDueDateStatus, getStatusBadge, getCardClass, getPriorityBadgeClass };
}
```

## File: resources/js/Pages/Tasks/Edit.vue
```vue
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
```

## File: resources/js/Pages/Dashboard.vue
```vue
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
```

## File: routes/web.php
```php
<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController; 
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 認証・メール認証が必須のグループ
Route::middleware(['auth', 'verified'])->group(function () {
    // 5大カテゴリ別の個別ページ（ルート名を 'dashboard' に統一）
    Route::get('/dashboard/{category?}', [TaskController::class, 'index'])->name('dashboard');

    // タスク管理の使い方ガイドページ
    Route::get('/tasks/guide', function () {
        return Inertia::render('TaskGuide');
    })->name('tasks.guide');

    // 一括処理・通常の作成ルート（{task} よりも上に配置）
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/bulk', [TaskController::class, 'storeBulk'])->name('tasks.store-bulk');
    Route::delete('/tasks/bulk', [TaskController::class, 'bulkDestroy'])->name('tasks.bulk-destroy');
    Route::patch('/tasks/bulk', [TaskController::class, 'bulkUpdate'])->name('tasks.bulk-update');

    // 個別処理ルート（{task} を含むものは下に配置）
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
```
