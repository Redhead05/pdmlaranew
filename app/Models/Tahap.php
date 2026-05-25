<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tahap extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tahap',
        'surat_keputusan',
        'allowed_kesanggupan',
        'start_date',
        'end_date',
        'slug',
    ];

    protected $casts = [
        'allowed_kesanggupan' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    //collection rellationship
    public function kesanggupans(): HasMany
    {
        return $this->hasMany(Kesanggupan::class, 'tahap_id');
    }

    /**
     * Relasi many-to-many ke lembagas melalui pivot table lembaga_tahap
     */
    public function lembagas()
    {
        return $this->belongsToMany(\App\Models\Lembaga::class, 'lembaga_tahap', 'tahap_id', 'lembaga_id')->withTimestamps();
    }

    /**
     * Relasi ke teams yang dibuat untuk tahap ini.
     */
    public function teams(): HasMany
    {
        return $this->hasMany(\App\Models\Team::class, 'tahap_id');
    }

    /**
     * Relasi ke generation runs untuk tahap ini.
     */
    public function generationRuns(): HasMany
    {
        return $this->hasMany(\App\Models\TeamGenerationRun::class, 'tahap_id');
    }

    /**
     * Use slug for route model binding instead of id.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Auto-generate slug when creating a new Tahap if not provided.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $base = Str::slug(($model->tahap ?? 'tahap') . '-' . now()->timestamp);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $model->slug = $slug;
            }
        });
    }
}
