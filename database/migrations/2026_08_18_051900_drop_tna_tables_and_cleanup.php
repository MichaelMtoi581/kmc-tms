<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->dropForeign(['tna_exercise_id']);
            $table->dropColumn('tna_exercise_id');
        });

        Schema::dropIfExists('planned_training_participants');
        Schema::dropIfExists('tna_responses');
        Schema::dropIfExists('tna_exercises');
    }

    public function down(): void
    {
        Schema::create('tna_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_year_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('Open');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('tna_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tna_exercise_id')->constrained()->cascadeOnDelete();
            $table->string('check_number');
            $table->string('full_name');
            $table->string('department');
            $table->string('designation');
            $table->string('training_needed');
            $table->text('training_reason')->nullable();
            $table->text('expected_outcome')->nullable();
            $table->string('priority')->nullable();
            $table->string('preferred_duration')->nullable();
            $table->string('preferred_institution')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
            $table->unique(['tna_exercise_id', 'check_number', 'training_needed'], 'tna_resp_unique');
        });

        Schema::create('planned_training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planned_training_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tna_response_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['planned_training_id', 'tna_response_id'], 'ptp_unique');
        });

        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('tna_exercise_id')->nullable()->after('source');
            $table->foreign('tna_exercise_id')->references('id')->on('tna_exercises');
        });
    }
};
