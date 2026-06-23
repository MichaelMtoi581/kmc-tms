<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnplannedTraining extends Model
{
    protected $fillable = [
        'course_title',
        'staff_id',
        'department_id',
        'financial_year_id',
        'training_category_id',
        'training_institution_id',
        'funding_source_id',
        'start_date',
        'end_date',
        'venue',
        'cost',
        'status',
        'duration_type',
        'source',
        'description',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($training) {
            if ($training->start_date && $training->end_date) {
                $months = $training->start_date->diffInMonths($training->end_date);
                $training->duration_type = $months >= 6 ? 'Long' : 'Short';
            } else {
                $training->duration_type = 'Short';
            }
        });
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    public function trainingCategory()
    {
        return $this->belongsTo(TrainingCategory::class);
    }

    public function trainingInstitution()
    {
        return $this->belongsTo(TrainingInstitution::class);
    }

    public function fundingSource()
    {
        return $this->belongsTo(FundingSource::class);
    }
}
