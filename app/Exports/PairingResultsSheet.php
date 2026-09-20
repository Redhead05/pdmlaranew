<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Sheet "Hasil Pairing": satu baris per pasangan (per NPSN lembaga).
 * Tanpa kolom koordinat (lat/lng) agar mudah dibaca pengguna.
 */
class PairingResultsSheet implements FromArray, WithHeadings, WithStyles, WithTitle
{
    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function title(): string
    {
        return 'Hasil Pairing';
    }

    public function headings(): array
    {
        return [
            'Kode Tim',
            'NPSN',
            'Nama Lembaga',
            'Kab/Kota Lembaga',
            'NIA Asesor A',
            'Nama Asesor A',
            'Work City A',
            'NIA Asesor B',
            'Nama Asesor B',
            'Work City B',
        ];
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
