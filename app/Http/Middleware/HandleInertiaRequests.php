<?php
/**
 * =====================================================================================
 * 【ファイル名】 HandleInertiaRequests.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（ミドルウェア / 共有データプロバイダ）
 * =====================================================================================
 * 【実務における設計思想】
 * Inertia.js を利用する際、すべてのページリクエストにおいて共通してフロントエンドに
 * 渡すデータ（Shared Props）を定義するミドルウェアです。
 * 認証済みユーザー情報（auth.user）に加え、タスク作成・一括処理時にフロント側で
 * 新規追加されたタスクをハイライトやアニメーションで視覚的に識別できるようにするため、
 * セッションのフラッシュデータ（new_task_ids）をクロージャーによる遅延評価で安全に
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
     * =====================================================================================
     * 【メソッド名】 version
     * 【概要】 フロントエンドアセットのバージョン判定処理
     * =====================================================================================
     * アセットが更新された際にブラウザ側へキャッシュの無効化を通知するためのバージョン文字列を返却します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return string|null バージョン文字列
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * =====================================================================================
     * 【メソッド名】 share
     * 【概要】 すべてのページリクエストで共通共有されるプロパティの定義処理
     * =====================================================================================
     * 認証済みユーザー情報や、新規作成されたタスクIDのフラッシュデータをクロージャーによる
     * 遅延評価を用いて安全にフロントエンド（Inertia.js）へ共有します。
     *
     * @param Request $request HTTPリクエストインスタンス
     * @return array<string, mixed> 共有データの連想配列
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