<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use App\Models\LembagaDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatlemCsvSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/datlem.csv');
        if (!file_exists($path)) {
            $this->command->error("File not found: $path");
            return;
        }

        $inserted = $updated = 0;

        $fp = fopen($path, 'rb');
        if ($fp === false) {
            $this->command->error("Cannot open file: $path");
            return;
        }

        // Detect delimiter from first line
        $firstLine = fgets($fp);
        rewind($fp);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

        // Read and normalize header
        $rawHeader = fgetcsv($fp, 0, $delimiter);
        if ($rawHeader === false) {
            fclose($fp);
            $this->command->error("Empty or invalid CSV header.");
            return;
        }

        // remove BOM from first header cell if present
        $rawHeader[0] = preg_replace('/^\xEF\xBB\xBF/', '', $rawHeader[0]);

        $headers = [];
        foreach ($rawHeader as $h) {
            $key = strtolower(trim($h));
            // make unique if duplicate
            $base = $key;
            $i = 1;
            while (in_array($key, $headers, true)) {
                $key = $base . '_' . $i++;
            }
            $headers[] = $key;
        }

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($fp, 0, $delimiter)) !== false) {
                // pad or truncate to header length
                if (count($row) < count($headers)) {
                    $row = array_pad($row, count($headers), '');
                } elseif (count($row) > count($headers)) {
                    $row = array_slice($row, 0, count($headers));
                }

                $data = array_combine($headers, array_map('trim', $row));
                if ($data === false) {
                    continue;
                }

                // require npsn to identify lembaga
                $npsn = $data['npsn'] ?? null;
                if (empty($npsn)) {
                    continue;
                }

                // normalize coordinates if present
                $lat = $this->normalizeCoord($data['latitude'] ?? ($data['lat'] ?? null));
                $lng = $this->normalizeCoord($data['longitude'] ?? ($data['lon'] ?? $data['long'] ?? null));

                // map lembaga fields (only those fillable)
                $lembagaAttributes = array_filter([
                    'npsn' => $npsn,
                    'satuan_pen' => $data['satuan_pen'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                    'kelurahan' => $data['kelurahan'] ?? null,
                    'kecamatan' => $data['kecamatan'] ?? null,
                    'kabupaten' => $data['kabupaten'] ?? null,
                    'status' => $data['status'] ?? null,
                    'jenjang' => $data['jenjang'] ?? null,
                    'bentuk_pendidikan' => $data['bentuk_pendidikan'] ?? null,
                    'latitude' => $lat,
                    'longitude' => $lng,
                ], function ($v) { return $v !== null && $v !== ''; });

                // insert or update lembaga by npsn
                $existing = Lembaga::where('npsn', $npsn)->first();
                if ($existing) {
                    $existing->update($lembagaAttributes);
                    $lembaga = $existing;
                    $updated++;
                } else {
                    $lembaga = Lembaga::create($lembagaAttributes);
                    $inserted++;
                }

                // build detail attributes
                $detailAttributes = [];
                if (array_key_exists('cek_akreditasi', $data)) {
                    $detailAttributes['cek_akreditasi'] = $data['cek_akreditasi'] ?: null;
                }
                if (!empty($data['habis_masa_berlaku'])) {
                    $detailAttributes['habis_masa_berlaku'] = $this->parseDate($data['habis_masa_berlaku']);
                }
                if (array_key_exists('cek_sasaran_2025', $data)) {
                    $detailAttributes['cek_sasaran_2025'] = $data['cek_sasaran_2025'] ?: null;
                }
                if (array_key_exists('has_paket_a', $data)) {
                    $detailAttributes['has_paket_a'] = $this->toBool($data['has_paket_a']);
                }
                if (array_key_exists('has_paket_b', $data)) {
                    $detailAttributes['has_paket_b'] = $this->toBool($data['has_paket_b']);
                }
                if (array_key_exists('has_paket_c', $data)) {
                    $detailAttributes['has_paket_c'] = $this->toBool($data['has_paket_c']);
                }

                if (!empty($detailAttributes)) {
                    $detailKey = ['id_lembaga' => $lembaga->id];
                    $existsDetail = LembagaDetail::where('id_lembaga', $lembaga->id)->first();
                    if ($existsDetail) {
                        $existsDetail->update($detailAttributes);
                    } else {
                        $detailAttributes['id_lembaga'] = $lembaga->id;
                        LembagaDetail::create($detailAttributes);
                    }
                }
            }

            DB::commit();
            fclose($fp);
            $this->command->info("Datlem CSV seeding completed. Inserted: $inserted, Updated: $updated");
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($fp);
            $this->command->error("Seeding failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function normalizeCoord($v)
    {
        if ($v === null || $v === '') return null;
        $v = trim($v);
        $v = str_replace("\xc2\xa0", '', $v); // remove NBSP
        $v = str_replace(',', '.', $v);
        // keep digits, dot, minus
        $v = preg_replace('/[^\d\.\-]/', '', $v);
        return $v === '' ? null : $v;
    }

    private function toBool($v): bool
    {
        if (is_bool($v)) return $v;
        $v = strtolower(trim((string)$v));
        return in_array($v, ['1','true','yes','y','on'], true);
    }

    private function parseDate($v)
    {
        $v = trim($v);
        if ($v === '') return null;
        // try Y-m-d, d/m/Y, d-m-Y, etc.
        $v = str_replace('/', '-', $v);
        $ts = strtotime($v);
        return $ts ? date('Y-m-d', $ts) : null;
    }
}
