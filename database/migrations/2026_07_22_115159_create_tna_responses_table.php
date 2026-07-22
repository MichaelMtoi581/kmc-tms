<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tna_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tna_exercise_id')->constrained()->cascadeOnDelete();
            $table->string('check_number');
            $table->string('full_name');
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('training_needed');
            $table->text('training_reason')->nullable();
            $table->text('expected_outcome')->nullable();
            $table->enum('priority', ['High', 'Medium', 'Low'])->default('Medium');
            $table->string('preferred_duration')->nullable();
            $table->string('preferred_institution')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();

            $table->unique(['tna_exercise_id', 'check_number', 'training_needed'], 'tna_resp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tna_responses');
    }
};
