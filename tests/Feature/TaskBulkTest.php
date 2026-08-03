<?php
/**
 * =====================================================================================
 * 【ファイル名】 TaskBulkTest.php
 * 【アーキテクチャ上の位置づけ】 テスト層（フィーチャーテスト / タスク一括処理機能の統合テスト）
 * =====================================================================================
 * 【実務における設計思想】
 * 複数行テキストからのタスク一括作成や、複数タスクの所有者スコープを伴う
 * 一括削除などのバルク操作が、セキュリティとデータ整合性を保ちながら
 * 正しく機能することを検証します。
 */
namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskBulkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * =====================================================================================
     * 【メソッド名】 test_authenticated_user_can_create_tasks_in_bulk
     * 【概要】 認証済みユーザーが複数行テキストからタスクを一括作成できることの検証
     * =====================================================================================
     * @return void
     */
    public function test_authenticated_user_can_create_tasks_in_bulk(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks/bulk', [
            'raw_text' => "タスク1 [wo] [hi]\nタスク2 [pe] [lo]",
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'タスク1',
        ]);
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'タスク2',
        ]);
    }

    /**
     * =====================================================================================
     * 【メソッド名】 test_authenticated_user_can_bulk_destroy_their_tasks
     * 【概要】 認証済みユーザーが自身の複数タスクを一括削除できることの検証
     * =====================================================================================
     * @return void
     */
    public function test_authenticated_user_can_bulk_destroy_their_tasks(): void
    {
        $user = User::factory()->create();

        $task1 = Task::factory()->create(['user_id' => $user->id]);
        $task2 = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/tasks/bulk', [
            'ids' => [$task1->id, $task2->id],
        ]);

        $response->assertRedirect();

        $this->assertSoftDeleted('tasks', ['id' => $task1->id]);
        $this->assertSoftDeleted('tasks', ['id' => $task2->id]);
    }
}