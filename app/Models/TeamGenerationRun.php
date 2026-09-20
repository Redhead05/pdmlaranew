<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tahap;
use App\Models\User;

class TeamGenerationRun extends Model
{
    protected $table = 'team_generation_runs';

    protected $fillable = [
        'tahap_id',
        'status',
        'created_by',
        'finalized_by',
        'finalized_at',
        'final_pairs_payload',
        'surat_tugas_number',
        'surat_tugas_slug',
        'surat_tugas_generated_by',
        'surat_tugas_generated_at',
        'surat_tugas_notification_sent_at',
    ];

    protected $casts = [
        'finalized_at' => 'datetime',
        'surat_tugas_generated_at' => 'datetime',
        'surat_tugas_notification_sent_at' => 'datetime',
        'final_pairs_payload' => 'array',
    ];

    public function tahap(): BelongsTo
    {
        return $this->belongsTo(Tahap::class, 'tahap_id');
    }

    public function drafts(): HasMany
    {
        return $this->hasMany(TeamDraft::class, 'run_id');
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
