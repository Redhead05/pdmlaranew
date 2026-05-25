<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class GenerationExport implements WithMultipleSheets
{
    protected array $pairs;
    protected array $unpairedTeams;
    protected array $unpairedLembagas;

    public function __construct(array $pairs, array $unpairedTeams, array $unpairedLembagas)
    {
        $this->pairs = $pairs;
        $this->unpairedTeams = $unpairedTeams;
        $this->unpairedLembagas = $unpairedLembagas;
    }

    public function sheets(): array
    {
        return [
            new GenerationPairsSheet($this->pairs),
            new GenerationUnpairedSheet($this->unpairedTeams, $this->unpairedLembagas),
        ];
    }
}
