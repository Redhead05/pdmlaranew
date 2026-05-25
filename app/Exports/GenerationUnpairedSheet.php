<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenerationUnpairedSheet implements FromArray, WithTitle, WithHeadings, WithStyles
{
    protected array $unpairedTeams;
    protected array $unpairedLembagas;

    public function __construct(array $unpairedTeams, array $unpairedLembagas)
    {
        $this->unpairedTeams = $unpairedTeams;
        $this->unpairedLembagas = $unpairedLembagas;
    }

    public function title(): string
    {
        return 'Tidak Berpasangan';
    }

    public function headings(): array
    {
        return [
            'Tipe',
            'ID',
            'Kode / NPSN',
            'Nama',
            'Latitude',
            'Longitude',
        ];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->unpairedTeams as $t) {
            $rows[] = ['Tim', $t['team_id'], $t['team_code'], $t['members'], $t['lat'], $t['lng']];
        }
        foreach ($this->unpairedLembagas as $l) {
            $rows[] = ['Lembaga', $l['id'], $l['npsn'], $l['name'], $l['lat'], $l['lng']];
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
