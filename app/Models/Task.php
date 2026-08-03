<?php
/**
 * =====================================================================================
 * 【ファイル名】 Task.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（モデル / データ永続化・Eloquent ORM）
 * =====================================================================================
 * 【実務における設計思想】
 * データベースの tasks テーブルとマッピングされるEloquentモデルです。
 * マスアサインメント脆弱性を防ぐための $fillable によるホワイトリスト定義と、
 * データベースから取得した値の型を自動変換する $casts（キャスト定義）を管理しています。
 * 特に is_completed を boolean にキャストすることで、フロントエンドとのやり取りや
 * 判定処理における型の安全性を担保しています。さらに due_date を date にキャスト
 * することで、モデル取得時に自動で Carbon インスタンスに変換され、日付操作を安全に行えます。
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTimeInterface;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 一括割り当て（Mass Assignment）可能な属性のホワイトリスト
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'routine_template_id',
        'title',
        'is_completed',
        'due_date',
        'category',
        'sub_category',
        'priority',
    ];

    /**
     * データベースから取得した属性の自動型キャスト定義
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date',
    ];

    /**
     * JSONシリアライズ時の日付フォーマットを Y-m-d に統一する
     * 
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * このタスクを所有するユーザーを取得
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このタスクの生成元となったルーティンテンプレートを取得（手動作成の場合は null）
     * 
     * @return BelongsTo
     */
    public function routineTemplate(): BelongsTo
    {
        return $this->belongsTo(RoutineTemplate::class);
    }
}