<?php
/**
 * =====================================================================================
 * 【ファイル名】 RoutineControllerTest.php
 * 【アーキテクチャ上の位置づけ】 テスト層（フィーチャーテスト / ルーティンテンプレート管理の統合テスト）
 * =====================================================================================
 * 【実務における設計思想】
 * ルーティンテンプレートの新規作成、ステータス切り替え、削除などの管理操作が
 * 認証済みユーザーのスコープ内で正常に処理されることを検証します。
 */
namespace Tests\Feature;

use App\Models\RoutineTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutineControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * =====================================================================================
     * 【メソッド名】 test_authenticated_user_can_create_routine_template
     * 【概要】 認証済みユーザーがルーティンテンプレートを新規作成できることの検証
     * =====================================================================================
     * @return void
     */
    public function test_authenticated_user_can_create_routine_template(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/routines', [
            'title' => '毎日の運動',
            'category' => 'health',
            'sub_category' => 'fitness',
            'priority' => 'medium',
            'frequency_type' => 'interval', // 'daily' から 'interval' に修正
            'interval_days' => 1,           // 1日ごと（毎日）
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('routine_templates', [
            'user_id' => $user->id,
            'title' => '毎日の運動',
            'category' => 'health',
        ]);
    }
    
    /**
     * =====================================================================================
     * 【メソッド名】 test_authenticated_user_can_toggle_routine_status
     * 【概要】 ルーティンテンプレートの有効・無効状態が切り替わることの検証
     * =====================================================================================
     * @return void
     */
    public function test_authenticated_user_can_toggle_routine_status(): void
    {
        $user = User::factory()->create();

        $routine = RoutineTemplate::factory()->create([
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->patch("/routines/{$routine->id}/toggle");

        $response->assertRedirect();
        $this->assertDatabaseHas('routine_templates', [
            'id' => $routine->id,
            'is_active' => false,
        ]);
    }
}