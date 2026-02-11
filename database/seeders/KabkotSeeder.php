<?php

namespace Database\Seeders;

use App\Models\Kabkot;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KabkotSeeder extends Seeder
{
    protected array $rows = [
        ['idkabkot' => 1,  'nama_kabkot' => 'KAB. BANGKALAN',       'long_kabkot' => 112.92966791300, 'lat_kabkot' => -7.04434547998],
        ['idkabkot' => 2,  'nama_kabkot' => 'KAB. BANYUWANGI',      'long_kabkot' => 114.20559711400, 'lat_kabkot' => -8.36457542670],
        ['idkabkot' => 3,  'nama_kabkot' => 'KAB. BLITAR',          'long_kabkot' => 112.23776256400, 'lat_kabkot' => -8.12970950404],
        ['idkabkot' => 4,  'nama_kabkot' => 'KAB. BOJONEGORO',      'long_kabkot' => 111.80993169200, 'lat_kabkot' => -7.25553647965],
        ['idkabkot' => 5,  'nama_kabkot' => 'KAB. BONDOWOSO',       'long_kabkot' => 113.95425864200, 'lat_kabkot' => -7.95855854717],
        ['idkabkot' => 6,  'nama_kabkot' => 'KAB. GRESIK',          'long_kabkot' => 112.55829960300, 'lat_kabkot' => -6.92568521711],
        ['idkabkot' => 7,  'nama_kabkot' => 'KAB. JEMBER',          'long_kabkot' => 113.64419718000, 'lat_kabkot' => -8.24139483782],
        ['idkabkot' => 8,  'nama_kabkot' => 'KAB. JOMBANG',         'long_kabkot' => 112.26506029500, 'lat_kabkot' => -7.54504342188],
        ['idkabkot' => 9,  'nama_kabkot' => 'KAB. KEDIRI',          'long_kabkot' => 112.08967530600, 'lat_kabkot' => -7.82870640353],
        ['idkabkot' => 10, 'nama_kabkot' => 'KAB. LAMONGAN',        'long_kabkot' => 112.30079818000, 'lat_kabkot' => -7.13128975659],
        ['idkabkot' => 11, 'nama_kabkot' => 'KAB. LUMAJANG',        'long_kabkot' => 113.13823465400, 'lat_kabkot' => -8.12508104189],
        ['idkabkot' => 12, 'nama_kabkot' => 'KAB. MADIUN',          'long_kabkot' => 111.64555815300, 'lat_kabkot' => -7.62439738515],
        ['idkabkot' => 13, 'nama_kabkot' => 'KAB. MAGETAN',         'long_kabkot' => 111.35782948600, 'lat_kabkot' => -7.66313239223],
        ['idkabkot' => 14, 'nama_kabkot' => 'KAB. MALANG',          'long_kabkot' => 112.64123088500, 'lat_kabkot' => -8.12684776044],
        ['idkabkot' => 15, 'nama_kabkot' => 'KAB. MOJOKERTO',       'long_kabkot' => 112.48555567200, 'lat_kabkot' => -7.54967695031],
        ['idkabkot' => 16, 'nama_kabkot' => 'KAB. NGANJUK',         'long_kabkot' => 111.93844484700, 'lat_kabkot' => -7.59750063860],
        ['idkabkot' => 17, 'nama_kabkot' => 'KAB. NGAWI',           'long_kabkot' => 111.34311938300, 'lat_kabkot' => -7.43916050790],
        ['idkabkot' => 18, 'nama_kabkot' => 'KAB. PACITAN',         'long_kabkot' => 111.17913652800, 'lat_kabkot' => -8.12601218029],
        ['idkabkot' => 19, 'nama_kabkot' => 'KAB. PAMEKASAN',       'long_kabkot' => 113.50392546900, 'lat_kabkot' => -7.06525831620],
        ['idkabkot' => 20, 'nama_kabkot' => 'KAB. PASURUAN',        'long_kabkot' => 112.83198660600, 'lat_kabkot' => -7.74295165557],
        ['idkabkot' => 21, 'nama_kabkot' => 'KAB. PONOROGO',        'long_kabkot' => 111.49967956600, 'lat_kabkot' => -7.93166396945],
        ['idkabkot' => 22, 'nama_kabkot' => 'KAB. PROBOLINGGO',     'long_kabkot' => 113.32089179600, 'lat_kabkot' => -7.86654642208],
        ['idkabkot' => 23, 'nama_kabkot' => 'KAB. SAMPANG',         'long_kabkot' => 113.25626595400, 'lat_kabkot' => -7.05186768690],
        ['idkabkot' => 24, 'nama_kabkot' => 'KAB. SIDOARJO',        'long_kabkot' => 112.70097171900, 'lat_kabkot' => -7.45195037539],
        ['idkabkot' => 25, 'nama_kabkot' => 'KAB. SITUBONDO',       'long_kabkot' => 114.04226831600, 'lat_kabkot' => -7.80230077238],
        ['idkabkot' => 26, 'nama_kabkot' => 'KAB. SUMENEP',         'long_kabkot' => 114.40198881500, 'lat_kabkot' => -6.96649308865],
        ['idkabkot' => 27, 'nama_kabkot' => 'KAB. TRENGGALEK',      'long_kabkot' => 111.62641609900, 'lat_kabkot' => -8.16152035740],
        ['idkabkot' => 28, 'nama_kabkot' => 'KAB. TUBAN',           'long_kabkot' => 111.89151292400, 'lat_kabkot' => -6.95326070021],
        ['idkabkot' => 29, 'nama_kabkot' => 'KAB. TULUNGAGUNG',     'long_kabkot' => 111.88714827300, 'lat_kabkot' => -8.11309537788],
        ['idkabkot' => 30, 'nama_kabkot' => 'KOTA BATU',            'long_kabkot' => 112.53239602500, 'lat_kabkot' => -7.83090518679],
        ['idkabkot' => 31, 'nama_kabkot' => 'KOTA BLITAR',          'long_kabkot' => 112.16643988500, 'lat_kabkot' => -8.09523385130],
        ['idkabkot' => 32, 'nama_kabkot' => 'KOTA KEDIRI',          'long_kabkot' => 112.01539123200, 'lat_kabkot' => -7.82605252788],
        ['idkabkot' => 33, 'nama_kabkot' => 'KOTA MADIUN',          'long_kabkot' => 111.52989129200, 'lat_kabkot' => -7.62696536845],
        ['idkabkot' => 34, 'nama_kabkot' => 'KOTA MALANG',          'long_kabkot' => 112.63644601800, 'lat_kabkot' => -7.97887322572],
        ['idkabkot' => 35, 'nama_kabkot' => 'KOTA MOJOKERTO',       'long_kabkot' => 112.43747309100, 'lat_kabkot' => -7.47143499415],
        ['idkabkot' => 36, 'nama_kabkot' => 'KOTA PASURUAN',        'long_kabkot' => 112.91051494800, 'lat_kabkot' => -7.64942184819],
        ['idkabkot' => 37, 'nama_kabkot' => 'KOTA PROBOLINGGO',     'long_kabkot' => 113.20585323900, 'lat_kabkot' => -7.77447231920],
        ['idkabkot' => 38, 'nama_kabkot' => 'KOTA SURABAYA',        'long_kabkot' => 112.72332276500, 'lat_kabkot' => -7.27365967049],
    ];

    public function run(): void
    {
        if (empty($this->rows)) {
            $this->command->info('No kabkots to seed.');
            return;
        }

        $prepared = array_map(function ($r) {
            return [
                // ensure numeric types
                'idkabkot'    => (int) ($r['idkabkot'] ?? 0),
                'nama_kabkot' => $r['nama_kabkot'] ?? null,
                'long_kabkot' => isset($r['long_kabkot']) && is_numeric($r['long_kabkot']) ? (float) $r['long_kabkot'] : null,
                'lat_kabkot'  => isset($r['lat_kabkot']) && is_numeric($r['lat_kabkot']) ? (float) $r['lat_kabkot'] : null,
                'created_at'  => $r['created_at'] ?? now(),
                'updated_at'  => $r['updated_at'] ?? now(),
            ];
        }, $this->rows);

        DB::transaction(function () use ($prepared) {
            DB::table('kabkots')->upsert(
                $prepared,
                ['idkabkot'],
                ['nama_kabkot', 'long_kabkot', 'lat_kabkot', 'updated_at']
            );
        });

        $this->command->info('Seeded ' . count($prepared) . ' kabkots.');
    }
}
