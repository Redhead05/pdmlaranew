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
            $tahaps[] = Tahap::query()->create([
                'tahap' => '1',
                'surat_keputusan' => 'SK/TAHAP/001/2026',
                'allowed_kesanggupan' => [2, 3, 4, 5, 6],
                'start_date' => now()->subDays(1)->setTime(9, 0, 0),
                'end_date' => now()->addDays(5)->setTime(17, 0, 0),
                'slug' => $makeSlug(),
            ]);

            $tahaps[] = Tahap::query()->create([
                'tahap' => '2',
                'surat_keputusan' => 'SK/TAHAP/002/2026',
                'allowed_kesanggupan' => [2, 4, 6, 8],
                'start_date' => now()->addDays(1)->setTime(8, 30, 0),
                'end_date' => now()->addDays(6)->setTime(16, 0, 0),
                'slug' => $makeSlug(),
            ]);

            $tahaps[] = Tahap::query()->create([
                'tahap' => '3',
                'surat_keputusan' => 'SK/TAHAP/003/2026',
                'allowed_kesanggupan' => [3, 5, 7, 10],
                'start_date' => now()->addDays(3)->setTime(10, 0, 0),
                'end_date' => now()->addDays(10)->setTime(18, 0, 0),
                'slug' => $makeSlug(),
            ]);

            $tahaps[] = Tahap::query()->create([
                'tahap' => '4',
                'surat_keputusan' => 'SK/TAHAP/004/2026',
                'allowed_kesanggupan' => [1, 2, 3],
                'start_date' => now()->addDays(7)->setTime(9, 0, 0),
                'end_date' => now()->addDays(14)->setTime(17, 0, 0),
                'slug' => $makeSlug(),
            ]);

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
