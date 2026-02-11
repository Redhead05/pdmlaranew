<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabkot extends Model
{
    protected $table = 'kabkots';
    protected $primaryKey = 'idkabkot';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $guarded = [];


    public function kecamatans(): HasMany
    {
        // foreign key on kecamatans is `idkabkot`, local key is `idkabkot`
        return $this->hasMany(Kecamatan::class, 'idkabkot', 'idkabkot');
    }
}
