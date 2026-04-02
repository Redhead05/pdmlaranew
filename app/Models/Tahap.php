<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tahap extends Model
{
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
    //get routekey slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

