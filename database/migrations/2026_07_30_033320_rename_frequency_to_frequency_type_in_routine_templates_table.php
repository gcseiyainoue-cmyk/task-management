<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routine_templates', function (Blueprint $table) {
            $table->renameColumn('frequency', 'frequency_type');
        });
    }

    public function down(): void
    {
        Schema::table('routine_templates', function (Blueprint $table) {
            $table->renameColumn('frequency_type', 'frequency');
        });
    }
};
