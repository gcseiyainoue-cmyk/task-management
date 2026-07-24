<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // ▼ どのユーザーのタスクかを紐付けるカラムを追加
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->date('due_date')->nullable();
            $table->string('category')->default('private'); // 'work' または 'private'
            $table->string('priority')->default('medium');   // 'high', 'medium', 'low'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};