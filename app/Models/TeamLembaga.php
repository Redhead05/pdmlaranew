<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLembaga extends Model
{
    protected $table = 'team_lembaga';

    protected $fillable = [
        'tahap_id',
        'team_id',
        'lembaga_id',
        'distance_km',
        'is_manual',
        'assigned_by',
    ];

    protected $casts = [
        'distance_km' => 'float',
        'is_manual' => 'boolean',
    ];

    public function tahap(): BelongsTo
    {
        return $this->belongsTo(Tahap::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class);
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
