<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';
    protected $primaryKey = 'id_kec';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $guarded = [];

    public function kabkot(): BelongsTo
    {
        return $this->belongsTo(KabKot::class, 'idkabkot', 'idkabkot');
    }

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class, 'id_kec', 'id_kec');
    }
}
