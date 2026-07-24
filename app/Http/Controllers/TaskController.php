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
 * さらに、マルチユーザー環境における厳格なデータ分離を実現するため、
 * ログインユーザー自身のタスク（Auth::user()->tasks()）にスコープを完全に絞り込み、
 * TaskPolicy を用いた認可チェック（$this->authorize）を徹底することで、
 * 不正アクセスや他者データの漏洩を防ぐ堅牢なセキュリティを担保しています。
 */
namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\SmartTaskParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * スマートタスク解析サービスのインスタンス
     */
    protected SmartTaskParserService $parserService;

    /**
     * コンストラクタ依存性注入によるサービスの初期化
     */
    public function __construct(SmartTaskParserService $parserService)
    {
        $this->parserService = $parserService;
    }

    /**
     * タスク一覧画面の表示
     * ログインユーザーに紐づくタスクのみを抽出し、カテゴリ別にフィルタリングして返す
     *
     * @param string|null $category
     * @return \Inertia\Response
     */
    public function index($category = 'all')
    {
        // ▼ ルートパラメータ省略時（null）に 'all' をフォールバックする安全策
        $category = $category ?? 'all';

        // ▼ ログインユーザーに紐づくタスクのクエリをベースにする
        $userTasksQuery = Auth::user()->tasks();

        $allTasks = (clone $userTasksQuery)->oldest()->get();

        $filteredTasks = match ($category) {
            'today' => (clone $userTasksQuery)->where('due_date', now()->toDateString())->oldest()->get(),
            'all' => $allTasks,
            default => (clone $userTasksQuery)->where('category', $category)->oldest()->get(),
        };

        return Inertia::render('Tasks/Index', [
            'tasks' => $allTasks,
            'filteredTasks' => $filteredTasks,
            'currentCategory' => $category,
        ]);
    }

    /**
     * 単一タスクの新規作成処理
     * リクエスト内容をバリデーション後、パーサーで解析し、ログインユーザーに紐づけて保存する
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        // ▼ ログインユーザーのリレーションを介して作成し、user_idを自動付与する
        $task = Auth::user()->tasks()->create($taskData);

        return redirect()->back()->with('new_task_ids', [$task->id]);
    }

    /**
     * 複数行テキストからのタスク一括作成処理
     * 改行区切りのテキストを解析し、すべてのタスクをログインユーザーに紐づけて一括登録する
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            // ▼ 一括作成時もログインユーザーに紐づけて安全に登録する
            $task = Auth::user()->tasks()->create($taskData);
            $newTaskIds[] = $task->id;
        });

        return redirect()->back()->with('new_task_ids', $newTaskIds);
    }

    /**
     * タスクの更新処理
     * TaskPolicy による所有権・認可チェックを実行し、許可された場合のみ更新を適用する
     *
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        // ▼ TaskPolicy の update メソッドを呼び出し、他人のタスク改変を防止する
        $this->authorize('update', $task);

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

    /**
     * 単一タスクの削除処理
     * TaskPolicy による所有権・認可チェックを実行し、許可された場合のみ削除する
     *
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        // ▼ TaskPolicy の delete メソッドを呼び出し、他人のタスク削除を防止する
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->back();
    }

    /**
     * 複数タスクの一括削除処理
     * 他ユーザーのタスクIDが含まれていても除外され、自身のタスクのみ安全に削除する
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:tasks,id'],
        ]);

        // ▼ ログインユーザーの所有するタスクに限定して一括削除を実行する
        Auth::user()->tasks()->whereIn('id', $request->ids)->delete();

        return redirect()->back();
    }

    /**
     * 複数タスクの一括更新処理
     * 他ユーザーのタスクが巻き添えにならないよう、ログインユーザーのスコープ内で一括更新する
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            // ▼ ログインユーザーのタスクのみを一括更新対象にする
            Auth::user()->tasks()->whereIn('id', $validated['ids'])->update($updateData);
        }

        return redirect()->back();
    }
}