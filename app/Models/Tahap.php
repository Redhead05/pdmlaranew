<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tahap extends Model
{
    use HasFactory;

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
    ];

    //collection rellationship
    public function kesanggupans(): HasMany
    {
        return $this->hasMany(Kesanggupan::class, 'tahap_id');
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
