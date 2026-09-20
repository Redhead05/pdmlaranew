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
 * Template "Pasangan Asesor": satu baris per tim (pasangan asesor).
 *
 * Kolom NIA dipaksa bertipe teks agar NIA berawalan nol tidak berubah saat
 * file diedit di Excel lalu di-upload kembali.
 */
class AsesorPairingExport extends DefaultValueBinder implements FromArray, WithColumnWidths, WithCustomValueBinder, WithHeadings, WithStyles, WithTitle
{
    /** Kolom yang selalu ditulis sebagai teks: NIA Asesor A / B / C (B, F, J). */
    private const TEXT_COLUMNS = ['B', 'F', 'J'];

    protected array $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function title(): string
    {
        return 'Pasangan Asesor';
    }

    public function headings(): array
    {
        return [
            'Kode Tim',
            'NIA Asesor A',
            'Nama Asesor A',
            'Kab/Kota A',
            'Kesanggupan A',
            'NIA Asesor B',
            'Nama Asesor B',
            'Kab/Kota B',
            'Kesanggupan B',
            'NIA Asesor C',
            'Nama Asesor C',
            'Kab/Kota C',
            'Kesanggupan C',
            'Jumlah Lembaga',
        ];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 18,
            'C' => 30,
            'D' => 20,
            'E' => 14,
            'F' => 18,
            'G' => 30,
            'H' => 20,
            'I' => 14,
            'J' => 18,
            'K' => 30,
            'L' => 20,
            'M' => 14,
            'N' => 16,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Paksa kolom NIA menjadi teks; kolom lain memakai perilaku default.
     */
    public function bindValue(Cell $cell, $value)
    {
        if (in_array($cell->getColumn(), self::TEXT_COLUMNS, true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
