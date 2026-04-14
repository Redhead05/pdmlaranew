<?php
// scripts/pair_check.php
// Run: php scripts/pair_check.php

echo "SCRIPT START\n";

require __DIR__ . '/../vendor/autoload.php';
echo "AUTOLOAD OK\n";
$app = require_once __DIR__ . '/../bootstrap/app.php';
echo "APP BOOTSTRAPPED\n";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "KERNEL BOOTSTRAP DONE\n";

use App\Models\User;
use App\Models\Kesanggupan;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;

$nia1 = '3574201810113955';
$nia2 = '3374201910120140';

echo "LOOKUP USERS\n";
$u1 = User::with('detail')->where('nia', $nia1)->first();
$u2 = User::with('detail')->where('nia', $nia2)->first();
var_export([ 'found_u1' => (bool)$u1, 'found_u2' => (bool)$u2 ]);
echo "\n";
$run = TeamGenerationRun::latest()->first();
var_export([ 'run_exists' => (bool)$run, 'run_id' => $run? $run->id : null ]);
echo "\n";
$tahapId = $run ? $run->tahap_id : null;

function haversine($lat1, $lon1, $lat2, $lon2){
    if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) return null;
    $lat1 = floatval($lat1); $lon1 = floatval($lon1); $lat2 = floatval($lat2); $lon2 = floatval($lon2);
    $R = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2)*sin($dLat/2) + cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)*sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $R * $c;
}

function pairScore($a, $b, $tahapId){
    $score = 0;
    if (! $a || ! $b) return ['score' => null, 'distance' => null, 'reasons' => []];
    $reasons = [];
    // type_asesor
    if (($a->detail->type_asesor ?? null) && ($b->detail->type_asesor ?? null) && ($a->detail->type_asesor === $b->detail->type_asesor)){
        $score += 100; $reasons[] = 'same type_asesor (+100)';
    }
    // kesanggupan
    $aKes = $tahapId ? Kesanggupan::where('user_id', $a->id)->where('tahap_id', $tahapId)->value('kesanggupan') : null;
    $bKes = $tahapId ? Kesanggupan::where('user_id', $b->id)->where('tahap_id', $tahapId)->value('kesanggupan') : null;
    if ($aKes !== null && $bKes !== null && $aKes == $bKes && $aKes > 0){ $score += 50; $reasons[] = 'same kesanggupan (+50)'; }
    // gender
    if (($a->detail->gender ?? null) && ($b->detail->gender ?? null) && ($a->detail->gender === $b->detail->gender)){ $score += 20; $reasons[] = 'same gender (+20)'; }
    // work_city
    if (($a->detail->work_city ?? null) && ($b->detail->work_city ?? null) && ($a->detail->work_city === $b->detail->work_city)){ $score += 10; $reasons[] = 'same work_city (+10)'; }
    // distance
    $d = null;
    if (isset($a->detail->latitude, $a->detail->longitude, $b->detail->latitude, $b->detail->longitude)){
        $d = haversine($a->detail->latitude, $a->detail->longitude, $b->detail->latitude, $b->detail->longitude);
        if ($d !== null && $d <= 80){ $score += 30; $reasons[] = 'proximity <=80km (+30)'; }
    }
    return ['score' => $score, 'distance' => $d, 'reasons' => $reasons, 'aKes' => $aKes, 'bKes' => $bKes];
}

echo "COMPUTE PAIR\n";
$pairInfo = pairScore($u1, $u2, $tahapId);
var_export($pairInfo);
echo "\n";

$result = [
    'run_id' => $run? $run->id : null,
    'tahap_id' => $tahapId,
    'user1' => $u1 ? [ 'id' => $u1->id, 'nia' => $u1->nia, 'name' => $u1->name, 'detail' => $u1->detail ? $u1->detail->toArray() : null] : null,
    'user2' => $u2 ? [ 'id' => $u2->id, 'nia' => $u2->nia, 'name' => $u2->name, 'detail' => $u2->detail ? $u2->detail->toArray() : null] : null,
    'kes1' => $u1 && $tahapId ? Kesanggupan::where('user_id',$u1->id)->where('tahap_id',$tahapId)->first()?->toArray() : null,
    'kes2' => $u2 && $tahapId ? Kesanggupan::where('user_id',$u2->id)->where('tahap_id',$tahapId)->first()?->toArray() : null,
];
$result['pair'] = $pairInfo;

// check teams
$result['teams_containing_both'] = [];
if ($run){
    $teams = TeamDraft::with('members.user')->where('run_id',$run->id)->get();
    foreach ($teams as $team){
        $nias = $team->members->map(fn($m)=> $m->user->nia)->toArray();
        if (in_array($nia1, $nias) && in_array($nia2, $nias)){
            $result['teams_containing_both'][] = ['team_code' => $team->team_code, 'nias' => $nias];
        }
    }
}

echo "RESULT JSON:\n";
echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
echo "\nEND\n";

