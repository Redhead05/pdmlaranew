<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function attendanceDetail()
    {
        return $this->hasMany(AttendanceDetail::class);
    }
     /**
     * Accessor: return start_date formatted as "dd mm yyyy"
     */
    public function getStartDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::parse($value)->format('d-m-Y H:i');
    }
     /**
     * Mutator: accept "dd mm yyyy" (or other parseable strings) and store as Y-m-d H:i:s
     */
    public function setStartDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['start_date'] = null;
            return;
        }

        try {
            $dt = Carbon::createFromFormat('d-m-Y H:i', $value);
        } catch (\Exception $e) {
            $dt = Carbon::parse($value);
        }

        $this->attributes['start_date'] = $dt->toDateTimeString();
    }
    public function getEndDateAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    /**
     * Mutator: accept "dd-mm-yyyy HH:ii" (or other parseable strings) and store as Y-m-d H:i:s
     */
    public function setEndDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['end_date'] = null;
            return;
        }

        try {
            $dt = Carbon::createFromFormat('d-m-Y H:i', $value);
        } catch (\Exception $e) {
            $dt = Carbon::parse($value);
        }

        $this->attributes['end_date'] = $dt->toDateTimeString();
    }
    //di bawah ini untuk mengubah format datetime yang ada di edit.balde.php
    // new accessors: return value in `Y-m-d\TH:i` for datetime-local input
    public function getStartDateForInputAttribute(): ?string
    {
        $raw = $this->attributes['start_date'] ?? null;
        if (empty($raw)) {
            return null;
        }

        try {
            return Carbon::parse($raw)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getEndDateForInputAttribute(): ?string
    {
        $raw = $this->attributes['end_date'] ?? null;
        if (empty($raw)) {
            return null;
        }

        try {
            return Carbon::parse($raw)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            return null;
        }
    }
}
