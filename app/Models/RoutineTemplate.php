<?php
/**
 * =====================================================================================
 * 【ファイル名】 RoutineTemplate.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（モデル / 定期タスクテンプレート管理）
 * =====================================================================================
 * 【実務における設計思想】
 * データベースの routine_templates テーブルとマッピングされるEloquentモデルです。
 * ユーザーが設定した定期タスクの自動生成ルールを保持し、有効フラグ（is_active）の
 * boolean キャストや、生成される個別タスクとのリレーション（HasMany）、所有ユーザーとの
 * リレーション（BelongsTo）を管理します。
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoutineTemplate extends Model
{
    use HasFactory;

    /**
     * 一括割り当て（Mass Assignment）可能な属性のホワイトリスト
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'sub_category',
        'priority',
        'frequency_type',
        'interval_days',
        'day_of_week',
        'is_active',
    ];

    /**
     * データベースから取得した属性の自動型キャスト定義
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * このルーティンテンプレートを所有するユーザーを取得
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このテンプレートから生成された個別タスクの一覧を取得
     * 
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}