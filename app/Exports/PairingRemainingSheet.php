<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sheet "Lembaga Tersisa": lembaga tahap yang belum mendapat tim.
 */
class PairingRemainingSheet implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function title(): string
    {
        return 'Lembaga Tersisa';
    }

    public function headings(): array
    {
        return ['NPSN', 'Nama Lembaga', 'Kab/Kota', 'Kecamatan', 'Jenjang', 'Status'];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
