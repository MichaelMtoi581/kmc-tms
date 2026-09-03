<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unplanned_trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable()->change();
            $table->unsignedBigInteger('training_category_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('unplanned_trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable(false)->change();
            $table->unsignedBigInteger('training_category_id')->nullable(false)->change();
        });
    }
};
