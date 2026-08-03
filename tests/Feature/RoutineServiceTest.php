<?php

namespace Tests\Feature;

use App\Models\RoutineTemplate;
use App\Models\Task;
use App\Models\User;
use App\Services\RoutineService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test; // ★ 追加
use Tests\TestCase;

class RoutineServiceTest extends TestCase
{
    use RefreshDatabase;

    private RoutineService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RoutineService();

        // テスト中の基準日時を金曜日（5）に固定し、実行タイミングによる不安定化を防止
        Carbon::setTestNow('2026-07-31 09:00:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // 日時固定の解除
        parent::tearDown();
    }

    #[Test]
    public function 本日の曜日に一致するルーティンから正しいカテゴリとサブカテゴリでタスクが生成されること(): void
    {
        $user = User::factory()->create();
        $friday = 5; // 2026-07-31 は金曜日

        $template = RoutineTemplate::factory()->dayOfWeek($friday)->create([
            'user_id' => $user->id,
            'title' => '週報の作成と1週間の振り返り',
            'category' => 'work',
            'sub_category' => 'admin',
            'priority' => 'high',
        ]);

        $this->service->generateDailyTasksForUser($user);

        // テンプレートの category と sub_category がタスクに正しくセットされているかを検証
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'routine_template_id' => $template->id,
            'title' => '週報の作成と1週間の振り返り',
            'category' => 'work',
            'sub_category' => 'admin',
            'priority' => 'high',
            'due_date' => '2026-07-31',
            'is_completed' => false,
        ]);
    }

    #[Test]
    public function 曜日に一致しないルーティンからはタスクが生成されないこと(): void
    {
        $user = User::factory()->create();
        $monday = 1; // 月曜日

        $template = RoutineTemplate::factory()->dayOfWeek($monday)->create([
            'user_id' => $user->id,
            'category' => 'work',
            'sub_category' => 'admin',
        ]);

        $this->service->generateDailyTasksForUser($user);

        $this->assertDatabaseMissing('tasks', [
            'routine_template_id' => $template->id,
        ]);
    }

    #[Test]
    public function 非アクティブなルーティンからはタスクが生成されないこと(): void
    {
        $user = User::factory()->create();
        $friday = 5;

        $template = RoutineTemplate::factory()->dayOfWeek($friday)->inactive()->create([
            'user_id' => $user->id,
            'category' => 'work',
            'sub_category' => 'admin',
        ]);

        $this->service->generateDailyTasksForUser($user);

        $this->assertDatabaseMissing('tasks', [
            'routine_template_id' => $template->id,
        ]);
    }

    #[Test]
    public function 本日分タスクを論理削除した場合でも同日中には再生成されないこと(): void
    {
        $user = User::factory()->create();
        $friday = 5;

        $template = RoutineTemplate::factory()->dayOfWeek($friday)->create([
            'user_id' => $user->id,
            'category' => 'personal',
            'sub_category' => 'housework',
        ]);

        // 1回目の実行：タスク生成
        $this->service->generateDailyTasksForUser($user);

        $task = Task::where('routine_template_id', $template->id)->first();
        $this->assertNotNull($task);

        // ユーザーが削除（論理削除）
        $task->delete();
        $this->assertSoftDeleted($task);

        // 2回目の実行：同日中の再生成スキップ
        $this->service->generateDailyTasksForUser($user);

        $this->assertEquals(1, Task::withTrashed()->where('routine_template_id', $template->id)->count());
        $this->assertEquals(0, Task::where('routine_template_id', $template->id)->count());
    }

    #[Test]
    public function インターバル指定で指定日数が経過している場合にタスクが生成されること(): void
    {
        $user = User::factory()->create();
        $intervalDays = 3;

        $template = RoutineTemplate::factory()->interval($intervalDays)->create([
            'user_id' => $user->id,
            'category' => 'health',
            'sub_category' => 'fitness',
        ]);

        // 3日前の既存タスク（親テンプレートと同じカテゴリ・サブカテゴリをセット）
        Task::factory()->create([
            'user_id' => $user->id,
            'routine_template_id' => $template->id,
            'category' => $template->category,
            'sub_category' => $template->sub_category,
            'due_date' => Carbon::today()->subDays($intervalDays)->toDateString(),
        ]);

        $this->service->generateDailyTasksForUser($user);

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'routine_template_id' => $template->id,
            'category' => 'health',
            'sub_category' => 'fitness',
            'due_date' => '2026-07-31',
        ]);
    }

    #[Test]
    public function 直前のインターバルタスクが論理削除されていても正確な日数が判定されること(): void
    {
        $user = User::factory()->create();
        $intervalDays = 3;

        $template = RoutineTemplate::factory()->interval($intervalDays)->create([
            'user_id' => $user->id,
            'category' => 'personal',
            'sub_category' => 'housework',
        ]);

        // 1日前のタスクを作成後に論理削除
        $deletedTask = Task::factory()->create([
            'user_id' => $user->id,
            'routine_template_id' => $template->id,
            'category' => $template->category,
            'sub_category' => $template->sub_category,
            'due_date' => Carbon::today()->subDays(1)->toDateString(),
        ]);
        $deletedTask->delete();

        $this->service->generateDailyTasksForUser($user);

        // 1日前（削除済み）からまだ3日経過していないため生成されない
        $this->assertEquals(1, Task::withTrashed()->where('routine_template_id', $template->id)->count());
        $this->assertEquals(0, Task::where('routine_template_id', $template->id)->count());
    }

    #[Test]
    public function 過去タスク履歴が存在しない場合はテンプレート作成日からのインターバルで判定されること(): void
    {
        $user = User::factory()->create();
        $intervalDays = 3;

        // 3日前に作成されたがタスク履歴がないテンプレート
        $template = RoutineTemplate::factory()->interval($intervalDays)->create([
            'user_id' => $user->id,
            'category' => 'growth',
            'sub_category' => 'study',
            'created_at' => Carbon::today()->subDays($intervalDays),
        ]);

        $this->service->generateDailyTasksForUser($user);

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'routine_template_id' => $template->id,
            'category' => 'growth',
            'sub_category' => 'study',
            'due_date' => '2026-07-31',
        ]);
    }
}