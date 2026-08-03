<?php
/**
 * =====================================================================================
 * 【ファイル名】 RoutineController.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（コントローラー / ルーティン管理・HTTPリクエスト処理）
 * =====================================================================================
 * 【実務における設計思想】
 * ルーティンテンプレートのCRUD操作（新規作成、更新、有効/無効切り替え、削除）を担うコントローラーです。
 * すべてのエンドポイントにおいて、ログインユーザーの紐付け（$request->user()）や、
 * 対象リソースの所有権チェック（$routine->user_id !== $request->user()->id による403認可制御）を
 * 徹底し、不正アクセスやデータ漏洩を防ぐ堅牢な設計になっています。
 * また、バリデーションによる入力値の厳格な検証と、状態トグル処理における柔軟な引数処理を実装していますｗ。
 */
namespace App\Http\Controllers;

use App\Models\RoutineTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RoutineController extends Controller
{
    /**
     * =====================================================================================
     * 【メソッド名】 store
     * 【概要】 新規ルーティンテンプレートのバリデーションおよび永続化処理
     * =====================================================================================
     * フォームから送信されたパラメータを厳格に検証し、現在認証されているユーザーに
     * 紐づくルーティンテンプレートとして安全にデータベースへ保存します。
     * 
     * @param Request $request HTTPリクエストインスタンス
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sub_category' => 'required|string',
            'priority' => 'required|string',
            'frequency_type' => 'required|string|in:interval,day_of_week',
            'interval_days' => 'required_if:frequency_type,interval|nullable|integer|min:1',
            'day_of_week' => 'required_if:frequency_type,day_of_week|nullable|integer|between:0,6',
        ]);

        $request->user()->routineTemplates()->create($validated);

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 update
     * 【概要】 既存ルーティンテンプレートの所有権検証および更新処理
     * =====================================================================================
     * 操作対象のルーティンがログインユーザーのものであるかを厳密に検証（IDの一致確認）し、
     * 不一致の場合は403エラーをスローします。検証通過後、入力値をバリデーションして更新します。
     * 
     * @param Request $request HTTPリクエストインスタンス
     * @param RoutineTemplate $routine 更新対象のルーティンテンプレートモデル
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function update(Request $request, RoutineTemplate $routine): RedirectResponse
    {
        // 自分のデータかチェック（セキュリティ・認可制御）
        if ($routine->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'sub_category' => 'required|string',
            'priority' => 'required|string',
            'frequency_type' => 'required|string|in:interval,day_of_week',
            'interval_days' => 'required_if:frequency_type,interval|nullable|integer|min:1',
            'day_of_week' => 'required_if:frequency_type,day_of_week|nullable|integer|between:0,6',
        ]);

        $routine->update($validated);

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 toggle
     * 【概要】 ルーティンテンプレートの有効/無効状態（is_active）の切り替え処理
     * =====================================================================================
     * 所有権の検証を行った後、リクエストで明示的に指定された値、または現在の状態の反転値
     * を用いて有効フラグを更新します。
     * 
     * @param Request $request HTTPリクエストインスタンス
     * @param RoutineTemplate $routine 状態を切り替えるルーティンテンプレートモデル
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function toggle(Request $request, RoutineTemplate $routine): RedirectResponse
    {
        if ($routine->user_id !== $request->user()->id) {
            abort(403);
        }

        $routine->update([
            'is_active' => $request->input('is_active', !$routine->is_active)
        ]);

        return redirect()->back();
    }

    /**
     * =====================================================================================
     * 【メソッド名】 destroy
     * 【概要】 ルーティンテンプレートの所有権検証および削除処理
     * =====================================================================================
     * 操作対象のルーティンがログインユーザーのものであるかを検証した上で、
     * データベースから安全に削除します。
     * 
     * @param Request $request HTTPリクエストインスタンス
     * @param RoutineTemplate $routine 削除対象のルーティンテンプレートモデル
     * @return RedirectResponse 処理完了後のリダイレクトレスポンス
     */
    public function destroy(Request $request, RoutineTemplate $routine): RedirectResponse
    {
        if ($routine->user_id !== $request->user()->id) {
            abort(403);
        }

        $routine->delete();

        return redirect()->back();
    }
}