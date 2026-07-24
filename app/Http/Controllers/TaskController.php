<?php
/**
 * =====================================================================================
 * 【ファイル名】 TaskController.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（コントローラー / コーディネーター）
 * =====================================================================================
 * 【実務における設計思想】
 * HTTPリクエストを受け取り、バリデーションの実行、モデル（Task）を介したデータ操作、
 * および Inertia.js を通じたフロントエンドへのデータ受け渡しを担当します。
 * スマートタスクの解析処理を独立したサービス（SmartTaskParserService）へ委譲し、
 * インデックス取得時のクエリ重複を排除して最適化を図ることで、Fat Controllerを防ぎ、
 * クリーンで保守性の高い設計を実現しています。
 */
namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\SmartTaskParserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    protected SmartTaskParserService $parserService;

    public function __construct(SmartTaskParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    public function index($category = 'all')
    {
        // ▼ ルートパラメータ省略時（null）に 'all' をフォールバックする安全策
        $category = $category ?? 'all';

        $allTasks = Task::oldest()->get();

        $filteredTasks = match ($category) {
            'today' => Task::where('due_date', now()->toDateString())->oldest()->get(),
            'all' => $allTasks,
            default => Task::where('category', $category)->oldest()->get(),
        };

        return Inertia::render('Tasks/Index', [
            'tasks' => $allTasks,
            'filteredTasks' => $filteredTasks,
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

        $parsed = $this->parserService->parse($validated['title']);

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
            $taskData = $this->parserService->parse($line);
            $task = Task::create($taskData);
            $newTaskIds[] = $task->id;
        });

        return redirect()->back()->with('new_task_ids', $newTaskIds);
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