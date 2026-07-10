<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\FinancialYear;
use App\Models\FundingSource;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\TrainingInstitution;
use App\Models\UnplannedTraining;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class UnplannedTrainingImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    public array $failures = [];
    public int $rowsImported = 0;
    public int $duplicatesSkipped = 0;

    private array $seen = [];

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function model(array $row)
    {
        $this->rowsImported++;

        $staff = Staff::where('check_number', $row['check_number'])->first();
        $department = Department::where('name', $row['department'])->first();
        $financialYear = FinancialYear::where('year_name', $row['financial_year'])->first();
        $category = TrainingCategory::where('name', $row['category'])->first();
        $institution = TrainingInstitution::where('name', $row['institution'] ?? '')->first();
        $fundingSource = FundingSource::where('name', $row['funding_source'] ?? '')->first();

        $courseTitle = $row['course_title'] ?? $row['training_title'] ?? '';
        $staffId = $staff?->id;
        $financialYearId = $financialYear?->id;
        $key = "{$staffId}|{$courseTitle}|{$financialYearId}";

        if (isset($this->seen[$key]) || UnplannedTraining::where('staff_id', $staffId)
            ->where('course_title', $courseTitle)
            ->where('financial_year_id', $financialYearId)
            ->exists()
        ) {
            $this->rowsImported--;
            $this->duplicatesSkipped++;
            return null;
        }

        $this->seen[$key] = true;

        return new UnplannedTraining([
            'course_title' => $courseTitle,
            'staff_id' => $staffId,
            'department_id' => $department?->id ?? $staff?->department_id,
            'financial_year_id' => $financialYearId,
            'training_category_id' => $category?->id,
            'training_institution_id' => $institution?->id,
            'funding_source_id' => $fundingSource?->id,
            'start_date' => $this->parseDate($row['start_date'] ?? null),
            'end_date' => $this->parseDate($row['end_date'] ?? null),
            'venue' => $row['venue'] ?? null,
            'cost' => $row['cost'] ?? 0,
            'status' => 'Planned',
            'source' => 'import',
            'description' => $row['description'] ?? null,
            'remarks' => $row['remarks'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'course_title' => 'required|string|max:255',
            'check_number' => 'required|string|exists:staff,check_number',
            'department' => 'required|string|exists:departments,name',
            'financial_year' => 'required|string|exists:financial_years,year_name',
            'category' => 'required|string|exists:training_categories,name',
        ];
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                ->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception) {
            return null;
        }
    }
}
