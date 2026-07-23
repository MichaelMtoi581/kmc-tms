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
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->foreignId('training_category_id')->nullable()->change();
            $table->foreignId('training_institution_id')->nullable()->change();
            $table->foreignId('funding_source_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->foreignId('training_category_id')->nullable(false)->change();
            $table->foreignId('training_institution_id')->nullable(false)->change();
            $table->foreignId('funding_source_id')->nullable(false)->change();
        });
    }
};
