<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('routine_templates', function (Blueprint $table) {
            $table->id();
            
            // 外部キー：誰のルーティンか（ユーザー削除時はルーティンも削除）
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('title');
            
            // カテゴリ（TaskController のバリデーションルールに準拠）
            $table->enum('category', ['inbox', 'work', 'personal', 'growth', 'health', 'finance'])
                  ->default('inbox');
                  
            $table->string('sub_category')->nullable();
            
            // 優先度
            $table->enum('priority', ['high', 'medium', 'low'])
                  ->default('medium');

            // 繰返し頻度（拡張用：daily, weekly 等）
            $table->string('frequency')->default('daily');

            // ルーティンの有効/無効フラグ
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            
            // パフォーマンス最適化：ユーザーごとのアクティブなルーティン取得を高速化
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routine_templates');
    }
};