<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard', ['category' => 'all']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Tasks/Index')
            ->has('tasks')
            ->has('filteredTasks')
            ->where('currentCategory', 'all')
        );
    }

    public function test_user_can_create_single_task()
    {
        $response = $this->actingAs($this->user)->post(route('tasks.store'), [
            'title' => 'テストタスク作成',
            'category' => 'work',
            'priority' => 'high',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'title' => 'テストタスク作成',
            'category' => 'work',
            'priority' => 'high',
        ]);
    }

    public function test_user_can_create_multiple_tasks_in_bulk_from_text()
    {
        $rawText = "タスク1\nタスク2";

        $response = $this->actingAs($this->user)->post(route('tasks.store-bulk'), [
            'raw_text' => $rawText,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['title' => 'タスク1']);
        $this->assertDatabaseHas('tasks', ['title' => 'タスク2']);
    }

    public function test_user_can_update_existing_task()
    {
        $task = Task::create([
            'title' => '初期タイトル',
            'category' => 'work',
            'priority' => 'medium',
            'is_completed' => false,
        ]);

        $response = $this->actingAs($this->user)->patch(route('tasks.update', $task), [
            'title' => '更新後タイトル',
            'is_completed' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => '更新後タイトル',
            'is_completed' => true,
        ]);
    }

    public function test_user_can_delete_single_task()
    {
        $task = Task::create([
            'title' => '削除対象タスク',
            'category' => 'work',
            'priority' => 'medium',
        ]);

        $response = $this->actingAs($this->user)->delete(route('tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_user_can_delete_multiple_tasks_in_bulk()
    {
        $task1 = Task::create(['title' => '一括削除1', 'category' => 'work', 'priority' => 'medium']);
        $task2 = Task::create(['title' => '一括削除2', 'category' => 'work', 'priority' => 'medium']);

        $response = $this->actingAs($this->user)->delete(route('tasks.bulk-destroy'), [
            'ids' => [$task1->id, $task2->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task1->id]);
        $this->assertDatabaseMissing('tasks', ['id' => $task2->id]);
    }

    public function test_user_can_update_multiple_tasks_in_bulk()
    {
        $task1 = Task::create(['title' => '一括更新1', 'category' => 'work', 'priority' => 'medium', 'is_completed' => false]);
        $task2 = Task::create(['title' => '一括更新2', 'category' => 'work', 'priority' => 'medium', 'is_completed' => false]);

        $response = $this->actingAs($this->user)->patch(route('tasks.bulk-update'), [
            'ids' => [$task1->id, $task2->id],
            'is_completed' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', ['id' => $task1->id, 'is_completed' => true]);
        $this->assertDatabaseHas('tasks', ['id' => $task2->id, 'is_completed' => true]);
    }
}