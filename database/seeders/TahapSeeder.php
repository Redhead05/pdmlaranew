<?php

namespace Database\Seeders;

use App\Models\Kesanggupan;
use App\Models\Tahap;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahapSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $asesors = User::query()->role('asesor')->get(['id']);

            if ($asesors->isEmpty()) {
                throw new \RuntimeException('No users with role `asesor` found. Seed/create asesor users first.');
            }

            // Supaya seeder aman dijalankan berulang-ulang tanpa nambah duplikat.
            // Tahap punya FK ke kesanggupans (cascade), jadi hapus tahap lebih aman.
            Tahap::query()->delete();

            $makeSlug = function (): string {
                do {
                    $slug = (string) random_int(1000000, 9999999);
                } while (Tahap::query()->where('slug', $slug)->exists());

                return $slug;
            };

            $tahaps = [];

            // Create 5 tahap records where end_date is already in the past
            // so they appear as finished/expired. We space them 1 day apart
            // and set the end time to earlier today or previous days.
            for ($i = 1; $i <= 5; $i++) {
                $daysAgo = $i * 2; // each tahap ended further in the past
                $start = now()->subDays($daysAgo + 10)->setTime(9, 0, 0);
                $end = now()->subDays($daysAgo)->setTime(17, 0, 0);

                $allowedSets = [
                    [1,2,3],
                    [2,3,4],
                    [2,4,6],
                    [3,5,7],
                    [2,3,5,7]
                ];

                $tahaps[] = Tahap::query()->create([
                    'tahap' => (string) $i,
                    'surat_keputusan' => "SK/TAHAP/00{$i}/2026",
                    'allowed_kesanggupan' => $allowedSets[$i-1] ?? [1],
                    'start_date' => $start,
                    'end_date' => $end,
                    'slug' => $makeSlug(),
                ]);
            }

            $now = now();
            $rows = [];

            $reasons = [
                'Bentrok jadwal visitasi.',
                'Sedang tugas luar kota.',
                'Kondisi kesehatan.',
                'Sudah ada penugasan lain.',
                'Keperluan keluarga.',
            ];

            // Pastikan ada minimal 1 asesor: belum mengisi sama sekali untuk *semua tahap*.
            $notFilledUserId = (int) $asesors->random()->id;

            foreach ($tahaps as $tahap) {
                $allowed = array_values(array_filter(array_map('intval', (array) ($tahap->allowed_kesanggupan ?? []))));
                if (empty($allowed)) {
                    $allowed = [1];
                }

                foreach ($asesors as $asesor) {
                    $userId = (int) $asesor->id;

                    // 1 asesor sengaja tidak dibuatkan isian apapun (belum mengisi)
                    if ($userId === $notFilledUserId) {
                        continue;
                    }

                    // 0 = belum mengisi (row ada tapi kesediaan null)
                    // 1 = bersedia (kesediaan true + kesanggupan wajib)
                    // 2 = tidak bersedia (kesediaan false + alasan wajib)
                    $state = random_int(0, 2);

                    $kesediaan = null;
                    $kesanggupan = null;
                    $alasan = null;

                    if ($state === 1) {
                        $kesediaan = true;
                        $kesanggupan = $allowed[array_rand($allowed)];
                    } elseif ($state === 2) {
                        $kesediaan = false;
                        $alasan = $reasons[array_rand($reasons)];
                    }

                    $rows[] = [
                        'tahap_id' => $tahap->id,
                        'user_id' => $userId,
                        'kesediaan' => $kesediaan,
                        'kesanggupan' => $kesanggupan,
                        'alasan' => $alasan,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (! empty($rows)) {
                Kesanggupan::query()->insert($rows);
            }
        });
    }
}
