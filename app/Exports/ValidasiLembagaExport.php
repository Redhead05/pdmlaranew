<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Template hasil pairing lembaga ↔ asesor validasi.
 * Kolom NPSN (A) & NIA Asesor (D) dipaksa bertipe teks.
 */
class ValidasiLembagaExport extends DefaultValueBinder implements FromArray, WithColumnWidths, WithCustomValueBinder, WithHeadings, WithStyles, WithTitle
{
    private const TEXT_COLUMNS = ['A', 'D'];

    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function title(): string
    {
        return 'Pairing Lembaga';
    }

    public function headings(): array
    {
        return ['NPSN', 'Nama Lembaga', 'Kabupaten', 'NIA Asesor', 'Nama Asesor'];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function columnWidths(): array
    {
        return ['A' => 16, 'B' => 42, 'C' => 22, 'D' => 20, 'E' => 32];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (in_array($cell->getColumn(), self::TEXT_COLUMNS, true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
