<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValidasiKesanggupan extends Model
{
    use SoftDeletes;

    protected $table = 'validasi_kesanggupans';

    protected $fillable = [
        'validasi_id',
        'user_id',
        'kesediaan',
        'alasan',
        'ttd',
        'bukti',
    ];

    protected $casts = [
        'kesediaan' => 'boolean',
    ];

    public function validasi(): BelongsTo
    {
        return $this->belongsTo(Validasi::class, 'validasi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
