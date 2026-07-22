<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planned_training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planned_training_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tna_response_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['planned_training_id', 'tna_response_id'], 'ptp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planned_training_participants');
    }
};
