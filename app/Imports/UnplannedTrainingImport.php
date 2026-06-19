<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\FinancialYear;
use App\Models\FundingSource;
use App\Models\Staff;
use App\Models\TrainingCategory;
use App\Models\TrainingInstitution;
use App\Models\UnplannedTraining;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UnplannedTrainingImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $staff = Staff::where('check_number', $row['check_number'] ?? '')->first();
        $department = Department::where('name', $row['department'] ?? '')->first();
        $financialYear = FinancialYear::where('year_name', $row['financial_year'] ?? '')->first();
        $category = TrainingCategory::where('name', $row['category'] ?? '')->first();
        $institution = TrainingInstitution::where('name', $row['institution'] ?? '')->first();
        $fundingSource = FundingSource::where('name', $row['funding_source'] ?? '')->first();

        return new UnplannedTraining([
            'course_title' => $row['course_title'] ?? $row['training_title'] ?? '',
            'staff_id' => $staff?->id,
            'department_id' => $department?->id ?? $staff?->department_id,
            'financial_year_id' => $financialYear?->id,
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
            'check_number' => 'nullable|string|exists:staff,check_number',
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
