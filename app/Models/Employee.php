<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position',
        'start_year',
        'end_year',
        'email',
        'photo',
        'facebook',
        'instagram',
    ];

    /**
     * Scope untuk mencari employees yang overlap dengan rentang tahun yang diminta
     * Kondisi overlap: start_year <= requested_end AND (end_year IS NULL ? start_year : end_year) >= requested_start
     */
    public function scopeYearRange(Builder $query, ?int $start, ?int $end)
    {
        if ($start === null && $end === null) {
            return $query;
        }

        // jika hanya start diberikan
        if ($start !== null && $end === null) {
            return $query->where('start_year', '<=', $start)
                         ->where(function ($q) use ($start) {
                             $q->whereNull('end_year')->orWhere('end_year', '>=', $start);
                         });
        }

        if ($start === null && $end !== null) {
            return $query->where('start_year', '<=', $end);
        }

        // both provided: cari data yang overlap
        return $query->where('start_year', '<=', $end)
                     ->where(function ($q) use ($start) {
                         $q->whereNull('end_year')->orWhere('end_year', '>=', $start);
                     });
    }

    /**
     * Scope untuk yang aktif pada tahun tertentu
     */
    public function scopeActiveInYear(Builder $query, int $year)
    {
        return $query->where('start_year', '<=', $year)
                     ->where(function ($q) use ($year) {
                         $q->whereNull('end_year')->orWhere('end_year', '>=', $year);
                     });
    }
}

