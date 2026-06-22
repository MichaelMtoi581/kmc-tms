<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Planned Trainings — staff_id, department_id, financial_year_id,
        // training_category_id FKs were already dropped by a failed migration.
        // Re-add them with restrictOnDelete.
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->foreign('staff_id')->references('id')->on('staff')->restrictOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->restrictOnDelete();
            $table->foreign('financial_year_id')->references('id')->on('financial_years')->restrictOnDelete();
            $table->foreign('training_category_id')->references('id')->on('training_categories')->restrictOnDelete();
        });

        // Unplanned Trainings — still have cascade, switch to restrict.
        Schema::table('unplanned_trainings', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['financial_year_id']);
            $table->dropForeign(['training_category_id']);

            $table->foreign('staff_id')->references('id')->on('staff')->restrictOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->restrictOnDelete();
            $table->foreign('financial_year_id')->references('id')->on('financial_years')->restrictOnDelete();
            $table->foreign('training_category_id')->references('id')->on('training_categories')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['financial_year_id']);
            $table->dropForeign(['training_category_id']);

            $table->foreign('staff_id')->references('id')->on('staff')->cascadeOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
            $table->foreign('financial_year_id')->references('id')->on('financial_years')->cascadeOnDelete();
            $table->foreign('training_category_id')->references('id')->on('training_categories')->cascadeOnDelete();
        });

        Schema::table('unplanned_trainings', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['department_id']);
            $table->dropForeign(['financial_year_id']);
            $table->dropForeign(['training_category_id']);

            $table->foreign('staff_id')->references('id')->on('staff')->cascadeOnDelete();
            $table->foreign('department_id')->references('id')->on('departments')->cascadeOnDelete();
            $table->foreign('financial_year_id')->references('id')->on('financial_years')->cascadeOnDelete();
            $table->foreign('training_category_id')->references('id')->on('training_categories')->cascadeOnDelete();
        });
    }
};
