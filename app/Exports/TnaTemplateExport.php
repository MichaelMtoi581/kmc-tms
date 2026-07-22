<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TnaTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'check_number',
            'full_name',
            'department',
            'designation',
            'training_needed',
            'training_reason',
            'expected_outcome',
            'priority',
            'preferred_duration',
            'preferred_institution',
            'remarks',
        ];
    }

    public function array(): array
    {
        return [
            [
                'KMC001',
                'John Mwakasege',
                'ICT',
                'System Administrator',
                'Data Analysis with Python',
                'To improve data reporting capabilities',
                'Ability to generate automated reports',
                'High',
                '1 Week',
                'Dar es Salaam Institute of Technology',
                '',
            ],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D6E4F0'],
                ],
            ],
        ];
    }
}
