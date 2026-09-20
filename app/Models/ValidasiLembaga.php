<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidasiLembaga extends Model
{
    protected $table = 'validasi_lembaga';

    protected $fillable = [
        'validasi_id',
        'lembaga_id',
        'user_id',
    ];

    public function validasi(): BelongsTo
    {
        return $this->belongsTo(Validasi::class, 'validasi_id');
    }

    public function lembaga(): BelongsTo
    {
        return $this->belongsTo(Lembaga::class, 'lembaga_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
