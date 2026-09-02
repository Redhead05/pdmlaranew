<?php

namespace App\Services;

class AutoMatchService
{
    /**
     * Hitung jarak Haversine (km) antara dua koordinat.
     */
    public static function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    /**
     * Jarak tiap anggota tim ke sebuah lembaga (urutan sesuai anggota).
     * Anggota/lembaga tanpa koordinat menghasilkan null.
     *
     * @param  iterable  $members  koleksi TeamMember (sudah load user.detail)
     * @param  \App\Models\Lembaga  $lembaga
     * @return array<int, float|null>
     */
    public static function memberDistances($members, $lembaga)
    {
        $hasLembaga = $lembaga && $lembaga->latitude && $lembaga->longitude;

        $distances = [];
        foreach ($members as $m) {
            $d = $m->user->detail ?? null;
            if (! $hasLembaga || ! $d || ! $d->latitude || ! $d->longitude) {
                $distances[] = null;
                continue;
            }
            $distances[] = round(self::haversine((float) $d->latitude, (float) $d->longitude, (float) $lembaga->latitude, (float) $lembaga->longitude), 2);
        }

        return $distances;
    }

    /**
     * Jarak tim ke lembaga = nilai maksimum jarak anggota (minimax).
     * Memastikan lembaga terdekat tidak terlalu jauh bagi anggota mana pun.
     */
    public static function teamDistanceToLembaga($members, $lembaga)
    {
        $distances = array_filter(self::memberDistances($members, $lembaga), fn ($d) => $d !== null);

        return $distances ? max($distances) : null;
    }

    /**
     * Pasangkan tim asesor ke lembaga terdekat secara otomatis (minimax).
     *
     * Algoritma: bangun semua pasangan (tim, lembaga) beserta jarak tim,
     * urutkan dari terdekat, lalu pasangkan secara greedy sambil menghormati:
     * - kuota tiap tim (jumlah lembaga maksimal sesuai kesanggupan), dan
     * - satu lembaga hanya untuk satu tim.
     *
     * @param  \Illuminate\Support\Collection  $teams    koleksi Team (sudah load members.user.detail)
     * @param  \Illuminate\Support\Collection  $lembagas koleksi Lembaga
     * @return array  daftar ['team' => Team, 'lembaga_id' => int, 'distance_km' => float]
     */
    public static function autoMatch($teams, $lembagas)
    {
        // 1. Siapkan deskripsi tiap tim: kuota (koordinat dibaca dari anggota).
        $teamDesc = [];
        foreach ($teams as $team) {
            $kuota = $team->kuota();
            if ($kuota < 1) {
                continue;
            }
            $teamDesc[$team->id] = [
                'team' => $team,
                'kuota' => $kuota,
                'used' => 0,
            ];
        }

        // 2. Bangun semua edge (tim -> lembaga) dan urutkan berdasarkan jarak minimax.
        $edges = [];
        foreach ($teamDesc as $t) {
            foreach ($lembagas as $l) {
                if (! $l->latitude || ! $l->longitude) {
                    continue;
                }
                $d = self::teamDistanceToLembaga($t['team']->members, $l);
                if ($d === null) {
                    continue;
                }
                $edges[] = [
                    'team_id' => $t['team']->id,
                    'lembaga_id' => $l->id,
                    'd' => $d,
                ];
            }
        }
        usort($edges, fn ($a, $b) => $a['d'] <=> $b['d']);

        // 3. Pasangkan dari jarak terdekat, hormati kuota & keunikan lembaga.
        $taken = [];
        $assignments = [];
        foreach ($edges as $e) {
            $td = &$teamDesc[$e['team_id']];
            if ($td['used'] >= $td['kuota']) {
                continue;
            }
            if (isset($taken[$e['lembaga_id']])) {
                continue;
            }

            $taken[$e['lembaga_id']] = true;
            $td['used']++;
            $assignments[] = [
                'team' => $td['team'],
                'lembaga_id' => $e['lembaga_id'],
                'distance_km' => round($e['d'], 3),
            ];
        }

        return $assignments;
    }
}
