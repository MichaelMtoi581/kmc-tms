<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // created, updated, deleted, imported
            $table->string('model_type'); // e.g. App\Models\PlannedTraining
            $table->unsignedBigInteger('model_id')->nullable();
            $table->longText('changes')->nullable(); // JSON diff
            $table->string('description'); // human-readable summary
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
