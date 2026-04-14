<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamDraftMember extends Model
{
    protected $table = 'team_draft_members';

    protected $fillable = [
        'run_id',
        'team_draft_id',
        'user_id',
        'is_manual',
        'assigned_by',
        'assigned_at',
    ];

    protected $casts = [
        'is_manual' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(TeamDraft::class, 'team_draft_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

