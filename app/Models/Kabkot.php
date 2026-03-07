<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabkot extends Model
{
    protected $table = 'kabkots';
    protected $fillable = ['nama_kabkot', 'latitude', 'longitude'];

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'kabkot_id');
    }
}
