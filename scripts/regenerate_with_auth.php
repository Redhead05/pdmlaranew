<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\KesanggupanController;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;

// login as admin id 1
Auth::loginUsingId(1);
if (! Auth::check()) {
    echo "Auth login failed\n";
} else {
    echo "Logged in as user id: " . Auth::id() . "\n";
}

$tahapId = 4;
$controller = new KesanggupanController();
$request = Request::create('/', 'POST', ['team_size' => 2]);
try{
    $resp = $controller->generateTeams($request, $tahapId);
    echo "generateTeams called\n";
} catch (\Exception $e) {
    echo "generateTeams exception: " . $e->getMessage() . "\n";
}

$run = TeamGenerationRun::where('tahap_id', $tahapId)->latest()->first();
if ($run) {
    echo "Run created: id={$run->id}, status={$run->status}\n";
    $teams = TeamDraft::with('members.user')->where('run_id', $run->id)->get();
    foreach ($teams as $team) {
        $nias = $team->members->map(fn($m)=> $m->user->nia)->toArray();
        echo "Team {$team->team_code}: " . implode(', ', $nias) . "\n";
    }
} else {
    echo "No run created\n";
}

