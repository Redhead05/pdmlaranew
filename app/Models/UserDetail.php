<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'address_home',
        'home_city',
        'address_work',
        'work_city',
        'gender',
        'type_asesor',
        'unit_kerja',
        'date_born',
        'ktp',
        'phone',
        'lintas_rumpun',
        'latitude',
        'longitude',
        'location_enabled',
    ];

    protected $casts = [
        'date_born' => 'date',
        'lintas_rumpun' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'location_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
