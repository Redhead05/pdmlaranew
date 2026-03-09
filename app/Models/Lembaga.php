<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    protected $fillable = [
        'npsn', 'satuan_pen', 'alamat', 'kelurahan', 'kecamatan',
        'kabupaten', 'status', 'jenjang', 'bentuk_pendidikan',
        'latitude', 'longitude'
    ];


    public function detail()
    {
        return $this->hasOne(lembagadetail::class);
    }
}
