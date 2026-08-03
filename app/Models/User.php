<?php
/**
 * =====================================================================================
 * 【ファイル名】 User.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（モデル / 認証・ユーザーデータ統括）
 * =====================================================================================
 * 【実務における設計思想】
 * Laravelの認証機能の中心となるユーザーモデルです。
 * 属性のホワイトリスト化や機密情報の秘匿（Hidden）を属性ベースでエレガントに定義し、
 * ユーザーが所持する「単発および自動生成されたタスク（tasks）」と
 * 「ルーティンテンプレート（routineTemplates）」の一対多のリレーションを一元管理します。
 */
namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * データベース属性のキャスト定義（パスワードのハッシュ化やメール認証日時の型変換）
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ユーザーが所有するすべてのタスク一覧を取得（単発タスク ＋ ルーティン生成タスク）
     * 
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * ユーザーが登録しているすべてのルーティンテンプレート一覧を取得
     * 
     * @return HasMany
     */
    public function routineTemplates(): HasMany
    {
        return $this->hasMany(RoutineTemplate::class);
    }
}