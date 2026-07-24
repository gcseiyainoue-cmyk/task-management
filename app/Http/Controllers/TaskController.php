<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests; // 追加

    public function index($category = 'all')
    {
        $query = Task::oldest();

        if ($category !== 'all' && $category !== 'today') {
            $query->where('category', $category);
        }

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::oldest()->get(),
            'filteredTasks' => $category === 'today' 
                ? Task::where('due_date', now()->toDateString())->oldest()->get() 
                : ($category === 'all' ? Task::oldest()->get() : Task::where('category', $category)->oldest()->get()),
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

        $parsed = $this->parseSmartTask($validated['title']);

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
            $taskData = $this->parseSmartTask($line);
            $task = Task::create($taskData);
            $newTaskIds[] = $task->id;
        });

        return redirect()->back()->with('new_task_ids', $newTaskIds);
    }

    private function parseSmartTask($line)
    {
        $category = 'inbox';
        $subCategory = 'general';
        $priority = 'medium'; 
        $dueDate = now()->toDateString(); 
        $cleanTitle = $line;

        $priorityPatterns = [
            '/(?:\[hi\]|\(hi\)|\bhi\b|(?<=[^\x00-\x7F])hi)/ui' => 'high',
            '/(?:\[lo\]|\(lo\)|\blo\b|(?<=[^\x00-\x7F])lo)/ui' => 'low',
            '/(?:\[md\]|\(md\)|\bmd\b|(?<=[^\x00-\x7F])md)/ui' => 'medium',
        ];
        foreach ($priorityPatterns as $pattern => $prioVal) {
            if (preg_match($pattern, $cleanTitle)) {
                $priority = $prioVal;
                $cleanTitle = preg_replace($pattern, '', $cleanTitle);
                break;
            }
        }

        $categoryPatterns = [
            '/(?:\[wo\]|\(wo\)|\bwo\b|(?<=[^\x00-\x7F])wo)/ui' => ['work', 'task'],
            '/(?:\[pe\]|\(pe\)|\bpe\b|(?<=[^\x00-\x7F])pe)/ui' => ['personal', 'shopping'],
            '/(?:\[gr\]|\(gr\)|\bgr\b|(?<=[^\x00-\x7F])gr)/ui' => ['growth', 'learning'],
            '/(?:\[he\]|\(he\)|\bhe\b|(?<=[^\x00-\x7F])he)/ui' => ['health', 'fitness'],
            '/(?:\[fi\]|\(fi\)|\bfi\b|(?<=[^\x00-\x7F])fi)/ui' => ['finance', 'budget'],
        ];
        foreach ($categoryPatterns as $pattern => $catInfo) {
            if (preg_match($pattern, $cleanTitle)) {
                $category = $catInfo[0];
                $subCategory = $catInfo[1];
                $cleanTitle = preg_replace($pattern, '', $cleanTitle);
                break;
            }
        }

        if ($priority === 'medium') {
            if (Str::contains($cleanTitle, ['急ぎ', '緊急', '最優先', '今すぐ', '至急', '重要', '!'])) {
                $priority = 'high';
            } elseif (Str::contains($cleanTitle, ['後で', 'いつでも', 'いつか', '急ぎではない', '暇なとき'])) {
                $priority = 'low';
            }
        }

        if ($category === 'inbox') {
            if (Str::contains($cleanTitle, ['ミーティング', '会議', '資料', '修正', '開発', 'バグ', 'PR', 'メール', '返信'])) {
                $category = 'work';
                $subCategory = 'task';
            } elseif (Str::contains($cleanTitle, ['買い物', 'スーパー', '食材', '掃除', '洗濯', '片付け', '購入'])) {
                $category = 'personal';
                $subCategory = 'shopping';
            } elseif (Str::contains($cleanTitle, ['勉強', '読書', '学習', '英語', '資格', 'スキル', '本'])) {
                $category = 'growth';
                $subCategory = 'learning';
            } elseif (Str::contains($cleanTitle, ['ジム', 'ランニング', '筋トレ', '病院', '薬', '運動', '散歩'])) {
                $category = 'health';
                $subCategory = 'fitness';
            } elseif (Str::contains($cleanTitle, ['銀行', '振込', '家計簿', '税金', '支払い', 'ATM'])) {
                $category = 'finance';
                $subCategory = 'budget';
            }
        }

        $dateKeywords = [
            '明後日' => fn() => now()->addDays(2)->toDateString(),
            'あさって' => fn() => now()->addDays(2)->toDateString(),
            '明日' => fn() => now()->addDay()->toDateString(),
            'あした' => fn() => now()->addDay()->toDateString(),
            '今週末' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '週末' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '月末' => fn() => now()->endOfMonth()->toDateString(),
            '来週' => fn() => now()->addWeek()->toDateString(),
            '月曜日' => fn() => now()->next(Carbon::MONDAY)->toDateString(),
            '月曜' => fn() => now()->next(Carbon::MONDAY)->toDateString(),
            '火曜日' => fn() => now()->next(Carbon::TUESDAY)->toDateString(),
            '火曜' => fn() => now()->next(Carbon::TUESDAY)->toDateString(),
            '水曜日' => fn() => now()->next(Carbon::WEDNESDAY)->toDateString(),
            '水曜' => fn() => now()->next(Carbon::WEDNESDAY)->toDateString(),
            '木曜日' => fn() => now()->next(Carbon::THURSDAY)->toDateString(),
            '木曜' => fn() => now()->next(Carbon::THURSDAY)->toDateString(),
            '金曜日' => fn() => now()->next(Carbon::FRIDAY)->toDateString(),
            '金曜' => fn() => now()->next(Carbon::FRIDAY)->toDateString(),
            '土曜日' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '土曜' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '日曜日' => fn() => now()->next(Carbon::SUNDAY)->toDateString(),
            '日曜' => fn() => now()->next(Carbon::SUNDAY)->toDateString(),
        ];

        foreach ($dateKeywords as $keyword => $calculator) {
            if (Str::contains($cleanTitle, $keyword)) {
                $dueDate = $calculator();
                $cleanTitle = str_replace($keyword, '', $cleanTitle);
                break;
            }
        }

        $cleanTitle = preg_replace('/^[、。\s]+|[、。\s]+$/u', '', $cleanTitle);
        $cleanTitle = preg_replace('/[、。\s]{2,}/u', '', $cleanTitle);
        $cleanTitle = trim($cleanTitle);

        return [
            'title' => $cleanTitle,
            'category' => $category,
            'sub_category' => $subCategory,
            'priority' => $priority,
            'due_date' => $dueDate,
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
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