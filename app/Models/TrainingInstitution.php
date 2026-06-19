<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingInstitution extends Model
{
    protected $fillable = [
        'name',
        'location',
        'phone',
        'email',
    ];
}
