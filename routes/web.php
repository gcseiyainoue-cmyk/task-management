<?php
/**
 * =====================================================================================
 * 【ファイル名】 routes/web.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（ルーティング / エンドポイント定義）
 * =====================================================================================
 * 【実務における設計思想】
 * LaravelとInertia.jsを結合するアプリケーションのルーティング定義ファイルです。
 * 認証（auth）およびメール認証（verified）ミドルウェアによってアクセス制御を行い、
 * 画面遷移（Inertia::render）とデータ操作の経路を整理しています。
 * 特に、静的なパス（/guide/... や /tasks/bulk など）を動的パラメータ
 * （{task} など）よりも上に記述することで、Laravelのルーティングにおける
 * 意図しないマッチング（競合バグ）を防ぐ堅牢な順序設計がなされています。
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RoutineController; 
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// 認証・メール認証が必須のグループ
Route::middleware(['auth', 'verified'])->group(function () {
    // ダッシュボード・コードガイドページ
    Route::get('/guide/code-guide', function () {
        return Inertia::render('Guide/DashboardCodeGuide');
    })->name('dashboard.code-guide');

    // ダッシュボードメインページ
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');

    // タスク管理の使い方ガイドページ
    Route::get('/guide/tasks', function () {
        return Inertia::render('Guide/TaskGuide');
    })->name('tasks.guide');

    // =====================================================================
    // 🔄 ルーティンテンプレート管理ルート（ダッシュボード統合型）
    // =====================================================================
    Route::post('/routines', [RoutineController::class, 'store'])->name('routines.store');
    Route::put('/routines/{routine}', [RoutineController::class, 'update'])->name('routines.update');
    Route::patch('/routines/{routine}/toggle', [RoutineController::class, 'toggle'])->name('routines.toggle'); // ★ PATCHに変更
    Route::delete('/routines/{routine}', [RoutineController::class, 'destroy'])->name('routines.destroy');

    // 一括処理・通常の作成ルート（{task} よりも上に配置）
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/bulk', [TaskController::class, 'storeBulk'])->name('tasks.store-bulk');
    Route::delete('/tasks/bulk', [TaskController::class, 'bulkDestroy'])->name('tasks.bulk-destroy');
    Route::patch('/tasks/bulk', [TaskController::class, 'bulkUpdate'])->name('tasks.bulk-update');

    // 個別処理ルート（{task} を含むものは下に配置）
    Route::post('/tasks/{task}/remove-routine', [TaskController::class, 'removeRoutine'])->name('tasks.remove-routine');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});

Route::middleware('auth')->group(function () {
    // プロフィール関連
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';