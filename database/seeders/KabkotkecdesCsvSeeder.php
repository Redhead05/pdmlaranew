<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kabkot;
use App\Models\Kecamatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabkotkecdesCsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = base_path('database/seeders/test.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV not found: {$path}");
            return;
        }

        if (($handle = fopen($path, 'rb')) === false) {
            $this->command->error("Unable to open CSV: {$path}");
            return;
        }

        // Detect delimiter by peeking first line
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            $this->command->error("CSV is empty: {$path}");
            return;
        }
        rewind($handle);

        $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';

        $header = fgetcsv($handle, 0, $delimiter);
        if ($header === false) {
            fclose($handle);
            $this->command->error("CSV header not found or unreadable: {$path}");
            return;
        }

        // Normalize header: remove BOM, trim and lowercase, make unique keys
        $header = array_map(function ($h) {
            $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
            return trim(strtolower($h));
        }, $header);

        // ensure header keys are unique (append index when duplicate)
        $seen = [];
        foreach ($header as $i => $h) {
            if ($h === '') {
                $h = 'column_' . $i;
            }
            $orig = $h;
            $idx = 1;
            while (in_array($h, $seen, true)) {
                $h = $orig . '_' . $idx;
                $idx++;
            }
            $seen[] = $h;
            $header[$i] = $h;
        }

        $inserted = 0;
        $updated = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                // trim each field
                $row = array_map(fn($v) => is_null($v) ? $v : trim($v), $row);

                // normalize row length
                if (count($row) > count($header)) {
                    $row = array_slice($row, 0, count($header));
                } elseif (count($row) < count($header)) {
                    $row = array_pad($row, count($header), '');
                }

                if (count($row) !== count($header)) {
                    // still mismatched, skip
                    continue;
                }

                $data = array_combine($header, $row);

                // helper to normalize coordinate string (comma -> dot) and return null when empty
                $normalizeCoord = function ($val) {
                    if ($val === null || $val === '') return null;
                    // remove spaces and NBSP
                    $v = str_replace([' ', "\u{00A0}", "\t"], ['', '', ''], $val);
                    // replace comma with dot
                    $v = str_replace(',', '.', $v);
                    // remove any non-numeric characters except dot and minus
                    $v = preg_replace('/[^0-9.\-]/', '', $v);
                    return $v === '' ? null : $v;
                };

                // Kabkot
                $kabId = null;
                if (isset($data['id_kab']) && $data['id_kab'] !== '') {
                    $kabId = (int)$data['id_kab'];
                } elseif (isset($data['id']) && $data['id'] !== '') {
                    $kabId = (int)$data['id'];
                }

                if ($kabId === null) {
                    // skip rows without kab id
                    continue;
                }

                $latKab = $normalizeCoord($data['latitude_kab'] ?? ($data['latitude'] ?? ''));
                $lngKab = $normalizeCoord($data['longitude_kab'] ?? ($data['longitude'] ?? ''));
                $namaKab = $data['nama_kabkot'] ?? ($data['nama_kab'] ?? null);

                $kab = Kabkot::firstOrNew(['id' => $kabId]);
                $isNewKab = !$kab->exists;
                $kab->id = $kabId; // explicitly set id for new records
                if ($namaKab !== null) $kab->nama_kabkot = $namaKab;
                $kab->latitude = $latKab;
                $kab->longitude = $lngKab;
                $kab->save();

                if ($isNewKab) $inserted++; else $updated++;

                // Kecamatan
                $kecId = null;
                if (isset($data['id_kec']) && $data['id_kec'] !== '') {
                    $kecId = (int)$data['id_kec'];
                } elseif (isset($data['idkec']) && $data['idkec'] !== '') {
                    $kecId = (int)$data['idkec'];
                }

                if ($kecId !== null) {
                    $latKec = $normalizeCoord($data['latitude_kec'] ?? ($data['latitude_kecamatan'] ?? ''));
                    $lngKec = $normalizeCoord($data['longitude_kec'] ?? ($data['longitude_kecamatan'] ?? ''));
                    $namaKec = $data['nama_kec'] ?? ($data['nama_kecamatan'] ?? null);

                    $kec = Kecamatan::firstOrNew(['id' => $kecId]);
                    $isNewKec = !$kec->exists;
                    $kec->id = $kecId;
                    $kec->kabkot_id = $kab->id;
                    if ($namaKec !== null) $kec->nama_kec = $namaKec;
                    $kec->latitude = $latKec;
                    $kec->longitude = $lngKec;
                    $kec->save();

                    if ($isNewKec) $inserted++; else $updated++;

                    // Desa
                    $desId = null;
                    if (isset($data['id_des']) && $data['id_des'] !== '') {
                        $desId = (int)$data['id_des'];
                    } elseif (isset($data['iddes']) && $data['iddes'] !== '') {
                        $desId = (int)$data['iddes'];
                    }

                    if ($desId !== null) {
                        // note: there may be duplicate header keys like 'latitude_des' repeated; use first occurrence keys
                        $latDes = $normalizeCoord($data['latitude_des'] ?? ($data['latitude_desa'] ?? ''));
                        $lngDes = $normalizeCoord($data['longitude_des'] ?? ($data['longitude_desa'] ?? ''));
                        $namaDes = $data['nama_desa'] ?? ($data['nama_desa'] ?? null);

                        $des = Desa::firstOrNew(['id' => $desId]);
                        $isNewDes = !$des->exists;
                        $des->id = $desId;
                        $des->kecamatan_id = $kec->id;
                        if ($namaDes !== null) $des->nama_desa = $namaDes;
                        $des->latitude = $latDes;
                        $des->longitude = $lngDes;
                        $des->save();

                        if ($isNewDes) $inserted++; else $updated++;
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            $this->command->error('Seeding failed: ' . $e->getMessage());
            return;
        }

        fclose($handle);

        $this->command->info("CSV seeding completed. Inserted: {$inserted}, Updated: {$updated}");
    }
}
