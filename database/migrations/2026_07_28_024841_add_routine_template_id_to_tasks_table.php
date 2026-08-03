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
        Schema::table('tasks', function (Blueprint $table) {
            // ルーティン由来でない単発タスクもあるため nullable
            // ルーティン元が削除された場合は null にしてタスク自体は保持 (nullOnDelete)
            $table->foreignId('routine_template_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('routine_templates')
                  ->nullOnDelete();

            // 補填チェック（`whereDate('due_date', ...)->whereNotNull('routine_template_id')`）の高速化
            $table->index(['user_id', 'due_date', 'routine_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['routine_template_id']);
            $table->dropIndex(['user_id', 'due_date', 'routine_template_id']);
            $table->dropColumn('routine_template_id');
        });
    }
};