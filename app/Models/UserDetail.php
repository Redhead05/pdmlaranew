<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
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
        'latitude',
        'longitude',
        'location_enabled',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
