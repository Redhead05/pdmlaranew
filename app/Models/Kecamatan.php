<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $table = 'kecamatans';
    protected $fillable = ['kabkot_id', 'nama_kec', 'latitude', 'longitude'];

    public function kabkot()
    {
        return $this->belongsTo(Kabkot::class, 'kabkot_id');
    }

    public function desas()
    {
        return $this->hasMany(Desa::class, 'kecamatan_id');
    }
}
