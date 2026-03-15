<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tahap extends Model
{
    protected $fillable = ['name', 'year'];
}

public function kesanggupans(): HasMany
{
    return $this->hasMany(Kesanggupan::class, 'tahap_id');
}
