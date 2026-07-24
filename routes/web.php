<?php
/**
 * =====================================================================================
 * 【ファイル名】 routes/web.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（ルーティング / エンドポイント定義）
 * =====================================================================================
 * 【実務における設計思想】
 * LaravelとInertia.jsを結合するアプリケーションのルーティング定義ファイルです。
 * 認証（auth）およびメール認証（verified）ミドルウェアによってアクセス制御を行い、
 * 画面遷移（Inertia::render）とデータ操作（TaskControllerの各アクション）の経路を整理しています。
 * 特に、静的なパス（/dashboard/code-guide や /tasks/bulk など）を動的パラメータ
 * （{category?} や {task}）を含むパスよりも上に記述することで、Laravelのルーティングにおける
 * 意図しないマッチング（競合バグ）を防ぐ堅牢な順序設計がなされています。
 */

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController; 
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
    // Index.vue コード・UI対応ガイドページ（※ パラメータなしの具体的なルートを上部に配置）
    Route::get('/dashboard/code-guide', function () {
        return Inertia::render('DashboardCodeGuide');
    })->name('dashboard.code-guide');

    // 5大カテゴリ別の個別ページ（ルート名を 'dashboard' に統一）
    Route::get('/dashboard/{category?}', [TaskController::class, 'index'])->name('dashboard');

    // タスク管理の使い方ガイドページ
    Route::get('/tasks/guide', function () {
        return Inertia::render('TaskGuide');
    })->name('tasks.guide');

    // 一括処理・通常の作成ルート（{task} よりも上に配置）
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/bulk', [TaskController::class, 'storeBulk'])->name('tasks.store-bulk');
    Route::delete('/tasks/bulk', [TaskController::class, 'bulkDestroy'])->name('tasks.bulk-destroy');
    Route::patch('/tasks/bulk', [TaskController::class, 'bulkUpdate'])->name('tasks.bulk-update');

    // 個別処理ルート（{task} を含むものは下に配置）
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