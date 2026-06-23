<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['planned_trainings', 'unplanned_trainings'] as $table) {
            DB::statement("
                UPDATE {$table}
                SET duration_type = IF(TIMESTAMPDIFF(MONTH, start_date, end_date) >= 6, 'Long', 'Short')
                WHERE start_date IS NOT NULL AND end_date IS NOT NULL
            ");
        }
    }

    public function down(): void
    {
        // Data backfill only — nothing structural to reverse.
    }
};
