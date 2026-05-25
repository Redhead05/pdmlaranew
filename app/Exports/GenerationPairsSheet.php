<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenerationPairsSheet implements FromArray, WithTitle, WithHeadings, WithStyles
{
    protected array $pairs;

    public function __construct(array $pairs)
    {
        $this->pairs = $pairs;
    }

    public function title(): string
    {
        return 'Pasangan';
    }

    public function headings(): array
    {
        return [
            'Kode Tim',
            'NIA',
            'Nama',
            'WorkCity',
            'HomeCity',
            'NPSN',
            'Nama Lembaga',
            'Lat Tim',
            'Lng Tim',
            'Lat Lembaga',
            'Lng Lembaga',
            'Jarak (KM)',
            'Catatan',
        ];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->pairs as $p) {
            // for each member of the team produce one row
            $members = $p['members'] ?? [];
            if (empty($members)) {
                // fallback: emit a single row with empty member fields
                $rows[] = [
                    $p['team_code'] ?? '',
                    '',
                    '',
                    '',
                    '',
                    $p['npsn'] ?? '',
                    $p['lembaga_name'] ?? '',
                    $p['team_lat'] ?? '',
                    $p['team_lng'] ?? '',
                    $p['lembaga_lat'] ?? '',
                    $p['lembaga_lng'] ?? '',
                    $p['distance_km'] ?? '',
                    '',
                ];
            } else {
                foreach ($members as $m) {
                    $rows[] = [
                        $p['team_code'] ?? '',
                        $m['nia'] ?? '',
                        $m['name'] ?? '',
                        $m['work_city'] ?? '',
                        $m['home_city'] ?? '',
                        $p['npsn'] ?? '',
                        $p['lembaga_name'] ?? '',
                        $p['team_lat'] ?? '',
                        $p['team_lng'] ?? '',
                        $p['lembaga_lat'] ?? '',
                        $p['lembaga_lng'] ?? '',
                        $p['distance_km'] ?? '',
                        '',
                    ];
                }
            }
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
