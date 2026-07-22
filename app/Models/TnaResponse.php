<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TnaResponse extends Model
{
    protected $fillable = [
        'tna_exercise_id', 'check_number', 'full_name', 'department',
        'designation', 'training_needed', 'training_reason',
        'expected_outcome', 'priority', 'preferred_duration',
        'preferred_institution', 'remarks', 'imported_at',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(TnaExercise::class, 'tna_exercise_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'check_number', 'check_number');
    }

    public function plannedTrainings(): BelongsToMany
    {
        return $this->belongsToMany(PlannedTraining::class, 'planned_training_participants');
    }
}
