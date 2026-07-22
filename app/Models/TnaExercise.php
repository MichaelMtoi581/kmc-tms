<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TnaExercise extends Model
{
    protected $fillable = [
        'financial_year_id', 'title', 'description',
        'start_date', 'end_date', 'status', 'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function financialYear(): BelongsTo
    {
        return $this->belongsTo(FinancialYear::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(TnaResponse::class);
    }

    public function plannedTrainings(): BelongsToMany
    {
        return $this->belongsToMany(PlannedTraining::class, 'planned_training_participants');
    }

    public function isOpen(): bool
    {
        return $this->status === 'Open';
    }
}
