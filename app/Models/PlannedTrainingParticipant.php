<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlannedTrainingParticipant extends Model
{
    protected $fillable = [
        'planned_training_id', 'tna_response_id',
    ];

    public function plannedTraining(): BelongsTo
    {
        return $this->belongsTo(PlannedTraining::class);
    }

    public function tnaResponse(): BelongsTo
    {
        return $this->belongsTo(TnaResponse::class);
    }
}
