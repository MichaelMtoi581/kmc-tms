<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrainingTemplateExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function headings(): array
    {
        return [
            'course_title',
            'check_number',
            'department',
            'financial_year',
            'category',
            'institution',
            'funding_source',
            'start_date',
            'end_date',
            'venue',
            'cost',
            'description',
            'remarks',
        ];
    }

    public function array(): array
    {
        return [
            [
                'Data Analysis with Python',
                'KMC001',
                'ICT',
                '2025/2026',
                'Short Course',
                'Dar es Salaam Institute of Technology',
                'Council Budget',
                '01/07/2025',
                '15/07/2025',
                'Council Hall',
                '500000',
                'Capacity building for ICT staff',
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
