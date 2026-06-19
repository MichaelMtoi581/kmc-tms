<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'check_number',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'date_of_birth',
        'designation',
        'education_level',
        'department_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Full display name, e.g. "John A. Mwakasege".
     */
    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? $this->middle_name[0] . '. ' : '';

        return trim("{$this->first_name} {$middle}{$this->last_name}");
    }
}
