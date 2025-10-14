<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'created_by',
        'slug',
        'start_date',
        'end_date',
    ];

    public function AttendanceDetails()
    {
        return $this->hasMany(AttendanceDetail::class);
    }
}
