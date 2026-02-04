<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AttendancesExport implements FromCollection, WithHeadings, WithColumnFormatting
{

    public function __construct(private Collection $rows) {}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            '#',
            'Code',
            'Names',
            'Email',
            'Telephone',
            'Arrived At',
            'Left At',
        ];
    }

    public function map($row): array
    {
        return [
            $row['index'],
            $row['code'],
            $row['names'],
            $row['email'],
            $row['telephone'],
            $row['arrived_at'] ?? '',
            $row['left_at'] ?? '',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
