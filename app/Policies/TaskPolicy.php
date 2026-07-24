<?php
/**
 * =====================================================================================
 * 【ファイル名】 TaskPolicy.php
 * 【アーキテクチャ上の位置づけ】 認可層（セキュリティポリシー / アクセスコントロール）
 * =====================================================================================
 * 【実務における設計思想】
 * マルチユーザー環境における厳格なデータ所有権の検証とアクセス制御を担当します。
 * Laravelのポリシー機構を活用し、認証済みユーザーが他者のタスクに対して不正に
 * アクセス（閲覧・更新・削除）することを防ぐためのセキュリティ境界を定義します。
 * コントローラー側での $this->authorize() 呼び出しと密に連携し、
 * アプリケーション全体の安全性を担保する中核的な役割を果たします。
 */
namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * ユーザーがタスクの一覧画面（または全件）を閲覧する権限を持つか判定する
     * （実際の絞り込みはコントローラー側のクエリスコープで行うため、認証済みであれば許可する）
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * ユーザーが特定のタスクの詳細を閲覧する権限を持つか判定する
     * ログインユーザーのIDとタスクの所有者IDが完全に一致する場合のみ許可する
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * ユーザーが新規タスクを作成する権限を持つか判定する
     * 認証済みのユーザーであれば誰でもタスクの作成を許可する
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * ユーザーが特定のタスクを更新する権限を持つか判定する
     * 所有者本人（$user->id === $task->user_id）である場合のみ更新を許可し、他人の改変を厳格に防止する
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * ユーザーが特定のタスクを削除する権限を持つか判定する
     * 所有者本人（$user->id === $task->user_id）である場合のみ削除を許可し、他人の不正削除を厳格に防止する
     *
     * @param User $user
     * @param Task $task
     * @return bool
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }
}