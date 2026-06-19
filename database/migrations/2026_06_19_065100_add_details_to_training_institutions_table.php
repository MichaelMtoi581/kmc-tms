<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_institutions', function (Blueprint $table) {
            $table->string('name')->unique()->after('id');
            $table->string('location')->nullable()->after('name');
            $table->string('phone')->nullable()->after('location');
            $table->string('email')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('training_institutions', function (Blueprint $table) {
            $table->dropColumn(['name', 'location', 'phone', 'email']);
        });
    }
};
