<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();
        $today = now()->toDateString();

        // 1. 全タスク（期限順で固定・参照用）
        $allTasks = $user->tasks()
            ->orderByRaw('due_date IS NULL ASC')
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. 今日のタスク（🟢 純粋に「今日が期限」かつ「未完了」のもの）
        // ※ステータスの「完了」コードが 2 だと仮定しています。適宜プロジェクトの数値に合わせてください。
        $todayTasks = $user->tasks()
            ->whereDate('due_date', $today) 
            ->orderBy('sort_order', 'asc')
            ->get();

        // 3. 🚨【新設】期限切れのタスク（「今日より前が期限」かつ「未完了」のもの）
        $overdueTasks = $user->tasks()
            ->whereDate('due_date', '<', $today)
            ->where('status', '!=', 2) // 完了済みのタスクは除外
            ->orderBy('due_date', 'asc') // 古い順に並べる
            ->get();

        return Inertia::render('Dashboard', [
            'allTasks' => $allTasks,
            'todayTasks' => $todayTasks,
            'overdueTasks' => $overdueTasks, // 👈 フロントエンドへ渡す
        ]);
    }

    public function create()
    {
        return Inertia::render('Tasks/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'category' => 'required|string|max:20',
        ], [
            'due_date.after_or_equal' => '期限には、今日以降の日付を指定してください。',
        ], [
            'title' => 'タスク名',
            'due_date' => '期限',
            'category' => 'カテゴリ',
        ]);

        $request->user()->tasks()->create($validated);

        return redirect()->route('dashboard')->with('message', 'タスクを作成しました！');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return Inertia::render('Tasks/Edit', [
            'task' => $task
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            // ★ここを修正: 更新時は期限切れタスクを保存できるようにする
            'due_date' => 'sometimes|nullable|date', 
            'status' => 'sometimes|required|integer|in:0,1,2',
            'category' => 'sometimes|required|string|max:20',
        ], [
            // エラーメッセージ（必要であれば）
        ], [
            'title' => 'タスク名',
            'due_date' => '期限',
            'status' => 'ステータス',
            'category' => 'カテゴリ',
        ]);

        $task->update($validated);

        return redirect()->route('dashboard')->with('message', 'タスクを更新しました！');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $task->delete();

        return redirect()->route('dashboard')->with('message', 'タスクを削除しました！');
    }

    // 並び替え用メソッド（Inertiaエラー回避のためリダイレクトを返す）
    public function reorder(Request $request)
    {
        $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->tasks as $taskData) {
            $task = auth()->user()->tasks()->findOrFail($taskData['id']);
            $task->update(['sort_order' => $taskData['sort_order']]);
        }

        // Inertiaのリクエストに応答するためリダイレクトを返す
        return redirect()->back();
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tasks,id',
            'status' => 'nullable|integer|in:0,1,2',
            'due_date' => 'nullable|date',
        ]);

        // 更新するデータを動的に生成
        $updateData = [];
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }
        if ($request->has('due_date')) {
            $updateData['due_date'] = $request->due_date;
        }

        if (!empty($updateData)) {
            $request->user()->tasks()
                ->whereIn('id', $request->ids)
                ->update($updateData);
        }

        return redirect()->back()->with('message', 'タスクを一括更新しました。');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tasks,id',
        ]);

        $request->user()->tasks()
            ->whereIn('id', $request->ids)
            ->delete();

        return redirect()->back()->with('message', 'タスクを一括削除しました。');
    }

}