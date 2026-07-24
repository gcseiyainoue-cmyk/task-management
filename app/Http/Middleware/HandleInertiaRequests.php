<?php
/**
 * =====================================================================================
 * 【ファイル名】 HandleInertiaRequests.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（ミドルウェア / 共有データプロバイダ）
 * =====================================================================================
 * 【実務における設計思想】
 * Inertia.js を利用する際、すべてのページリクエストにおいて共通してフロントエンドに
 * 渡すデータ（Shared Props）を定義するミドルウェアです。
 * 認証済みユーザー情報（`auth.user`）に加え、タスク作成・一括処理時にフロント側で
 * 新規追加されたタスクをハイライトやアニメーションで視覚的に識別できるようにするため、
 * セッションのフラッシュデータ（`new_task_ids`）をクロージャーによる遅延評価で安全に
 * フロントエンドへ共有する設計としています。
 */
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'new_task_ids' => fn () => $request->session()->get('new_task_ids'),
            ],
        ];
    }
}