<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Workbook "Unduh Hasil Pairing" — satu file berisi:
 *  1. Hasil Pairing      (per NPSN)
 *  2. Manual Override    (tim belum penuh, kolom NPSN/Nama diisi lalu di-upload ulang)
 *  3. Lembaga Tersisa    (info lembaga yang belum terpetakan)
 * Dibuat tanpa kolom koordinat (lat/lng).
 */
class PairingExport implements WithMultipleSheets
{
    protected array $results;

    protected array $manualOverride;

    protected array $remaining;

    public function __construct(array $results, array $manualOverride, array $remaining)
    {
        $this->results = $results;
        $this->manualOverride = $manualOverride;
        $this->remaining = $remaining;
    }

    public function sheets(): array
    {
        return [
            new PairingResultsSheet($this->results),
            new ManualOverrideExport($this->manualOverride),
            new PairingRemainingSheet($this->remaining),
        ];
    }
}
