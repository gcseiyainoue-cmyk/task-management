<?php
/**
 * =====================================================================================
 * 【ファイル名】 Task.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（モデル / データ永続化・Eloquent ORM）
 * =====================================================================================
 * 【実務における設計思想】
 * データベースの `tasks` テーブルとマッピングされるEloquentモデルです。
 * マスアサインメント脆弱性を防ぐための `$fillable` によるホワイトリスト定義と、
 * データベースから取得した値の型を自動変換する `$casts`（キャスト定義）を管理しています。
 * 特に `is_completed` を boolean にキャストすることで、フロントエンドとのやり取りや
 * 判定処理における型の安全性を担保しています。さらに `due_date` を date にキャスト
 * することで、モデル取得時に自動で Carbon インスタンスに変換され、日付操作を安全に行えます。
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_completed',
        'due_date',
        'category',
        'sub_category',
        'priority',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date' => 'date', // ▼ 推奨: 日付を Carbon インスタンスとして扱うためのキャストを追加
    ];

    /**
     * JSONシリアライズ時の日付フォーマットを Y-m-d に統一する
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }
    
}