<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Template Manual Override: tim yang belum penuh dengan kolom NPSN / nama
 * lembaga kosong — diisi admin lalu di-upload ulang (GenerationController::upload).
 */
class ManualOverrideExport implements FromArray, WithHeadings, WithTitle
{
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function title(): string
    {
        return 'Manual Override';
    }

    public function headings(): array
    {
        return ['Kode Tim', 'NIA A', 'Nama A', 'NIA B', 'Nama B', 'Sisa Kuota', 'NPSN', 'Nama Lembaga'];
    }

    public function array(): array
    {
        return $this->rows;
    }
}
