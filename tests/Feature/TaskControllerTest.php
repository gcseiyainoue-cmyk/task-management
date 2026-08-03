<?php
/**
 * =====================================================================================
 * 【ファイル名】 TaskControllerTest.php
 * 【アーキテクチャ上の位置づけ】 テスト層（フィーチャーテスト / タスクCRUDおよび認可の統合テスト）
 * =====================================================================================
 * 【実務における設計思想】
 * タスクの作成（Store）、更新（Update）、削除（Delete）におけるコントローラーの動作と、
 * TaskPolicy による所有権検証が正しく機能するかを網羅的にテストします。
 */
namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * =====================================================================================
     * 【メソッド名】 test_authenticated_user_can_create_task
     * 【概要】 認証済みユーザーがタスクを新規作成できることの検証
     * =====================================================================================
     * @return void
     */
    public function test_authenticated_user_can_create_task(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => '新規テストタスク [wo] [hi]',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => '新規テストタスク',
        ]);
    }

    /**
     * =====================================================================================
     * 【メソッド名】 test_user_cannot_update_other_users_task
     * 【概要】 TaskPolicyにより、他人のタスクの更新が厳格に拒否されることの検証
     * =====================================================================================
     * @return void
     */
    public function test_user_cannot_update_other_users_task(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $owner->id,
            'title' => '元のタイトル',
        ]);

        // 修正: PUTからPATCHに変更
        $response = $this->actingAs($otherUser)->patch("/tasks/{$task->id}", [
            'title' => '改ざんされたタイトル',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => '元のタイトル',
        ]);
    }
}