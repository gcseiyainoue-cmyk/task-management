<?php
/**
 * =====================================================================================
 * 【ファイル名】 RoutineService.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（サービス / ルーティンタスク自動生成ビジネスロジック）
 * =====================================================================================
 * 【実務における設計思想】
 * ユーザーが登録した有効なルーティンテンプレートをもとに、本日のタスクがまだ存在しない場合
 * に自動で個別タスクを生成・永続化するビジネスロジックをカプセル化しています。
 * コントローラーの肥大化（Fat Controller）を防ぎ、定期実行や初期描画時のタスク自動生成
 * という責務を独立したサービス層として切り出すことで、保守性と再利用性を高めています。
 */
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class RoutineService
{
    /**
     * =====================================================================================
     * 【メソッド名】 generateDailyTasksForUser
     * 【概要】 ユーザーの有効なルーティンテンプレートから条件に応じて本日のタスクを自動生成する処理
     * =====================================================================================
     * 1. 本日の日付を取得します。
     * 2. 指定されたユーザーに紐づく有効なルーティンテンプレート（is_active = true）の一覧を取得します。
     * 3. テンプレートごとの頻度設定（frequency_type）に応じて、本日生成すべきかを判定します。
     *    - 曜日毎（day_of_week）: 設定された曜日と本日の曜日が一致する場合に生成
     *    - 何日毎（interval）: 最後に生成されたタスクの期限日 + インターバル日数が今日以降に達している場合に生成
     * 4. 条件を満たし、かつ本日すでに未生成であれば、新しいタスクを自動生成します。
     * 
     * @param User $user 対象のユーザーモデル
     * @return void
     */
    public function generateDailyTasksForUser(User $user): void
    {
        $today = Carbon::today();
        $todayString = $today->toDateString();

        // ユーザーに紐づく有効なルーティンテンプレートを取得
        $activeRoutines = $user->routineTemplates()->where('is_active', true)->get();

        foreach ($activeRoutines as $routine) {
            // 1. 本日すでにこのルーティンからタスクが生成されているかチェック（削除済みも含む）
            $existsToday = $user->tasks()
                ->withTrashed()
                ->where('routine_template_id', $routine->id)
                ->where('due_date', $todayString)
                ->exists();

            if ($existsToday) {
                continue;
            }

            // 2. 頻度タイプに応じた生成判定
            $shouldGenerate = false;

            if ($routine->frequency_type === 'day_of_week') {
                // 【曜日毎】の場合：今日の曜日（0:日〜6:土）が設定値と一致するか
                if ($routine->day_of_week !== null && $today->dayOfWeek === $routine->day_of_week) {
                    $shouldGenerate = true;
                }
            } elseif ($routine->frequency_type === 'interval') {
                // 【何日毎（インターバル）】の場合
                // 直近に生成されたタスクを取得（削除済みも含めて正確な履歴を追うため withTrashed を付与）
                $latestTask = $user->tasks()
                    ->withTrashed()
                    ->where('routine_template_id', $routine->id)
                    ->orderBy('due_date', 'desc')
                    ->first();

                if (!$latestTask) {
                    // タスク履歴がない場合、テンプレート作成日からインターバルが経過しているかチェック
                    $nextDueDate = Carbon::parse($routine->created_at)->addDays($routine->interval_days);
                    if ($today->greaterThanOrEqualTo($nextDueDate)) {
                        $shouldGenerate = true;
                    }
                } else {
                    // 最後のタスクの期限日 + interval_days が「今日以降」に達しているか
                    $nextDueDate = Carbon::parse($latestTask->due_date)->addDays($routine->interval_days);
                    if ($today->greaterThanOrEqualTo($nextDueDate)) {
                        $shouldGenerate = true;
                    }
                }
            }

            // 3. 判定が真であれば本日分のタスクを生成
            if ($shouldGenerate) {
                $user->tasks()->create([
                    'routine_template_id' => $routine->id,
                    'title' => $routine->title,
                    'category' => $routine->category,
                    'sub_category' => $routine->sub_category,
                    'priority' => $routine->priority,
                    'due_date' => $todayString,
                    'is_completed' => false,
                ]);
            }
        }
    }
}