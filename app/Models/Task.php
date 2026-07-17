<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'category',
        'sort_order',
    ];

    // ステータスの定数定義
    const STATUS_TODO = 0;        // 未着手
    const STATUS_IN_PROGRESS = 1; // 進行中
    const STATUS_COMPLETED = 2;   // 完了

    // ユーザーへのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}