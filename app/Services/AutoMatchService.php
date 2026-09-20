<?php

namespace App\Services;

use App\Models\Kesanggupan;
use App\Models\Lembaga;
use Illuminate\Support\Collection;

class AutoMatchService
{
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
     * @param  Lembaga  $lembaga
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
     */
    public static function teamDistanceToLembaga($members, $lembaga)
    {
        $distances = array_filter(self::memberDistances($members, $lembaga), fn ($d) => $d !== null);

        return $distances ? max($distances) : null;
    }

    /**
     * Kuota tiap tim = nilai kesanggupan terkecil anggota yang menyatakan
     * kesediaan (kesediaan = true) pada tahap tsb. Tanpa anggota eligible -> 0.
     * Dihitung dengan SATU query untuk semua tim (menghilangkan N+1).
     *
     * @param  iterable  $teams  koleksi Team yang relasi members-nya sudah di-load
     * @return array<int, int> map team_id => kuota
     */
    public static function kuotaMap(int $tahapId, iterable $teams): array
    {
        $map = [];
        $userTeams = [];
        foreach ($teams as $team) {
            $map[$team->id] = null;
            foreach ($team->members as $m) {
                if ($m->user) {
                    $userTeams[$m->user_id][] = $team->id;
                }
            }
        }

        if ($userTeams) {
            $rows = Kesanggupan::where('tahap_id', $tahapId)
                ->where('kesediaan', true)
                ->whereNotNull('kesanggupan')
                ->whereIn('user_id', array_keys($userTeams))
                ->pluck('kesanggupan', 'user_id');

            foreach ($rows as $userId => $kuota) {
                foreach ($userTeams[$userId] ?? [] as $teamId) {
                    $v = (int) $kuota;
                    $map[$teamId] = $map[$teamId] === null ? $v : min($map[$teamId], $v);
                }
            }
        }

        foreach ($map as $id => $v) {
            $map[$id] = $v ?? 0;
        }

        return $map;
    }

    /**
     * Pasangkan tim asesor ke lembaga terdekat secara otomatis (minimax),
     * versi MEMORI-TERBATAS: greedy per-lembaga -> tim terdekat yang masih
     * punya kapasitas. Tidak membangun array semua pasangan (tim x lembaga)
     * lalu usort seperti versi lama, sehingga aman untuk ribuan lembaga x
     * ratusan tim.
     *
     * @param  Collection  $teams  koleksi Team (sudah load members.user.detail)
     * @param  Collection  $lembagas  koleksi Lembaga
     * @return array<int, array{team_id:int, lembaga_id:int, distance_km:float}>
     */
    public static function autoMatch(Collection $teams, Collection $lembagas): array
    {
        $tahapId = $teams->first()?->tahap_id ?? 0;
        $kuotaMap = self::kuotaMap($tahapId, $teams);

        // Deskripsi tim: koordinat anggota (dari user.detail) + sisa kapasitas.
        $teamDesc = [];
        foreach ($teams as $team) {
            $kuota = $kuotaMap[$team->id] ?? 0;
            if ($kuota < 1) {
                continue;
            }
            $coords = [];
            foreach ($team->members as $m) {
                $d = $m->user->detail ?? null;
                if ($d && $d->latitude !== null && $d->longitude !== null) {
                    $coords[] = [(float) $d->latitude, (float) $d->longitude];
                }
            }
            if (! $coords) {
                continue;
            }
            $teamDesc[$team->id] = ['coords' => $coords, 'remaining' => $kuota];
        }

        if (! $teamDesc) {
            return [];
        }
        $teamIds = array_keys($teamDesc);
        sort($teamIds);

        $assignments = [];
        foreach ($lembagas as $l) {
            if ($l->latitude === null || $l->longitude === null) {
                continue; // tanpa koordinat tidak bisa dihitung jaraknya
            }
            $llat = (float) $l->latitude;
            $llng = (float) $l->longitude;

            $bestId = null;
            $bestD = INF;
            foreach ($teamIds as $tid) {
                $desc = $teamDesc[$tid];
                if ($desc['remaining'] <= 0) {
                    continue;
                }
                // Jarak tim = max jarak anggota; henti lebih awal bila sudah tidak bisa menang.
                $mx = 0.0;
                foreach ($desc['coords'] as $c) {
                    $d = self::haversine($c[0], $c[1], $llat, $llng);
                    if ($d > $mx) {
                        $mx = $d;
                    }
                    if ($mx >= $bestD) {
                        break;
                    }
                }
                if ($mx < $bestD) {
                    $bestD = $mx;
                    $bestId = $tid;
                }
            }

            if ($bestId !== null) {
                $teamDesc[$bestId]['remaining']--;
                $assignments[] = [
                    'team_id' => $bestId,
                    'lembaga_id' => $l->id,
                    'distance_km' => round($bestD, 3),
                ];
            }
        }

        return $assignments;
    }
}
