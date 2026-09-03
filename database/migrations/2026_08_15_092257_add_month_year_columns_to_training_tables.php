<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['planned_trainings', 'unplanned_trainings'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('start_month')->nullable()->after('end_date');
                $table->string('start_year')->nullable()->after('start_month');
                $table->string('end_month')->nullable()->after('start_year');
                $table->string('end_year')->nullable()->after('end_month');
            });
        }
    }

    public function down(): void
    {
        foreach (['planned_trainings', 'unplanned_trainings'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['start_month', 'start_year', 'end_month', 'end_year']);
            });
        }
    }
};
