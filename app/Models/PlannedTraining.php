<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Auditable;

class PlannedTraining extends Model
{
    use Auditable;
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
        'start_month',
        'start_year',
        'end_month',
        'end_year',
        'venue',
        'cost',
        'status',
        'duration_type',
        'source',
        'description',
        'remarks',
    ];

    public function getStartPeriodLabelAttribute(): ?string
    {
        return $this->start_month && $this->start_year
            ? strtoupper($this->start_month) . ', ' . $this->start_year
            : null;
    }

    public function getEndPeriodLabelAttribute(): ?string
    {
        return $this->end_month && $this->end_year
            ? strtoupper($this->end_month) . ', ' . $this->end_year
            : null;
    }

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
            } elseif ($training->start_month && $training->start_year && $training->end_month && $training->end_year) {
                $startIndex = self::monthIndex($training->start_month, $training->start_year);
                $endIndex = self::monthIndex($training->end_month, $training->end_year);
                $months = $endIndex - $startIndex;
                $training->duration_type = $months >= 6 ? 'Long' : 'Short';
            } else {
                $training->duration_type = 'Short';
            }

            $endDate = $training->end_date;
            if (!$endDate && $training->end_month && $training->end_year) {
                $monthNumber = self::monthNumber($training->end_month);
                $endDate = $monthNumber
                    ? \Carbon\Carbon::createFromDate((int) $training->end_year, $monthNumber, 1)->endOfMonth()
                    : null;
            }

            if ($endDate && $endDate->isPast() && $training->status !== 'Cancelled') {
                $training->status = 'Completed';
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

    private static function monthNumber(string $month): ?int
    {
        $map = [
            'january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5,
            'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10,
            'november' => 11, 'december' => 12,
        ];
        return $map[strtolower(trim($month))] ?? null;
    }

    private static function monthIndex(string $month, string $year): int
    {
        return ((int) $year) * 12 + (self::monthNumber($month) ?? 1);
    }
}
