<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desa extends Model
{
    protected $table = 'desas';
    protected $primaryKey = 'id_des';
    public $incrementing = false;
    protected $keyType = 'int';
    protected $guarded = [];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kec', 'id_kec');
    }
}
