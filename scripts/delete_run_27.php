<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TeamGenerationRun;
use App\Models\TeamDraft;
use App\Models\TeamDraftMember;

$run = TeamGenerationRun::find(27);
if ($run) {
    TeamDraftMember::where('run_id', $run->id)->delete();
    TeamDraft::where('run_id', $run->id)->delete();
    $run->delete();
    echo "Deleted run 27\n";
} else {
    echo "Run 27 not found\n";
}

