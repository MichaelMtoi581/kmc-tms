<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCategory extends Model
{
    protected $fillable = ['name'];

    public function plannedTraining()
    {
        return $this->hasMany(PlannedTraining::class);
    }

    public function unplannedTraining()
    {
        return $this->hasMany(UnplannedTraining::class);
    }
}
