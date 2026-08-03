<?php

namespace Database\Seeders;

use App\Models\RoutineTemplate;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoutineSeeder extends Seeder
{
    /**
     * 1〜7日ごとのインターバルおよび日曜〜土曜の曜日毎パターンを網羅したダミーデータを生成
     */
    public function run(): void
    {
        // テストユーザーA・Bを取得または作成
        $user1 = User::firstOrCreate(
            ['email' => 'a@example.com'],
            ['name' => 'テストユーザーA', 'password' => Hash::make('password')]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'b@example.com'],
            ['name' => 'テストユーザーB', 'password' => Hash::make('password')]
        );

        $users = [$user1, $user2];
        $today = Carbon::today();

        // =========================================================
        // 1. 曜日指定（日曜日:0 〜 土曜日:6）のテンプレート定義
        // =========================================================
        $daysOfWeek = [
            0 => ['title' => '【日曜日】翌週のスケジュール調整と目標設定', 'category' => 'growth', 'sub_category' => 'goal', 'priority' => 'medium'],
            1 => ['title' => '【月曜日】週始めのメールチェックとタスク整理', 'category' => 'work', 'sub_category' => 'admin', 'priority' => 'high'],
            2 => ['title' => '【火曜日】コードレビューとリファクタリング', 'category' => 'work', 'sub_category' => 'task', 'priority' => 'medium'],
            3 => ['title' => '【水曜日】ドキュメント更新とナレッジ整理', 'category' => 'work', 'sub_category' => 'project', 'priority' => 'low'],
            4 => ['title' => '【木曜日】プロジェクト進捗の確認と調整', 'category' => 'work', 'sub_category' => 'meeting', 'priority' => 'medium'],
            5 => ['title' => '【金曜日】週報の作成と1週間の振り返り', 'category' => 'work', 'sub_category' => 'admin', 'priority' => 'high'],
            6 => ['title' => '【土曜日】個人開発・スキルアップ学習', 'category' => 'growth', 'sub_category' => 'study', 'priority' => 'low'],
        ];

        // =========================================================
        // 2. インターバル指定（1日ごと 〜 7日ごと）のテンプレート定義
        // =========================================================
        $intervals = [
            1 => ['title' => '【1日ごと】デイリーリフレクションとメモ整理', 'category' => 'growth', 'sub_category' => 'reading', 'priority' => 'low'],
            2 => ['title' => '【2日ごと】観葉植物の水やり', 'category' => 'personal', 'sub_category' => 'housework', 'priority' => 'medium'],
            3 => ['title' => '【3日ごと】部屋の掃除機がけ', 'category' => 'personal', 'sub_category' => 'housework', 'priority' => 'medium'],
            4 => ['title' => '【4日ごと】筋トレ・フィットネス', 'category' => 'health', 'sub_category' => 'fitness', 'priority' => 'high'],
            5 => ['title' => '【5日ごと】バックアップログの確認', 'category' => 'work', 'sub_category' => 'admin', 'priority' => 'low'],
            6 => ['title' => '【6日ごと】冷蔵庫の食材賞味期限チェック', 'category' => 'personal', 'sub_category' => 'shopping', 'priority' => 'low'],
            7 => ['title' => '【7日ごと】デスクまわりのディープクレンジング', 'category' => 'personal', 'sub_category' => 'housework', 'priority' => 'medium'],
        ];

        foreach ($users as $user) {
            // --- 曜日指定テンプレート & 過去履歴作成 ---
            foreach ($daysOfWeek as $dow => $data) {
                $template = RoutineTemplate::create([
                    'user_id' => $user->id,
                    'title' => $data['title'],
                    'category' => $data['category'],
                    'sub_category' => $data['sub_category'],
                    'priority' => $data['priority'],
                    'frequency_type' => 'day_of_week',
                    'day_of_week' => $dow,
                    'interval_days' => null,
                    'is_active' => true,
                ]);

                // 直近3週間分の過去タスク履歴を生成
                for ($week = 3; $week >= 1; $week--) {
                    $dueDate = $today->copy()->subWeeks($week)->startOfWeek(Carbon::SUNDAY)->addDays($dow);

                    Task::create([
                        'user_id' => $user->id,
                        'routine_template_id' => $template->id,
                        'title' => $template->title,
                        'category' => $template->category,
                        'sub_category' => $template->sub_category,
                        'priority' => $template->priority,
                        'due_date' => $dueDate->toDateString(),
                        'is_completed' => true,
                        'created_at' => $dueDate,
                        'updated_at' => $dueDate,
                    ]);
                }
            }

            // --- インターバル指定テンプレート & 過去履歴作成 ---
            foreach ($intervals as $intervalDays => $data) {
                $template = RoutineTemplate::create([
                    'user_id' => $user->id,
                    'title' => $data['title'],
                    'category' => $data['category'],
                    'sub_category' => $data['sub_category'],
                    'priority' => $data['priority'],
                    'frequency_type' => 'interval',
                    'day_of_week' => null,
                    'interval_days' => $intervalDays,
                    'is_active' => true,
                ]);

                // 直近3回分のタスク履歴を生成
                for ($i = 3; $i >= 1; $i--) {
                    $dueDate = $today->copy()->subDays($i * $intervalDays);

                    $task = Task::create([
                        'user_id' => $user->id,
                        'routine_template_id' => $template->id,
                        'title' => $template->title,
                        'category' => $template->category,
                        'sub_category' => $template->sub_category,
                        'priority' => $template->priority,
                        'due_date' => $dueDate->toDateString(),
                        'is_completed' => true,
                        'created_at' => $dueDate,
                        'updated_at' => $dueDate,
                    ]);

                    // 3日ごとのタスク（直近分）は論理削除の動作検証用に soft delete
                    if ($intervalDays === 3 && $i === 1) {
                        $task->delete();
                    }
                }
            }
        }
    }
}