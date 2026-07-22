<?php

namespace App\Imports;

use App\Models\TnaResponse;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class TnaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    public array $failures = [];
    public int $rowsImported = 0;
    public int $duplicatesSkipped = 0;

    private array $seen = [];
    private int $exerciseId;

    public function __construct(int $exerciseId)
    {
        $this->exerciseId = $exerciseId;
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function model(array $row)
    {
        $this->rowsImported++;

        $trainingNeeded = strip_tags($row['training_needed'] ?? $row['training_title'] ?? '');

        $checkNumber = $row['check_number'] ?? $row['check_no'] ?? '';
        $key = "{$checkNumber}|{$trainingNeeded}";

        if (isset($this->seen[$key]) || TnaResponse::where('tna_exercise_id', $this->exerciseId)
            ->where('check_number', $checkNumber)
            ->where('training_needed', $trainingNeeded)
            ->exists()
        ) {
            $this->rowsImported--;
            $this->duplicatesSkipped++;
            return null;
        }

        $this->seen[$key] = true;

        $fullName = strip_tags(
            $row['full_name'] ?? trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''))
        );

        return new TnaResponse([
            'tna_exercise_id' => $this->exerciseId,
            'check_number' => $checkNumber,
            'full_name' => $fullName,
            'department' => isset($row['department']) ? strip_tags($row['department']) : null,
            'designation' => isset($row['designation']) ? strip_tags($row['designation']) : null,
            'training_needed' => $trainingNeeded,
            'training_reason' => isset($row['training_reason']) ? strip_tags($row['training_reason']) : null,
            'expected_outcome' => isset($row['expected_outcome']) ? strip_tags($row['expected_outcome']) : null,
            'priority' => $row['priority'] ?? 'Medium',
            'preferred_duration' => isset($row['preferred_duration']) ? strip_tags($row['preferred_duration']) : null,
            'preferred_institution' => isset($row['preferred_institution']) ? strip_tags($row['preferred_institution']) : null,
            'remarks' => isset($row['remarks']) ? strip_tags($row['remarks']) : null,
            'imported_at' => now(),
        ]);
    }

    public function rules(): array
    {
        return [
            'check_number' => 'required|string',
            'training_needed' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'priority' => 'nullable|in:High,Medium,Low',
        ];
    }
}
