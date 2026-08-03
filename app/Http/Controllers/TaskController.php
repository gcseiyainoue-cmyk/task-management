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
use App\Services\RoutineService;
use App\Services\SmartTaskParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use IneFoundatiortia\Inertia;
use Illuminate\n\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * スマートタスク解析サービスのインスタンス
     */
    protected SmartTaskParserService $parserService;

    /**
     * ルーティン自動生成サービスのインスタンス
     */
    protected RoutineService $routineService; 

    /**
     * コンストラクタ依存性注入によるサービスの初期化
     * 
     * @param SmartTaskParserService $parserService
     * @param RoutineService $routineService
     */
    public function __construct(
        SmartTaskParserService $parserService,
        RoutineService $routineService 
    ) {
        $this->parserService = $parserService;
        $this->routineService = $routineService; 
    }

    /**
     * =====================================================================================
     * 【メソッド名】 index
     * 【概要】 タスク一覧画面の表示処理
     * =====================================================================================
     * ログインユーザーに紐づくタスクのみを抽出し、クエリパラメータ（view / category）に応じて
     * フィルタリングして返すとともに、ルーティンテンプレート一覧をフロントエンドへ提供します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return Response Inertiaレスポンス（Dashboard画面）
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();

        // 1. 今日のルーティンタスクが未作成なら自動生成
        $this->routineService->generateDailyTasksForUser($user);

        $view = $request->input('view');         // 例: 'today', 'all', 'routines' など
        $category = $request->input('category'); // 例: 'work', 'routines' など

        // 2. ルーティンテンプレート一覧の取得（サイドバーのバッジやルーティン管理画面で使用）
        $routineTemplates = $user->routineTemplates()->latest()->get();

        // 3. タスク取得時に routineTemplate リレーションを一緒にEager Loadingする
        $userTasksQuery = $user->tasks()->with('routineTemplate');
        $allTasks = (clone $userTasksQuery)->oldest()->get();
        $filteredTasksQuery = clone $userTasksQuery;

        $currentFilter = '';

        // 4. 絞り込み条件の判定
        if ($category === 'routines' || $view === 'routines') {
            $currentFilter = 'routines';
        } elseif ($category) {
            $filteredTasksQuery->where('category', $category);
            $currentFilter = $category;
        } else {
            $view = $view ?? 'today';
            
            if ($view === 'today') {
                $filteredTasksQuery->where('due_date', now()->toDateString());
            }
            
            $currentFilter = $view;
        }

        $filteredTasks = $filteredTasksQuery->oldest()->get();

        return Inertia::render('Dashboard', [
            'tasks' => $allTasks,
            'filteredTasks' => $filteredTasks,
            'routineTemplates' => $routineTemplates, 
            'currentCategory' => $currentFilter,    
            'currentView' => $view,
        ]);
    }

    /**
     * =====================================================================================
     * 【メソッド名】 store
     * 【概要】 単一タスクの新規作成処理
     * =====================================================================================
     * リクエスト内容をバリデーション後、パーサーで解析し、ログインユーザーに紐づけて安全に保存します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        // リクエストデータのバリデーション（カテゴリや優先度は未指定を許容）
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'category' => 'nullable|in:inbox,work,personal,growth,health,finance',
            'sub_category' => 'nullable|string',
            'priority' => 'nullable|in:high,medium,low',
        ]);

        // SmartTaskParserServiceを使用してタイトル文字列からタグやキーワードを解析
        $parsed = $this->parserService->parse($validated['title']);

        // 明示的なリクエスト値とパーサーによる解析結果を統合してタスクデータを構築
        $taskData = [
            'title' => $parsed['title'],
            'due_date' => $validated['due_date'] ?? $parsed['due_date'],
            'category' => isset($validated['category']) && $validated['category'] !== 'inbox' 
                ? $validated['category'] 
                : $parsed['category'],
            'sub_category' => $validated['sub_category'] ?? $parsed['sub_category'],
            'priority' => isset($validated['priority']) && $validated['priority'] !== 'medium' 
                ? $validated['priority'] 
                : $parsed['priority'],
            'is_completed' => false,
        ];

        // ログインユーザーのリレーションを介してタスクを新規作成し、外部キーを自動付与
        $task = Auth::user()->tasks()->create($taskData);

        return redirect()->back()->with('new_task_ids', [$task->id]);
    }
    
    /**
     * =====================================================================================
     * 【メソッド名】 storeBulk
     * 【概要】 複数行テキストからのタスク一括作成処理
     * =====================================================================================
     * 改行区切りのテキストを解析し、すべてのタスクをログインユーザーに紐づけて一括登録します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function storeBulk(Request $request): RedirectResponse
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
            // 一括作成時もログインユーザーに紐づけて安全に登録する
            $task = Auth::user()->tasks()->create($taskData);
            $newTaskIds[] = $task->id;
        });

        return redirect()->back()->with('new_task_ids', $newTaskIds);
    }

    /**
     * =====================================================================================
     * 【メソッド名】 update
     * 【概要】 タスクの更新処理
     * =====================================================================================
     * TaskPolicy による所有権・認可チェックを実行し、許可された場合のみ更新を適用します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @param Task $task 更新対象のタスクモデル
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        // TaskPolicy の update メソッドを呼び出し、他人のタスク改変を防止する
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'is_completed' => 'nullable|boolean',
            'due_date' => 'nullable|date',
            'category' => 'nullable|in:inbox,work,personal,growth,health,finance',
            'sub_category' => 'nullable|string',
            'priority' => 'nullable|string',
            'routine_template_id' => 'nullable|exists:routine_templates,id',
        ]);

        $task->update($validated);

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 removeRoutine
     * 【概要】 ルーティンの紐づき解除およびテンプレート削除処理
     * =====================================================================================
     * ルーティンの紐づきを解除し、テンプレートも削除して完全に通常タスクに一本化します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @param Task $task 対象のタスクモデル
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function removeRoutine(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        \DB::transaction(function () use ($task) {
            $routineTemplate = $task->routineTemplate;

            // 1. タスクの routine_template_id を null に更新して単発タスクにする
            $task->update(['routine_template_id' => null]);

            // 2. 大元のルーティンテンプレートを削除して自動再生成を防ぐ
            if ($routineTemplate) {
                $routineTemplate->delete();
            }
        });

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 destroy
     * 【概要】 単一タスクの削除処理
     * =====================================================================================
     * TaskPolicy による所有権・認可チェックを実行し、許可された場合のみ削除します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @param Task $task 削除対象のタスクモデル
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function destroy(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        // データベースのトランザクションを使って安全に同時削除する
        \DB::transaction(function () use ($task) {
            // もしルーティン由来のタスクで、かつ親テンプレートを一緒に消す場合
            if ($task->routine_template_id) {
                $routineTemplate = $task->routineTemplate;
                if ($routineTemplate) {
                    $routineTemplate->delete(); // 親のテンプレートを削除すれば、二度と再生成されなくなる
                }
            }

            $task->delete();
        });

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 bulkDestroy
     * 【概要】 複数タスクの一括削除処理
     * =====================================================================================
     * 他ユーザーのタスクIDが含まれていても除外され、自身のタスクのみ安全に削除します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:tasks,id'],
        ]);

        // ログインユーザーの所有するタスクに限定して一括削除を実行する
        Auth::user()->tasks()->whereIn('id', $request->ids)->delete();

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 bulkUpdate
     * 【概要】 複数タスクの一括更新処理
     * =====================================================================================
     * 他ユーザーのタスクが巻き添えにならないよう、ログインユーザーのスコープ内で一括更新します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function bulkUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:tasks,id'],
            'is_completed' => ['nullable', 'boolean'],
            'due_date' => ['nullable', 'date'],
            'category' => ['nullable', 'in:inbox,work,personal,growth,health,finance'],
            'sub_category' => ['nullable', 'string'],
            'priority' => ['nullable', 'in:high,medium,low'],
            'routine_template_id' => ['nullable', 'exists:routine_templates,id'],
        ]);

        $updateData = collect($validated)
            ->except('ids')
            ->filter(fn($value) => !is_null($value))
            ->toArray();

        if (!empty($updateData)) {
            // ログインユーザーのタスクのみを一括更新対象にする
            Auth::user()->tasks()->whereIn('id', $validated['ids'])->update($updateData);
        }

        return redirect()->back();
    }
}