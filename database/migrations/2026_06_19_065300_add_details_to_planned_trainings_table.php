<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->string('course_title')->after('id');
            $table->foreignId('staff_id')->constrained()->cascadeOnDelete()->after('course_title');
            $table->foreignId('department_id')->constrained()->cascadeOnDelete()->after('staff_id');
            $table->foreignId('financial_year_id')->constrained()->cascadeOnDelete()->after('department_id');
            $table->foreignId('training_category_id')->constrained()->cascadeOnDelete()->after('financial_year_id');
            $table->foreignId('training_institution_id')->nullable()->constrained()->nullOnDelete()->after('training_category_id');
            $table->foreignId('funding_source_id')->nullable()->constrained()->nullOnDelete()->after('training_institution_id');
            $table->date('start_date')->nullable()->after('funding_source_id');
            $table->date('end_date')->nullable()->after('start_date');
            $table->string('venue')->nullable()->after('end_date');
            $table->decimal('cost', 12, 2)->default(0)->after('venue');
            $table->enum('status', ['Planned', 'Ongoing', 'Completed', 'Cancelled'])->default('Planned')->after('cost');
            $table->string('source')->default('manual')->after('status');
            $table->text('description')->nullable()->after('source');
            $table->text('remarks')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('planned_trainings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('staff_id');
            $table->dropConstrainedForeignId('department_id');
            $table->dropConstrainedForeignId('financial_year_id');
            $table->dropConstrainedForeignId('training_category_id');
            $table->dropConstrainedForeignId('training_institution_id');
            $table->dropConstrainedForeignId('funding_source_id');
            $table->dropColumn([
                'course_title', 'start_date', 'end_date', 'venue',
                'cost', 'status', 'source', 'description', 'remarks'
            ]);
        });
    }
};
