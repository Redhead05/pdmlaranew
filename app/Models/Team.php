<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    protected $table = 'teams';

    protected $fillable = [
        'tahap_id',
        'code',
        'created_by',
        'finalized_by',
        'finalized_at',
    ];

    protected $casts = [
        'finalized_at' => 'datetime',
    ];

    public function tahap(): BelongsTo
    {
        return $this->belongsTo(Tahap::class, 'tahap_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    /**
     * Lembaga yang sudah dipasangkan ke tim ini pada tahap bersangkutan.
     */
    public function lembagas(): BelongsToMany
    {
        return $this->belongsToMany(Lembaga::class, 'team_lembaga', 'team_id', 'lembaga_id')
            ->withPivot('id', 'distance_km', 'is_manual')
            ->withTimestamps();
    }

    /**
     * Kuota visitasi tim: jumlah lembaga maksimal yang boleh dikunjungi.
     * Diambil dari kesanggupan terkecil anggota (tim dibentuk dengan kesanggupan sama).
     */
    public function kuota(): int
    {
        $ids = $this->members()->pluck('user_id');

        return (int) Kesanggupan::where('tahap_id', $this->tahap_id)
            ->whereIn('user_id', $ids)
            ->where('kesediaan', true)
            ->min('kesanggupan');
    }
}

