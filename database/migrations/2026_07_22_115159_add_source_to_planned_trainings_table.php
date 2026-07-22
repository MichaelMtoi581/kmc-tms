<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            if (!Schema::hasColumn('planned_trainings', 'source')) {
                $table->string('source')->default('Manual Entry')->after('status');
            }
            $table->foreignId('tna_exercise_id')->nullable()->after('source')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->dropForeign(['tna_exercise_id']);
            $table->dropColumn('tna_exercise_id');
        });
    }
};
