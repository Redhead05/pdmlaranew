<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kesanggupan extends Model
{
    protected $table = 'kesanggupans';

    protected $fillable = [
        'tahap_id',
        'kesediaan',     // boolean: 1 = ya, 0 = tidak
        'kesanggupan',   // integer: 2..6
        'alasan',
    ];

    protected $casts = [
        'kesediaan' => 'boolean',
        'kesanggupan' => 'integer',
    ];

    public function tahap(): BelongsTo
    {
        return $this->belongsTo(Tahap::class, 'tahap_id');
    }
}
