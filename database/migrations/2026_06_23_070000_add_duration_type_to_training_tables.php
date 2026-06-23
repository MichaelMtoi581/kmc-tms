<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->string('duration_type', 10)->default('Short')->after('status');
        });

        Schema::table('unplanned_trainings', function (Blueprint $table) {
            $table->string('duration_type', 10)->default('Short')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->dropColumn('duration_type');
        });

        Schema::table('unplanned_trainings', function (Blueprint $table) {
            $table->dropColumn('duration_type');
        });
    }
};
