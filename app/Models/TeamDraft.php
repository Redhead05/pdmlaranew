<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\TeamGenerationRun;
use App\Models\TeamDraftMember;

class TeamDraft extends Model
{
    protected $table = 'team_drafts';

    protected $fillable = [
        'run_id',
        'team_code',
    ];

    public function run(): BelongsTo
    {
        return $this->belongsTo(TeamGenerationRun::class, 'run_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamDraftMember::class, 'team_draft_id');
    }
}
