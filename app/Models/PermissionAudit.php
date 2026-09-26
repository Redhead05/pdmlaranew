<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionAudit extends Model
{
    protected $fillable = [
        'actor_id',
        'user_id',
        'action',
        'value',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
