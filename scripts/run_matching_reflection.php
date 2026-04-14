<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Admin\KesanggupanController;
use App\Models\Kesanggupan;
use App\Models\User;

$tahapId = 4;
$nia1 = '3574201810113955';
$nia2 = '3374201910120140';

$eligibleRows = Kesanggupan::where('tahap_id', $tahapId)->where('kesediaan', true)->get();
$eligibleUserIds = $eligibleRows->pluck('user_id')->unique()->values()->all();

$kesMap = [];
foreach ($eligibleRows as $r) {
    $kesMap[$r->user_id] = (int) ($r->kesanggupan ?? 0);
}

$users = User::whereIn('id', $eligibleUserIds)->with('detail')->get();

$controller = new KesanggupanController();
$ref = new ReflectionClass($controller);
$method = $ref->getMethod('generateTeamsByRules');
$method->setAccessible(true);
$groups = $method->invoke($controller, $users, $kesMap);

echo "Eligible users count: " . count($users) . "\n";
foreach ($groups as $i => $g) {
    echo "Group " . ($i+1) . ":\n";
    foreach ($g as $uid) {
        $u = $users->firstWhere('id', $uid);
        if ($u) {
            echo " - " . $u->nia . " (id {$u->id}) kes=" . ($kesMap[$u->id] ?? '0') . " type=" . ($u->detail->type_asesor ?? '-') . " work_city=" . ($u->detail->work_city ?? '-') . " gender=" . ($u->detail->gender ?? '-') . "\n";
        }
    }
}

// check if both NIAs appear in same group
$found=false; foreach ($groups as $g) { $nias = array_map(function($uid) use ($users){ $u = $users->firstWhere('id',$uid); return $u ? $u->nia : null; }, $g); if (in_array($nia1,$nias) && in_array($nia2,$nias)) { $found=true; echo "\nBoth NIAs are in same group: " . implode(', ', $nias) . "\n"; } }
if (! $found) echo "\nNIAs not in same group under current matching rules\n";

