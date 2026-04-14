<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tahap;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;
use App\Http\Controllers\Admin\KesanggupanController;
use Illuminate\Http\Request;

$tahapId = 4;
$nia1 = '3574201810113955';
$nia2 = '3374201910120140';

echo "Inspect tahap id={$tahapId}\n";
$tahap = Tahap::find($tahapId);
if (! $tahap) { echo "Tahap not found\n"; exit; }
echo "Tahap: id={$tahap->id}, end_date={$tahap->end_date}\n";

// if end_date not set or not passed, set to yesterday
if (! $tahap->end_date || $tahap->end_date->gt(now())) {
    $tahap->end_date = now()->subDay();
    $tahap->save();
    echo "Updated tahap end_date to " . $tahap->end_date . "\n";
} else {
    echo "Tahap end_date already passed\n";
}

// delete existing runs for this tahap
$runs = TeamGenerationRun::where('tahap_id', $tahapId)->get();
foreach ($runs as $r) {
    TeamDraftMember::where('run_id', $r->id)->delete();
    TeamDraft::where('run_id', $r->id)->delete();
    $r->delete();
    echo "Deleted run id: {$r->id}\n";
}

// call generator
$controller = new KesanggupanController();
$request = Request::create('/', 'POST', ['team_size' => 2]);
try {
    $resp = $controller->generateTeams($request, $tahapId);
    echo "Called generateTeams\n";
} catch (\Exception $e) {
    echo "generateTeams error: " . $e->getMessage() . "\n";
}

$run = TeamGenerationRun::where('tahap_id', $tahapId)->latest()->first();
if (! $run) { echo "No run created after generate\n"; exit; }

echo "New run id={$run->id}, status={$run->status}\n";
$teams = TeamDraft::with('members.user')->where('run_id', $run->id)->get();
foreach ($teams as $team) {
    $nias = $team->members->map(fn($m)=> $m->user->nia)->toArray();
    echo "Team {$team->team_code}: " . implode(', ', $nias) . "\n";
}

// check if both NIAs are in same team
$found = [];
foreach ($teams as $team) {
    $nias = $team->members->map(fn($m)=> $m->user->nia)->toArray();
    if (in_array($nia1, $nias) && in_array($nia2, $nias)) {
        $found[] = $team->team_code;
    }
}
if ($found) {
    echo "Both NIAs are in same team(s): " . implode(', ', $found) . "\n";
} else {
    echo "NIAs are not in same team after regenerate\n";
}

