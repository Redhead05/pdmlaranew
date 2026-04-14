<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\KesanggupanController;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;

$tahapId = 4; // gunakan tahap yang relevan

// Hapus run existing untuk tahap ini
$runs = TeamGenerationRun::where('tahap_id', $tahapId)->get();
foreach ($runs as $r) {
    TeamDraftMember::where('run_id', $r->id)->delete();
    TeamDraft::where('run_id', $r->id)->delete();
    $r->delete();
    echo "Deleted run id: {$r->id}\n";
}

$controller = new KesanggupanController();
$request = Request::create('/','POST', ['team_size' => 2]);
try{
    $response = $controller->generateTeams($request, $tahapId);
    echo "generateTeams executed, response type: " . get_class($response) . "\n";
} catch (\Exception $e) {
    echo "generateTeams threw: " . $e->getMessage() . "\n";
}

// show new runs
$run = TeamGenerationRun::where('tahap_id', $tahapId)->latest()->first();
if ($run) {
    echo "New run id: {$run->id}, status: {$run->status}\n";
    $teams = TeamDraft::with('members.user')->where('run_id', $run->id)->get();
    foreach ($teams as $team) {
        $nias = $team->members->map(fn($m) => $m->user->nia)->toArray();
        echo "Team {$team->team_code}: " . implode(', ', $nias) . "\n";
    }
} else {
    echo "No run created\n";
}

