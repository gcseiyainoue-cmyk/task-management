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
        Schema::table('routine_templates', function (Blueprint $table) {
            // 『何日毎』の際の間隔日数（例: 7日毎なら 7）
            $table->unsignedInteger('interval_days')->nullable()->after('frequency_type');

            // 『曜日毎』の際の曜日指定（0:日, 1:月, 2:火, 3:水, 4:木, 5:金, 6:土）
            $table->unsignedTinyInteger('day_of_week')->nullable()->after('interval_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routine_templates', function (Blueprint $table) {
            $table->dropColumn(['interval_days', 'day_of_week']);
        });
    }
};