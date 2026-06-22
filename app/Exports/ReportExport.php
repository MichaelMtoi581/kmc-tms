<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;

class ReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected Collection $data;
    protected array $headings;

    public function __construct(Collection $data, array $headings)
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E9ECEF'],
                ],
            ],
        ];
    }

    public static function fromArray(array $data, array $headings): self
    {
        return new self(collect($data), $headings);
    }
}
